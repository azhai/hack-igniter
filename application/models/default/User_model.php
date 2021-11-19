<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 用户.
 */
class User_model extends MY_Model
{
    use \Mylib\ORM\MY_Cache_hash;
    use \Mylib\ORM\MY_Cacheable;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_users';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'password' => 'varchar',
            'nickname' => 'varchar',
            'email' => 'varchar',
            'phone' => 'varchar',
            'last_seen' => 'datetime',
            'last_ipaddr' => 'varchar',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }
}
