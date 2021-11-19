<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 定时任务
 */
class Cron_task_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_cron_task';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'user_uid' => 'char',
            'refer_id' => 'int',
            'is_active' => 'bit',
            'behind' => 'smallint',
            'action_type' => 'enum',
            'cmd_url' => 'varchar',
            'args_data' => 'text',
            'last_time' => 'datetime',
            'last_result' => 'text',
            'last_error' => 'text',
        ];
    }
}
