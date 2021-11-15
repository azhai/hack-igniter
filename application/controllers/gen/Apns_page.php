<?php
/**
 * hack-igniter
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @package hack-igniter
 * @author  Ryan Liu (azhai)
 * @link    http://azhai.surge.sh/
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * iOS的Apns消息推送服务
 */
class Apns_page extends MY_Controller
{
    protected $redis = null;

    protected function initialize()
    {
        $cache = $this->load->cache('redis', 'apns');
        $this->redis = $cache->redis->instance();
    }

    /**
     * 向用户推送消息，以json格式写入redis队列
     *
     * @param $userid  用户
     * @param $message 消息文本
     * @param $data    其他消息属性
     * @return bool
     */
    public function pushmsg($userid, $message, array $data = [])
    {
        $this->load->model('user/user_token_model');
        $row = $this->user_token_model->get_last_token($userid); //最后一个有效token
        if (empty($row)) {
            return false; //未找到可用token
        }
        if ($row['voip_token']) { //优先使用voip
            $data['token'] = $row['voip_token'];
            $channel = 'voip';
        } else {
            $data['token'] = $row['token'];
            $channel = $row['channel'];
        }
        $data['text'] = trim($message);
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $queue_name = 'apns_' . $channel;
        return $this->redis->lPush($queue_name, $json);
    }

    /**
     * 单个证书的推送服务进程.
     */
    public function pushloop($channel = 'prod')
    {
        $this->config->load('apns', true, true);
        $params = $this->config->item($channel, 'apns');
        $queue_name = 'apns_' . $channel;
        $this->load->library('MY_Apns', $params, $queue_name);
        $apns = $this->$queue_name;
        $server = $apns->getPushServer();
        $invalid_queue = $apns->getInvalidQueueName();
        $queues = [$queue_name,];

        $server->start();
        while ($server->run()) { // Main loop...
            // Check the error queue
            $inv_tokens = $apns->getPushInvalidTokens();
            foreach ($inv_tokens as $inv_token) {
                $this->redis->lPush($invalid_queue, $inv_token);
                log_message('INFO', 'Invalid token: ' . $inv_token);
            }

            if ($item = $this->redis->brPop($queues, 30)) {
                list($queue, $data) = $item;
                if (!is_array($data)) {
                    $data = json_decode($data, true);
                }
                // Instantiate a new Message with a single recipient
                $message = Apns::createMessage('', $data);
                // Add the message to the message queue
                $server->add($message);
            }
        }
    }

    /**
     * 全部证书的接收错误token进程.
     */
    public function feedloop()
    {
        $this->config->load('apns', true, true);
        $all_params = $this->config->item('apns');
        //初始化
        $apns_pool = [];
        foreach ($all_params as $channel => $params) {
            $queue_name = 'apns_' . $channel;
            $this->load->library('MY_Apns', $params, $queue_name);
            $apns_pool[$channel] = $this->$queue_name;
        }

        //接收错误token
        do {
            foreach ($apns_pool as & $apns) {
                $inv_tokens = $apns->getFeedInvalidTokens();
                foreach ($inv_tokens as $inv_token) {
                    $this->redis->lPush(QUEUE_INVALID, $inv_token);
                    log_message('INFO', 'Invalid token: ' . $inv_token);
                }
                sleep(20);
            }
            sleep(900);
        } while (true);
    }
}
