<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * User_model
 */
class User_model extends MY_Model
{
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_users';

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
            'username' => 'varchar',
            'password' => 'varchar',
            'email' => 'varchar',
            'lastseen' => 'datetime',
            'lastip' => 'varchar',
            'displayname' => 'varchar',
            'stack' => 'longtext',
            'enabled' => 'tinyint',
            'shadowpassword' => 'varchar',
            'shadowtoken' => 'varchar',
            'shadowvalidity' => 'datetime',
            'failedlogins' => 'int',
            'throttleduntil' => 'datetime',
            'roles' => 'longtext',
        ];
    }

    public function get_relations()
    {
        return [
            'entries' => [
              'type' => FOREIGN_HAS_MANY,
              'model' => 'default/entry_model',
              'rev_name' => 'owner',
              'fkey' => 'ownerid',
            ],
        ];
    }

    public function cache_fields()
    {
        return [
            'id' => 'int',
            'username' => 'varchar',
            'email' => 'varchar',
            'displayname' => 'varchar',
            'enabled' => 'tinyint',
            'roles' => 'longtext',
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

    public function check_password($username, $password)
    {
        $row = $this->one(['username' => $username]);
        if (empty($row) || empty($row['enabled'])) {
            return; //账号不存在或已禁用
        }
        if ($row['password']) {
            $this->load->library('MY_Portable_hash', null, 'hasher');
            $ok = $this->hasher->check_password($password, $row['password']);
            if ($ok) {
                $fields = ['id', 'username', 'displayname', 'email', 'roles'];
                return array_intersect_key($row, array_fill_keys($fields, null));
            }
        }
        return false; //密码不正确
    }
}
