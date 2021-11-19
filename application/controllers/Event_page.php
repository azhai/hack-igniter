<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 事件槽.
 */
class Event_page extends MY_Controller
{
    public const SLOT_PREFIX = 'slot_';

    protected $event;
    protected $client;

    /**
     * 注册已有的事件.
     */
    public function initialize()
    {
        parent::initialize();
        $this->load->driver('MY_Logger');
        $this->event = new \Mylib\Util\Event();
        $methods = get_class_methods($this);
        $prelen = strlen(self::SLOT_PREFIX);
        foreach ($methods as $name) {
            $name = strtolower($name);
            if (! starts_with($name, self::SLOT_PREFIX)) {
                continue;
            }
            $event_name = str_replace('_', '.', substr($name, $prelen));
            $this->event->on($event_name, [$this, $name]);
        }
    }

    public function slot_curl_init($base_url)
    {
        $this->client = new \Mylib\Util\Client($base_url);
        $this->client->setLogMethod([$this->logger, 'log']);
        $domains = ['localhost' => '127.0.0.1'];
        $this->client->beforeSend(replaceHostCallback(true, 80, $domains));
    }
}
