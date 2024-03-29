<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 角色.
 */
class Role_model extends MY_Model
{
    use \Mylib\ORM\MY_Cache_hash;
    use \Mylib\ORM\MY_Cacheable;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_roles';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'default');
    }

    public function table_indexes($another = false)
    {
        return array('id');
    }

    public function table_fields()
    {
        return array(
            'id' => 'int',
            'title' => 'varchar',
            'remark' => 'varchar',
            'is_super' => 'tinyint',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        );
    }

    public function cache_fields()
    {
        return array(
            'id' => 'int',
            'title' => 'varchar',
            'is_super' => 'tinyint',
            'is_removed' => 'tinyint',
        );
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'role:'.$condition['id'];
    }
}
