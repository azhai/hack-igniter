<?php

defined('BASEPATH') || exit('No direct script access allowed');

require_once APPPATH.'libraries/Txim.php';

/**
 * 发送腾讯云消息.
 */
class Sender extends MY_Service
{
    public const IM_QUEUE_NAME = 'im-queue';

    protected $url_apart = 'https://console.tim.qq.com/v4/openim/sendmsg';
    protected $url_batch = 'https://console.tim.qq.com/v4/openim/batchsendmsg';
    protected $url_all = 'https://console.tim.qq.com/v4/all_member_push/im_push';

    protected $tlssig_config = [];
    protected $secretary_id = ''; //小秘书的userid
    protected $sys_notice_id = ''; //系统通知的userid

    /**
     * 构造函数.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('alg');
        $this->load->model('message/message_log_model');
        $this->load->model('cdr_log/cdr_log_model');
        $this->load->library('MY_Tx_msg', [], 'txmsg');
        $this->config->load('tlssigConfig', true, true);
        $this->tlssig_config = $this->config->item('tlssigConfig');
        $this->secretary_id = $this->tlssig_config['secretary_id'];
        $this->sys_notice_id = $this->tlssig_config['sys_notice_id'];
        if (! isset($this->redisdb3)) {
            $this->load->cache('redis', 'gift', 'gift_cache');
            $this->redisdb3 = $this->gift_cache->redis->instance();
        }
    }

    /**
     * 获取系统消息或小秘书的账号.
     *
     * @param mixed $appid
     * @param mixed $is_secretary
     */
    public function get_sys_sender($appid = '', $is_secretary = false)
    {
        return $is_secretary ? $this->secretary_id : $this->sys_notice_id;
    }

    /**
     * 生成usersig.
     *
     * @param string $identifier 用户名
     *
     * @return string 生成的UserSig 失败时为false
     */
    public function gen_sign($identifier)
    {
        $txim = new Txim($this->tlssig_config['appid'], $this->tlssig_config['adminsig']);

        return $txim->genUserSig($identifier);
    }

    /**
     * 生成usersig，使用Python脚本.
     *
     * @param string $identifier 用户名
     *
     * @return string 生成的UserSig 失败时为false
     */
    public function gen_sign_py($identifier)
    {
        $userid = trim(escapeshellarg($identifier), "'");
        $command = sprintf('%s/tools/signature.py %s 2>&1', APPPATH, $userid);
        $ret = exec($command, $output, $status);

        return (-1 === $status) ? false : $ret;
    }

    /**
     * 腾讯云接口网址
     *
     * @param int $push_type 0=单发 1=群发 2=全员
     *
     * @return string
     */
    public function get_request_url($push_type = 0)
    {
        $query_string = $this->get_query_string();

        switch ((int) $push_type) {
            case 2:
                $url = $this->url_all.$query_string;

                break;

            case 1:
                $url = $this->url_batch.$query_string;

                break;

            default:
                $url = $this->url_apart.$query_string;

                break;
        }

        return $url.$query_string;
    }

    /**
     * 队列名称.
     *
     * @param int $push_type 0=单发 1=群发 2=全员
     *
     * @return string
     */
    public function get_queue_name($push_type = 0)
    {
        switch ((int) $push_type) {
            case 2:
                $queue_name = self::IM_QUEUE_NAME.'-all';

                break;

            case 1:
                $queue_name = self::IM_QUEUE_NAME.'-batch';

                break;

            default:
                $queue_name = self::IM_QUEUE_NAME;

                break;
        }

        return $queue_name;
    }

    /**
     * 添加要发送的消息.
     *
     * @param array $msgdata   消息完整内容
     * @param mixed $push_type
     *
     * @return bool
     */
    public function add_message(array $msgdata, $push_type = 0)
    {
        $queue_name = $this->get_queue_name($push_type);
        if ($json_data = to_json($msgdata)) {
            $this->redisdb3->lPush($queue_name, $json_data);

            return true;
        }

        return false;
    }

    /**
     * 发送文本消息.
     *
     * @param array $msgdata 消息数据
     * @param string/array $to （多个）接收者
     *
     * @return int
     */
    public function send_more(array $msgdata, $to)
    {
        if (! is_array($to)) {
            $to = [$to];
        }
        $count = 0;
        foreach ($to as $one) {
            $msgdata['To_Account'] = (string) $one;
            if ($this->add_message($msgdata)) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * 发送文本消息.
     *
     * @param string $text 消息内容
     * @param string $from 发送者
     * @param string/array $to （多个）接收者
     * @param bool $is_system = false 是否系统消息，如果是，则MsgOper = 1
     * @param int  $life_time = 604800 消息保留时间，默认7天
     *
     * @return int
     */
    public function send_text($text, $from, $to, $is_system = false, $life_time = 604800)
    {
        $this->txmsg->create($text, $life_time);
        $msgdata = $this->txmsg->buildText($from, 0, $is_system);

        return $this->send_more($msgdata, $to);
    }

    /**
     * 发送小秘书消息.
     *
     * @param string $text 消息内容
     * @param string/array $to （多个）接收者
     * @param string $appid APP代码
     *
     * @return int
     */
    public function send_secret_text($text, $to, $appid = '')
    {
        $sender = $this->get_sys_sender($appid, true);

        return $this->send_text($text, $sender, $to, true);
    }

    /**
     * 发送tips消息.
     *
     * @param string $text 消息内容
     * @param string $from 发送者
     * @param string/array $to （多个）接收者
     * @param bool $is_system = false 是否系统消息，如果是，则MsgOper = 1
     *
     * @return int
     */
    public function send_tips($text, $from, $to, $is_system = false)
    {
        $this->txmsg->create($text);
        $msgdata = $this->txmsg->buildTips($from, 0, $is_system);

        return $this->send_more($msgdata, $to);
    }

    /**
     * 发送系统提醒消息.
     *
     * @param string $text 消息内容
     * @param string/array $to （多个）接收者
     * @param string $appid APP代码
     *
     * @return int
     */
    public function send_notice_text($text, $to, $appid = '')
    {
        $sender = $this->get_sys_sender($appid);

        return $this->send_tips($text, $sender, $to, true);
    }

    /**
     * 发送自定义类型消息.
     *
     * @param string/int $from 发送方
     * @param string/int $to 接收方
     * @param string $type 数据类型
     * @param mixed  $data 具体数据
     *
     * @return int
     */
    public function send_custom($from, $to, $type, $data)
    {
        $msgdata = $this->txmsg->buildCustomData($from, $to, $type, $data);

        return $this->add_message($msgdata) ? 1 : 0;
    }

    /**
     * 发送任务达到通知
     * status  [任务状态 1=新福利 2=待领取 3=领取完成 4=隐藏福利中心].
     *
     * @param string/int $userid 接收方
     * @param array  $task    任务数据
     * @param string $message 消息内容
     * @param string $appid   APP代码
     *
     * @return int
     */
    public function send_task($userid, $task, $message, $appid = '')
    {
        $sender = $this->get_sys_sender($appid);
        $task_fields = 'id,task_name,task_url,task_image,reward_num,reward_type';
        $data = ['state' => 2, 'message' => $message,
            'url' => 'web://'.WEBSITE_URL.'/web_page/user_task.php',
            'task' => array_part($task, $task_fields), ];
        $msgdata = $this->txmsg->buildCustomData($sender, $userid, 'welfare_center', $data);

        return $this->add_message($msgdata) ? 1 : 0;
    }

    /**
     * 发送余额不足提示和充值按钮.
     *
     * @param string/int $userid 接收方
     * @param string $text  展示文案
     * @param string $appid APP代码
     *
     * @return int
     */
    public function send_lock_of_money($userid, $text, $appid = '')
    {
        $sender = $this->get_sys_sender($appid);
        $user_fields = 'userid,nickname,headphoto,smallheadpho,midleheadpho';
        $sender = $this->user_service->get_profile($sender, $user_fields);
        $params = 'paymodes=alipay&title=获取金币&mtitle=金币不足';
        $button = ['name' => '去获取金币', 'url' => 'in://fastpay?'.$params];
        $this->txmsg->create($text);
        $msgdata = $this->txmsg->buildPopUp($sender, $userid, [$button]);

        return $this->add_message($msgdata) ? 1 : 0;
    }

    /**
     * 给双方发送134挂断信号.
     *
     * @param array/string $detail 话单ID或者话单信息（除挂断原因外）
     * @param string $reason 挂断原因
     * @param string/int $from  通话一方
     * @param string/int $to  通话另一方
     * @param null|mixed $isvideo
     *
     * @return int
     */
    public function send_hang_up($detail, $reason = '', $from = 0, $to = 0, $isvideo = null)
    {
        $detail = is_array($detail) ? $detail : ['callid' => $detail];
        assert(isset($detail['callid']) && $detail['callid'] > 0);
        $detail['reason'] = $reason;
        if ($from > 0) {
            $detail['from_userid'] = $from;
        }
        if ($to > 0) {
            $detail['to_userid'] = $to;
        }
        if (null !== $isvideo) {
            $detail['isvideo'] = $isvideo ? '1' : '0';
        }
        $count = 0;
        $msgdata = $this->txmsg->buildHangUp($detail['from_userid'], $detail['to_userid'], $detail);
        $count += $this->add_message($msgdata) ? 1 : 0;
        $msgdata = $this->txmsg->buildHangUp($detail['to_userid'], $detail['from_userid'], $detail);
        $count += $this->add_message($msgdata) ? 1 : 0;

        return $count;
    }

    /**
     * 全员推送
     *
     * @param string $content 内容
     * @param string $appid   APP代码
     *
     * @return int
     */
    public function all_member_push($content, $appid = '')
    {
        $sender = $this->get_sys_sender($appid);
        $this->txmsg->create('', 60); //有效时间
        $msgdata = $this->txmsg->buildAllMemberPush($sender, $content, 'bullet_chat');
        debug_error('all_member_push data: %s', to_json($msgdata));

        return $this->add_message($msgdata, 2) ? 1 : 0;
    }

    /**
     * 腾讯云账号参数.
     */
    protected function get_query_string()
    {
        $usersig = trim(str_replace(["\n", "\r", "\t"], '', $this->tlssig_config['usersig']));
        $account = http_build_query([
            'sdkappid' => $this->tlssig_config['appid'],
            'identifier' => $this->tlssig_config['adminname'],
            'usersig' => $usersig,
        ]);

        return '?contenttype=json&apn=1&'.$account;
    }
}
