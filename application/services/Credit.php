<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 扣费加款
 */
class Credit extends MY_Service
{
    const SECOND_HAVE_MICRO = 1e6; // 1秒是多少微妙
    const USING_TRANSCATION = true; // 是否使用事务
    const USING_SPIN_LOCK = false; // 是否使用自旋锁

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('account/User_credit_model');
        $this->load->model('account/User_credit_log_model');
    }

    /**
     * 按月读取消费记录
     *
     * @param int $userid 用户的userid
     * @param string $month 月份
     * @param string $category = 'all'  操作类型 call/gift/payment/other/all
     * @param int $offset = 0 偏移量
     * @param int $limit = -1 限量，小于0表示无限
     * @return array
     */
    public function get_bill_list($userid, $month, $category = 'all', $offset = 0, $limit = -1)
    {
        if (false !== preg_match('/(\d+)\D*(\d+)/', $month, $matches)) {
            $start = strtotime(sprintf('%4d-%02d-01', $matches[1], $matches[2]));
        } else {
            $start = date('Y-m-01');
        }
        $stop = strtotime(date('Y-m-01', $start + 86400 * 31)) - 1;
        $where = ['userid' => $userid, 'number <>' => 0];
        $category = strtolower($category);
        $branches = ['call', 'gift', 'payment'];
        if ('other' === $category) {
            $where['operation NOT IN'] = $branches;
        } elseif (in_array($category, $branches, true)) {
            $where['operation'] = $category;
        }
        if ($limit < 0) {
            $limit = null;
        }
//        $this->user_credit_log_model->with_foreign('user', 'related');
        return $this->user_credit_log_model->all_more($where, $start, $stop, $limit, $offset);
    }

    /**
     * 读取余额。可选择从主库读
     *
     * @param $userids 单个或多个用户的userid
     * @param bool $master = false  是否强制读取主库
     * @return array 用户的userid和账户的关联数组
     */
    public function read_balances($userids, $master = false)
    {
        $userids = is_array($userids) ? $userids : explode(',', $userids);
        if (empty($userids)) {
            return [];
        }
        if ($master) {
            $this->user_credit_model->set_force_master(true);
        }
        $this->user_credit_model->where_in('userid', $userids);
        $rows = $this->user_credit_model->all();
        return array_column($rows, null, 'userid');
    }

    /**
     * 余额变动操作，自旋锁版本
     *
     * @param int $userid 用户的userid
     * @param string $coin_type 货币名称
     * @param float $number 变动金额，正数为加款，负数为扣费
     * @param float $remain = 0  预留金额，仅用于强制扣费
     * @param bool $force = false 是否强制扣费
     * @return array 错误码、实付、余额
     */
    public function charge_balance($userid, $coin_type, $number, $remain = 0, $force = false)
    {
        $result = [1, 0, 0]; //$errno, $paid, $balance
        $retry_times = 5;
        while ($retry_times > 0) {
            $retry_times--;
            if (self::USING_TRANSCATION && !$this->user_credit_model->trans_start()) {
                usleep(0.01 * self::SECOND_HAVE_MICRO);
                continue;
            } else if (self::USING_SPIN_LOCK && !$this->_acquire_spinlock($userid)) {
                usleep(0.01 * self::SECOND_HAVE_MICRO);
                continue;
            }
            if ($number >= 0) {
                $result = $this->_charge_raise($userid, $coin_type, $number);
            } else {
                $result = $this->_charge_reduce($userid, $coin_type, $number, $remain, $force);
            }
            if (self::USING_TRANSCATION) {
                $this->user_credit_model->trans_complete();
            } else if (self::USING_SPIN_LOCK) {
                $this->_release_spinlock($userid);
            }
            break;
        }
        return $result;
    }

    /**
     * 记录余额变动日志
     *
     * @param int $userid 用户的userid
     * @param int $relatedid 关联用户的userid
     * @param array $data 必填参数集
     *              string credittype  货币名称
     *              float  number  变动金额，正数为加款，负数为扣费
     *              float  count 账户余额
     * @param array $options = [] 备选参数集
     *              float  prepay  预付金额，正数为加款，负数为扣费
     *              string operation = ''  操作类型
     *              string description = '' 操作描述
     * @return int 日志ID
     */
    public function logging_operation($userid, $relatedid, array $data, array $options = [], $another = false)
    {
        $prepay = isset($options['prepay']) ? $options['prepay'] : 0.0;
        $operation = isset($options['operation']) ? $options['operation'] : '';
        if ($another) {
            $data['userid'] = $relatedid;
            $data['relatedid'] = $userid;
            $description = isset($options['another_description']) ? $options['another_description'] : '';
        } else {
            $data['userid'] = $userid;
            $data['relatedid'] = $relatedid;
            $description = isset($options['description']) ? $options['description'] : '';
        }
        $data = array_merge($data, [
            'prepay' => $prepay, 'operation' => $operation, 'description' => $description,
            'underling' => 0, 'dateline' => time(), 'ipaddr' => get_real_client_ip(),
        ]);
        $insert_id = $this->user_credit_log_model->insert($data);
        return intval($insert_id);
    }

    /**
     * 改变余额并记录日志
     *
     * @param int $userid 用户的userid
     * @param int $relatedid 关联用户的userid
     * @param string $coin_type 货币名称
     * @param float $number 变动金额，正数为加款，负数为扣费
     * @param array $options = [] 备选参数集
     *              float  prepay  预付金额，正数为加款，负数为扣费
     *              float  remain = 0  预留金额，仅用于强制扣费
     *              bool   force = false 是否强制扣费
     *              string operation = ''  操作类型
     *              string description = '' 操作描述
     * @return array 日志ID、实付、余额
     */
    public function charge_with_logging($userid, $relatedid, $coin_type, $number, array $options = [])
    {
        $remain = isset($options['remain']) ? floatval($options['remain']) : 0;
        $force = isset($options['force']) ? $options['force'] : false;
        @list($errno, $paid, $balance) = $this->charge_balance($userid, $coin_type, $number, $remain, $force);
        $insert_id = 0;
        if (0 === $errno || ($force && $paid < 0)) {
            $prepay = isset($options['prepay']) ? $options['prepay'] : 0.0;
            $data = [
                'credittype' => $coin_type, 'count' => $balance,
                'prepay' => $prepay, 'number' => $paid + $prepay,
            ];
            $insert_id = $this->logging_operation($userid, $relatedid, $data, $options);
        }
        return [$insert_id, $paid, $balance];
    }

    /**
     * 扣款当前账户并奖励对方
     *
     * @param int $userid 用户的userid
     * @param int $relatedid 关联用户的userid
     * @param float $number 变动金额，正数为加款，负数为扣费
     * @param array $reward 返还，可能为ratio比例，值为0-1之间的小数，或者jifen与对应的数量
     * @param array $options = [] 备选参数集
     *              float  prepay  预付金额，正数为加款，负数为扣费
     *              float  remain = 0  预留金额，仅用于强制扣费
     *              bool   force = false 是否强制扣费
     *              string operation = ''  操作类型
     *              string description = '' 操作描述
     *              string another_description = '' 对方的操作描述
     * @return array 日志ID、实付、余额
     */
    public function charge_and_reward($userid, $relatedid, $number, array $reward, array $options = [])
    {
        $result = [];
        $prepay = isset($options['prepay']) ? $options['prepay'] : 0.0;
        $remain = isset($options['remain']) ? floatval($options['remain']) : 0;
        $force = isset($options['force']) ? $options['force'] : false;
        @list($errno, $paid, $balance) = $this->charge_balance($userid, 'gold', $number, $remain, $force);
        $insert_id = 0;
        if (0 === $errno || ($force && $paid < 0)) {
            $data = [
                'credittype' => 'gold', 'count' => $balance,
                'prepay' => $prepay, 'number' => $paid + $prepay,
            ];
            $insert_id = $this->logging_operation($userid, $relatedid, $data, $options);
        }
        $result[] = [$insert_id, $paid, $balance];

        //返还
        if (isset($reward['ratio'])) {
            $number = abs($paid + $prepay) * $reward['ratio'];
            $coin_type = 'jifen';
        } else {
            $number = reset($reward);
            $coin_type = key($reward);
        }
        if ($paid < 0 && $number > 0) {
            @list($errno2, $paid2, $balance) = $this->charge_balance($relatedid, $coin_type, $number);
            $data = ['credittype' => $coin_type, 'number' => $number, 'count' => $balance];
            $insert_id = $this->logging_operation($userid, $relatedid, $data, $options, true);
            $result[] = [$insert_id, $number, $balance];
        }
        return $result;
    }

    /**
     * 加款，不存在记录时新加一条
     *
     * @param int $userid 用户的userid
     * @param string $coin_type 货币名称
     * @param float $number 变动金额，正数为加款，负数为扣费
     * @return array 错误码、实付、余额
     */
    protected function _charge_raise($userid, $coin_type, $number)
    {
        if ($number < 0) {
            return [1, 0, 0]; // 失败
        }
        $change = sprintf('+ %.2f', $number);
        $db = $this->user_credit_model->reconnect();
        $table = $this->user_credit_model->table_name();
        $tpl = "UPDATE `%s` SET dateline = %d, %s = %s %s WHERE userid = '%s'";
        $sql = sprintf($tpl, $table, time(), $coin_type, $coin_type, $change, $userid);
        $success = ($db->query($sql) && $db->affected_rows() > 0); //操作成功
        if (empty($success)) { //不存在记录，新写入一行
            $count = floatval($number);
            $tpl = "INSERT INTO `%s` (userid, dateline, %s) VALUES ('%s', %d, %.2f)";
            $db->query(sprintf($tpl, $table, $coin_type, $userid, time(), $count));
        } else {
            $balances = $this->read_balances($userid, true);
            $count = floatval($balances[$userid][$coin_type]);
        }
        return [0, $number, $count];
    }

    /**
     * 扣费，强制扣费直到0为止
     *
     * @param int $userid 用户的userid
     * @param string $coin_type 货币名称
     * @param float $number 变动金额，正数为加款，负数为扣费
     * @param float $remain = 0  预留金额，仅用于强制扣费
     * @param bool $force = false 是否强制扣费
     * @return array 错误码、实付、余额
     */
    protected function _charge_reduce($userid, $coin_type, $number, $remain = 0, $force = false)
    {
        $number = abs($number);
        $change = sprintf('- %.2f', $number);
        if (empty($force)) { //普通扣费，余额足够才操作
            $where = sprintf(' AND %s >= %.2f', $coin_type, $number + $remain);
        } else { //强制扣费，有余额才操作
            $where = sprintf(' AND %s > 0', $coin_type);
        }
        $db = $this->user_credit_model->reconnect();
        $table = $this->user_credit_model->table_name();
        $tpl = "UPDATE `%s` SET dateline = %d, %s = %s %s WHERE userid = '%s'%s";
        $sql = sprintf($tpl, $table, time(), $coin_type, $coin_type, $change, $userid, $where);
        $success = ($db->query($sql) && $db->affected_rows() > 0); //操作成功，包括强制扣费为负数
        $balances = $this->read_balances($userid, true);
        if (!isset($balances[$userid])) { //不存在记录，新写入一行余额为0
            $tpl = "INSERT INTO `%s` (userid, dateline, %s) VALUES ('%s', %d, 0)";
            $db->query(sprintf($tpl, $table, $coin_type, $userid, time()));
            return [1, 0, 0];
        }
        $count = floatval($balances[$userid][$coin_type]);
        if ($success && $count >= 0) { //余额足够
            $actually_paid = 0 - $number;
            return [0, $actually_paid, $count];
        } else if (empty($force)) { //不足没有操作
            return [1, 0, $count];
        }
        //不足扣除全部余额
        $tpl = "UPDATE `%s` SET %s = 0 WHERE userid = '%s' AND %s < 0";
        $db->query(sprintf($tpl, $table, $coin_type, $userid, $coin_type));
        $actually_paid = 0 - $number - $count;
        return [0, $actually_paid, 0];
    }

    /**
     * 请求自旋锁
     *
     * @param int $userid 用户的userid
     * @param bool $blocking = false 是否阻塞，为false时立即返回，为true时等到得到锁才返回
     * @return bool
     */
    protected function _acquire_spinlock($userid, $blocking = false)
    {
        if (!function_exists('apcu_add')) {
            return true;
        }
        $key = 'lock-balance:' . $userid;
        $value = time();
        if (apcu_add($key, $value, 300)) {
            return false; //已存在
        }
        return apcu_store($key, $value, 300);
    }

    /**
     * 释放自旋锁
     *
     * @param int $userid 用户的userid
     * @return bool
     */
    protected function _release_spinlock($userid)
    {
        if (!function_exists('apcu_delete')) {
            return true;
        }
        $key = 'lock-balance:' . $userid;
        return apcu_delete($key);
    }
}
