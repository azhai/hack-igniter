<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Authtoken_model
 */
class Authtoken_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_authtoken';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'user_id' => 'int',
            'username' => 'varchar',
            'token' => 'varchar',
            'salt' => 'varchar',
            'lastseen' => 'datetime',
            'ip' => 'varchar',
            'useragent' => 'varchar',
            'validity' => 'datetime',
        ];
    }
}
