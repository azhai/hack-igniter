<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Log_system_model
 */
class Log_system_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_log_system';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
              'id' => 'int',
              'level' => 'int',
              'date' => 'datetime',
              'message' => 'varchar',
              'ownerid' => 'int',
              'requesturi' => 'varchar',
              'route' => 'varchar',
              'ip' => 'varchar',
              'context' => 'varchar',
              'source' => 'longtext',
        ];
    }
}