<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 管理员
 */
class Admin_model extends MY_Model
{
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Cache_Hash;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_admins';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'default');
    }

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'role_id' => 'int',
            'username' => 'varchar',
            'password' => 'varchar',
            'nickname' => 'varchar',
            'gender' => 'enum',
            'email' => 'varchar',
            'phone' => 'varchar',
            'last_seen' => 'datetime',
            'last_ipaddr' => 'varchar',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function cache_fields()
    {
        return [
            'id' => 'int',
            'role_id' => 'int',
            'username' => 'varchar',
            'nickname' => 'varchar',
            'gender' => 'enum',
            'email' => 'varchar',
            'phone' => 'varchar',
            'is_removed' => 'tinyint',
        ];
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'user:' . $condition['id'];
    }

    public function get_relations()
    {
        return [
            'role' => [
                'model' => 'default/role_model',
                'columns' => ['id', 'title', 'is_super', 'is_removed'],
            ],
        ];
    }

    public function check_password($username, $password)
    {
        $row = $this->one(['username' => $username]);
        if (empty($row) || $row['is_removed']) {
            return; //账号不存在或已禁用
        }
        if ($row['password']) {
            $this->load->library('MY_Portable_hash', null, 'hasher');
            $ok = $this->hasher->check_password($password, $row['password']);
            if ($ok) {
                $fields = ['id', 'role_id', 'username', 'nickname', 'gender'];
                return array_intersect_key($row, array_fill_keys($fields, null));
            }
        }
        return false; //密码不正确
    }
}
