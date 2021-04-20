<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 角色权限
 */
class Role_privilege_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_role_privileges';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'role_id' => 'int',
            'menu_id' => 'int',
            'privilege_id' => 'int',
            'is_revoked' => 'tinyint',
        ];
    }

    public function before_delete($is_resume = false, $escape = null)
    {
        return ['is_revoked' => $is_resume ? 0 : 1];
    }

    public function get_role_privs($role_id, $is_revoked = 0)
    {
        $where = ['role_id' => $role_id, 'is_revoked' => $is_revoked];
        return $this->parse_where($where)->all();
    }

    public function save_role_privs(array $rows, $role_id)
    {
        $uniq = 'menu_id:privilege_id';
        $where = ['role_id' => $role_id];
        return $this->diff_save_data($rows, $uniq, $where);
    }
}
