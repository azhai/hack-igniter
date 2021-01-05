<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 消息提醒
 */
class Cron_notice_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_cron_notice';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'user_uid' => 'char',
            'task_id' => 'int',
            'is_active' => 'bit',
            'important' => 'tinyint',
            'message' => 'text',
            'read_time' => 'datetime',
            'delay_start_time' => 'datetime',
            'start_time' => 'datetime',
            'stop_time' => 'datetime',
            'start_clock' => 'char',
            'stop_clock' => 'char',
        ];
    }
}