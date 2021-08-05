<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/Txim.php';

use Mylib\Util\Client;

/**
 * 发送腾讯云消息
 */
class Sender extends MY_Service
{
    const IM_QUEUE_NAME = 'im-queue';

    protected $url_apart = 'https://console.tim.qq.com/v4/openim/sendmsg';
    protected $url_batch = 'https://console.tim.qq.com/v4/openim/batchsendmsg';

    protected $tlssig_config = [];
    protected $secretary_id = ''; //小秘书的userid
    protected $sys_notice_id = ''; //系统通知的userid

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('TxMsg', [], 'txmsg');
        $this->config->load('tlssigConfig', true, true);
        $this->tlssig_config = $this->config->item('tlssigConfig');
        $this->secretary_id = $this->tlssig_config['secretary_id'];
        $this->sys_notice_id = $this->tlssig_config['sys_notice_id'];
        $this->load->cache('redis', 'gift', 'gift_cache');
        $this->redisdb3 = $this->gift_cache->redis->instance();
    }

    /**
     * 生成usersig
     * @param string $identifier 用户名
     * @return string 生成的UserSig 失败时为false
     */
    public function gen_sign($identifier)
    {
        $txim = new Txim($this->tlssig_config['appid'], $this->tlssig_config['adminsig']);
        return $txim->genUserSig($identifier);
    }

    /**
     * 生成usersig，使用Python脚本
     * @param string $identifier 用户名
     * @return string 生成的UserSig 失败时为false
     */
    public function gen_sign_py($identifier)
    {
        $userid = trim(escapeshellarg($identifier), "'");
        $command = sprintf('%s/tools/signature.py %s 2>&1', APPPATH, $userid);
        $ret = exec($command, $output, $status);
        return ($status == -1) ? false : $ret;
    }

    /**
     * 腾讯云账号参数
     */
    protected function get_query_string()
    {
        $usersig = trim(str_replace(["\n", "\r", "\t"], '', $this->tlssig_config['usersig']));
        $account = http_build_query(array(
            'sdkappid' => $this->tlssig_config['appid'],
            'identifier' => $this->tlssig_config['adminname'],
            'usersig' => $usersig,
        ));
        return '?contenttype=json&apn=1&' . $account;
    }

    /**
     * 腾讯云接口网址
     * @param false $is_batch 是否使用群发接口
     * @return string
     */
    public function get_request_url($is_batch = false)
    {
        $query_string = $this->get_query_string();
        if ($is_batch) {
            return $this->url_batch . $query_string;
        } else {
            return $this->url_apart . $query_string;
        }
    }

    /**
     * 队列名称
     * @return string
     */
    public function get_queue_name()
    {
        return self::IM_QUEUE_NAME;
    }

    /**
     * 添加要发送的消息
     * @param array $msgdata 消息完整内容
     * @return bool
     */
    public function add_message(array $msgdata)
    {
        if ($json_data = json_encode($msgdata, 320)) {
            $this->redisdb3->lPush(self::IM_QUEUE_NAME, $json_data);
            return true;
        }
        return false;
    }

    /**
     * 发送文本消息
     *
     * @param array $msgdata 消息数据
     * @param string/array $to （多个）接收者
     * @return int
     */
    public function send_more(array $msgdata, $to)
    {
        if (!is_array($to)) {
            $to = [$to, ];
        }
        $count = 0;
        foreach ($to as $one) {
            $msgdata['To_Account'] = (string)$one;
            if ($this->add_message($msgdata)) {
                $count ++;
            }
        }
        return $count;
    }

    /**
     * 发送文本消息
     *
     * @param string $text 消息内容
     * @param string $from 发送者
     * @param string/array $to （多个）接收者
     * @param bool $is_system = false 是否系统消息，如果是，则MsgOper = 1
     * @param int $life_time = 604800 消息保留时间，默认7天
     * @return int
     */
    public function send_text($text, $from, $to, $is_system = false, $life_time = 604800)
    {
        $this->txmsg->create($text, $life_time);
        $msgdata = $this->txmsg->buildText($from, 0, $is_system);
        return $this->send_more($msgdata, $to);
    }

    /**
     * 发送小秘书消息
     *
     * @param string $text 消息内容
     * @param string/array $to （多个）接收者
     * @return int
     */
    public function send_secret_text($text, $to)
    {
        return $this->send_text($text, $this->secretary_id, $to, true);
    }

    /**
     * 发送tips消息
     *
     * @param string $text 消息内容
     * @param string $from 发送者
     * @param string/array $to （多个）接收者
     * @param bool $is_system = false 是否系统消息，如果是，则MsgOper = 1
     * @return int
     */
    public function send_tips($text, $from, $to, $is_system = false)
    {
        $this->txmsg->create($text);
        $msgdata = $this->txmsg->buildTips($from, 0, $is_system);
        return $this->send_more($msgdata, $to);
    }

    /**
     * 发送系统提醒消息
     *
     * @param string $text 消息内容
     * @param string/array $to （多个）接收者
     * @return int
     */
    public function send_notice_text($text, $to)
    {
        return $this->send_tips($text, $this->sys_notice_id, $to, true);
    }
}
