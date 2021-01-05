<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 定时执行
 */
class Cron_timer_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_cron_timer';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'task_id' => 'int',
            'is_active' => 'bit',
            'run_date' => 'date',
            'run_clock' => 'char',
        ];
    }
}
