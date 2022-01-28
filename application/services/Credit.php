<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 扣费加款.
 */
class Credit extends MY_Service
{
    public const SECOND_HAVE_MICRO = 1e6;   // 1秒是多少微妙
    public const MAX_RETRY_TIMES = 3;     // 最大重试次数
    public const USING_TRANSCATION = true;  // 是否使用事务
    public const USING_SPIN_LOCK = false; // 是否使用自旋锁

    /**
     * 构造函数.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('account/User_credit_model');
        $this->load->model('account/User_credit_log_model');
    }

    /**
     * 倒数重试次数.
     *
     * @param int $retries  重试次数
     * @param int $sleep_ms = 0 休眠时间，单位：毫秒
     */
    public static function count_down($retries, $sleep_ms = 0, $offset_ms = 0)
    {
        if ($retries <= 0) { //不需要重试
            return false;
        }
        if ($sleep_ms <= 0) { //不需要休眠
            return true;
        }
        if ($offset_ms > 0) { //随机浮动以错峰运行
            $minial_ms = max($sleep_ms - $offset_ms, 1);
            $sleep_ms = rand($minial_ms, $sleep_ms + $offset_ms);
        }
        $sleep_usec = $sleep_ms * 1000;
        usleep($sleep_usec);
        return true;
    }

    /**
     * 按月读取消费记录.
     *
     * @param int    $userid   用户的userid
     * @param string $month    月份
     * @param string $category = 'all'  操作类型 call/gift/payment/other/all
     * @param int    $offset   = 0 偏移量
     * @param int    $limit    = -1 限量，小于0表示无限
     *
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
     * 读取余额。可选择从主库读.
     *
     * @param $userids 单个或多个用户的userid
     * @param bool $master      = false  是否强制读取主库
     * @param int  $retry_times = 3  重试次数
     *
     * @return array 用户的userid和账户的关联数组
     */
    public function read_balances($userids, $master = false, $retry_times = 3)
    {
        $userids = is_array($userids) ? $userids : explode(',', $userids);
        if (empty($userids)) {
            return [];
        }
        do {
            if ($master) {
                $this->user_credit_model->set_force_master(true);
            }
            $this->user_credit_model->where_in('userid', $userids);
            $rows = $this->user_credit_model->all();
        } while (empty($rows) && self::count_down(--$retry_times, 150));

        return array_column($rows, null, 'userid');
    }

    /**
     * 读取或创建余额。可选择从主库读.
     *
     * @param string $userid      用户的userid
     * @param bool   $master      = false  是否强制读取主库
     * @param int    $retry_times = 3  重试次数
     *
     * @return array 账户关联数组
     */
    public function get_or_create_balance($userid, $master = false, $retry_times = 3)
    {
        $table = $this->user_credit_model->table_name();
        $where = ['userid' => $userid];
        do {
            if ($master) {
                $this->user_credit_model->set_force_master(true);
            }
            $row = $this->user_credit_model->one($where);
            if ($is_failure = empty($row)) {
                $tpl = "INSERT IGNORE `%s` (userid, dateline) VALUES ('%s', %d)";
                $db = $this->user_credit_model->reconnect();
                $sql = sprintf($tpl, $table, $userid, time());
                log_debug("%s => SQL insert: %s", __FUNCTION__, $sql);
                $insert_id = $db->query($sql) ? $db->insert_id() : 0;
                log_debug("%s => SQL insert result: %d", __FUNCTION__, $insert_id);
            }
        } while ($is_failure && self::count_down(--$retry_times, 150));

        return $row;
    }

    /**
     * 余额变动操作.
     *
     * @param int    $userid    用户的userid
     * @param float  $number    变动金额，正数为加款，负数为扣费
     * @param float  $discount  = 0  折扣，九五折可以是0.95或者-0.05
     * @param string $coin_type = 'goldcoin' 货币名称
     * @param bool   $force     = false 是否强制扣费
     *
     * @return array 错误码、实付、余额
     */
    public function charge_balance($userid, $number, $discount = 0, $coin_type = 'goldcoin', $force = false)
    {
        //$errno, $paid（实付与$number相同符号）, $balance
        $result = [1, 0, 0];
        $retry_times = self::MAX_RETRY_TIMES;
        do {
            log_debug("%s => user %s change %.2f %s discount %.2f retry x%d",
                __FUNCTION__, $userid, $number, $coin_type, $discount, $retry_times);
            if (self::USING_TRANSCATION && ! $this->user_credit_model->trans_start()) {
                usleep(0.01 * self::SECOND_HAVE_MICRO * $retry_times);

                continue;
            }
            if (self::USING_SPIN_LOCK && ! $this->_acquire_spinlock($userid)) {
                usleep(0.01 * self::SECOND_HAVE_MICRO * $retry_times);

                continue;
            }
            if ($number >= 0) {
                $result = $this->_charge_raise($userid, $number, $discount, $coin_type);
            } else {
                $result = $this->_charge_reduce($userid, $number, $discount, $coin_type, $force);
            }
            if (self::USING_TRANSCATION) {
                $this->user_credit_model->trans_complete();
            } elseif (self::USING_SPIN_LOCK) {
                $this->_release_spinlock($userid);
            }
            if ($number >= 0 || 0 === (int) ($result[0]) || $result[2] <= 0) {
                break; //成功或无钱可扣
            }
        } while (self::count_down(--$retry_times, 200, 50));

        return $result;
    }

    /**
     * 强制扣费.
     *
     * @param int    $userid    用户的userid
     * @param float  $number    变动金额，正数为加款，负数为扣费
     * @param float  $discount  = 0  折扣，九五折可以是0.95或者-0.05
     * @param string $coin_type = 'goldcoin' 货币名称
     *
     * @return array 错误码、实付、余额
     */
    public function force_charge_balance($userid, $number, $discount = 0, $coin_type = 'goldcoin')
    {
        $number = 0 - abs($number);

        return $this->charge_balance($userid, $number, $discount, $coin_type, true);
    }

    /**
     * 记录余额变动日志.
     *
     * @param int   $userid    用户的userid
     * @param int   $relatedid 关联用户的userid
     * @param array $data      必填参数集
     *                         float  number  变动金额，正数为加款，负数为扣费
     *                         float  count 账户余额
     *                         string credittype  货币名称
     * @param array $options   = [] 备选参数集
     *                         float  prepay = 0  预付金额，正数为加款，负数为扣费
     *                         string only_proof = null  唯一凭证
     *                         string operation = null  操作类型
     *                         string description = null 操作描述
     * @param mixed $another
     *
     * @return int 日志ID
     */
    public function logging_operation($userid, $relatedid, array $data, array $options = [], $another = false)
    {
        $data['prepay'] = isset($options['prepay']) ? $options['prepay'] : 0.0;
        $data['operation'] = isset($options['operation']) ? $options['operation'] : '';
        if (! isset($data['credittype'])) {
            $data['credittype'] = 'gold';
        } elseif ('coin' === substr($data['credittype'], -4)) { //资产和流水中货币名称有出入
            $data['credittype'] = substr($data['credittype'], 0, -4);
        }
        if (isset($options['only_proof'])) {
            $data['only_proof'] = $options['only_proof'];
        }
        if ($another) {
            $data['userid'] = $relatedid;
            $data['relatedid'] = $userid;
            if (isset($options['another_description'])) {
                $data['description'] = $options['another_description'];
            }
        } else {
            $data['userid'] = $userid;
            $data['relatedid'] = $relatedid;
            if (isset($options['description'])) {
                $data['description'] = $options['description'];
            }
        }
        $ipaddr = get_real_client_ip();
        $data = array_replace($data, ['underling' => 0, 'dateline' => time(), 'ipaddr' => $ipaddr]);
        $insert_id = $this->user_credit_log_model->insert($data);

        return (int) $insert_id;
    }

    /**
     * 改变余额并记录日志.
     *
     * @param int    $userid    用户的userid
     * @param int    $relatedid 关联用户的userid
     * @param float  $number    变动金额，正数为加款，负数为扣费
     * @param string $coin_type = 'goldcoin' 货币名称
     * @param array  $options   = [] 备选参数集
     *                          float  discount = 0  折扣，九五折可以是0.95或者-0.05
     *                          float  prepay = 0  预付金额，正数为加款，负数为扣费
     *                          float  remain = 0  预留金额，仅用于强制扣费
     *                          bool   force = false 是否强制扣费
     *                          string only_proof = ''  唯一凭证
     *                          string operation = ''   操作类型
     *                          string description = '' 操作描述
     *
     * @return array 日志ID、实付、余额
     */
    public function charge_with_logging($userid, $relatedid, $number, $coin_type = 'goldcoin', array $options = [])
    {
        $discount = isset($options['discount']) ? (float) ($options['discount']) : 0;
        $force = isset($options['force']) ? $options['force'] : false;
        @list($errno, $paid, $balance) = $this->charge_balance($userid, $number, $discount, $coin_type, $force);
        $insert_id = 0;
        if (0 === $errno || ($force && $paid < 0)) {
            $prepay = isset($options['prepay']) ? $options['prepay'] : 0.0;
            $data = ['credittype' => $coin_type, 'count' => $balance, 'number' => $paid + $prepay];
            $insert_id = $this->logging_operation($userid, $relatedid, $data, $options);
        }

        return [$insert_id, $paid, $balance];
    }

    /**
     * 扣款当前账户并奖励对方.
     *
     * @param int   $userid    用户的userid
     * @param int   $relatedid 关联用户的userid
     * @param float $number    变动金额，正数为加款，负数为扣费
     * @param array $reward    返还，可能为ratio比例，值为0-1之间的小数，或者jifen与对应的数量
     * @param array $options   = [] 备选参数集
     *                         float  discount = 0  折扣，九五折可以是0.95或者-0.05
     *                         float  prepay = 0  预付金额，正数为加款，负数为扣费
     *                         bool   force = false 是否强制扣费
     *                         string operation = ''  操作类型
     *                         string only_proof = ''  唯一凭证
     *                         string description = '' 操作描述
     *                         string another_description = '' 对方的操作描述
     *
     * @return array 日志ID、实付、余额
     */
    public function charge_and_reward($userid, $relatedid, $number, array $reward, array $options = [])
    {
        $result = [];
        $discount = isset($options['discount']) ? (float) ($options['discount']) : 0;
        $prepay = isset($options['prepay']) ? $options['prepay'] : 0.0;
        $force = isset($options['force']) ? $options['force'] : false;
        @list($errno, $paid, $balance) = $this->charge_balance($userid, $number, $discount, 'goldcoin', $force);
        $total_paid = $paid + $prepay; //总支出，综合预付和结余（如果多付了，结余会是正数）
        $insert_id = 0;
        if (0 === $errno || ($force && $total_paid < 0)) {
            $data = ['credittype' => 'goldcoin', 'count' => $balance, 'number' => $total_paid];
            $insert_id = $this->logging_operation($userid, $relatedid, $data, $options);
        }
        $result[] = [$insert_id, $total_paid, $balance];

        //返还
        $ratio = isset($reward['ratio']) ? (float) ($reward['ratio']) : 0;
        if (0 === $ratio) {
            $number = reset($reward);
            $coin_type = key($reward);
        } else {
            $number = abs($total_paid);
            $coin_type = 'jifen';
        }
        if ($total_paid <= 0 && $number > 0) {
            @list($errno2, $gained, $balance) = $this->charge_balance($relatedid, $number, $ratio, $coin_type);
            $data = ['credittype' => $coin_type, 'number' => $gained, 'count' => $balance];
            $options['prepay'] = 0.0;
            $insert_id = $this->logging_operation($userid, $relatedid, $data, $options, true);
            $result[] = [$insert_id, $gained, $balance];
        }

        return $result;
    }

    /**
     * 计算折扣.
     *
     * @param float $number   原始金额，正数为加款，负数为扣费
     * @param float $discount = 0  折扣，九五折可以是0.95或者-0.05
     *
     * @return float 折后金额，与原始金额符号相同
     */
    protected function _calc_discount($number, $discount = 0)
    {
        if (0 === $discount || $discount < -1 || $discount >= 1) {
            return $number; //无折扣
        }
        if ($discount <= 0) {
            $discount += 1.0;
        }

        return bcmul($discount, $number, 1);
    }

    /**
     * 加款，不存在记录时新加一条
     *
     * @param int    $userid    用户的userid
     * @param float  $number    变动金额，正数为加款，负数为扣费
     * @param float  $discount  = 0  折扣，九五折可以是0.95或者-0.05
     * @param string $coin_type = 'jifen' 货币名称
     *
     * @return array 错误码、实付、余额
     */
    protected function _charge_raise($userid, $number, $discount = 0, $coin_type = 'jifen')
    {
        if ($number < 0) {
            return [1, 0, 0]; // 失败
        }
        if (0 !== $discount) {
            $number = $this->_calc_discount($number, $discount);
        }
        $change = sprintf('+ %.2f', $number);
        $db = $this->user_credit_model->reconnect();
        $table = $this->user_credit_model->table_name();
        $tpl = "UPDATE `%s` SET dateline = %d, %s = %s %s WHERE userid = '%s' LIMIT 1";
        $sql = sprintf($tpl, $table, time(), $coin_type, $coin_type, $change, $userid);
        $success = ($db->query($sql) && $db->affected_rows() > 0); //操作成功
        if (empty($success)) { //不存在记录，新写入一行
            $count = (float) $number;
            $tpl = "INSERT IGNORE `%s` (userid, dateline, %s) VALUES ('%s', %d, %.2f)";
            $sql = sprintf($tpl, $table, $coin_type, $userid, time(), $count);
            log_debug("%s => SQL insert: %s", __FUNCTION__, $sql);
            $insert_id = $db->query($sql) ? $db->insert_id() : 0;
            log_debug("%s => SQL insert result: %d", __FUNCTION__, $insert_id);
        }
        $balances = $this->get_or_create_balance($userid, true);
        $count = (float) ($balances[$coin_type]);
        debug_output('user %s result ok, add %.2f remain %.2f', $userid, $number, $count);

        return [0, $number, $count];
    }

    /**
     * 扣费，强制扣费直到0为止.
     *
     * @param int    $userid    用户的userid
     * @param float  $number    变动金额，正数为加款，负数为扣费
     * @param float  $discount  = 0  折扣，九五折可以是0.95或者-0.05
     * @param string $coin_type = 'goldcoin' 货币名称
     * @param bool   $force     = false 是否强制扣费
     *
     * @return array 错误码、实付、余额
     */
    protected function _charge_reduce($userid, $number, $discount = 0, $coin_type = 'goldcoin', $force = false)
    {
        if (0 !== $discount) {
            $number = $this->_calc_discount($number, $discount);
        }
        $db = $this->user_credit_model->reconnect();
        $table = $this->user_credit_model->table_name();
        $balances = $this->get_or_create_balance($userid, true);
        $count = (float) ($balances[$coin_type]);
        $abs_number = abs($number);
        if ($count >= $abs_number) {
            $actually_paid = 0 - $abs_number;
        } elseif ($force) { //强制扣费，有余额才操作
            $actually_paid = 0 - $count;
        } else { //普通扣费，余额足够才操作
            return [1, 0, $count];
        }
        $amount = $count + $actually_paid;
        $tpl = "UPDATE `%s` SET dateline = %d, %s = %.2f WHERE userid = '%s' AND %s = %.2f LIMIT 1";
        $sql = sprintf($tpl, $table, time(), $coin_type, $amount, $userid, $coin_type, $count);
        $success = ($db->query($sql) && $db->affected_rows() > 0); //操作成功，包括强制扣费为负数
        if ($success) {
            log_debug("%s => user %s result ok, spent %.2f remain %.2f", __FUNCTION__, $userid, $actually_paid, $amount);
            return [0, $actually_paid, $amount];
        } else {
            log_debug("%s => user %s result fail, spent 0.0 remain %.2f", __FUNCTION__, $userid, $count);
            return [1, 0, $count];
        }
    }

    /**
     * 请求自旋锁
     *
     * @param int  $userid   用户的userid
     * @param bool $blocking = false 是否阻塞，为false时立即返回，为true时等到得到锁才返回
     *
     * @return bool
     */
    protected function _acquire_spinlock($userid, $blocking = false)
    {
        if (! function_exists('apcu_add')) {
            return true;
        }
        $key = 'lock-balance:'.$userid;
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
     *
     * @return bool
     */
    protected function _release_spinlock($userid)
    {
        if (! function_exists('apcu_delete')) {
            return true;
        }
        $key = 'lock-balance:'.$userid;

        return apcu_delete($key);
    }
}
