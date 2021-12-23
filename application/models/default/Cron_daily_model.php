<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 日常执行.
 */
class Cron_daily_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_cron_daily';

    public function table_indexes($another = false)
    {
        return array('id');
    }

    public function table_fields()
    {
        return array(
            'id' => 'int',
            'task_id' => 'int',
            'is_active' => 'bit',
            'workday' => 'bit',
            'weekday' => 'tinyint',
            'run_clock' => 'char',
        );
    }
}
