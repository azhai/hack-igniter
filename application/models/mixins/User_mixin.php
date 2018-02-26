<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 管理员
 */
trait User_mixin
{
    use \Mylib\Behavior\MY_Cacheable;

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'admin');
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
