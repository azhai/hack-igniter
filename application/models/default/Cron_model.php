<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Cron_model
 */
class Cron_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_cron';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'interim' => 'varchar',
            'lastrun' => 'datetime',
        ];
    }
}
