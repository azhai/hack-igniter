<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Log_change_model
 */
class Log_change_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_log_change';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
              'id' => 'int',
              'date' => 'datetime',
              'ownerid' => 'int',
              'title' => 'varchar',
              'contenttype' => 'varchar',
              'contentid' => 'int',
              'mutation_type' => 'varchar',
              'diff' => 'longtext',
              'comment' => 'varchar',
        ];
    }
}