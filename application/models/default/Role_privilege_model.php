<?php
defined('BASEPATH') OR exit('No direct script access allowed');


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

    public function get_rows_where($role_id, $is_revoked = 0)
    {
        $where = ['role_id' => $role_id, 'is_revoked' => $is_revoked];
        return $this->some($where);
    }

    public function save_rows_where(array $rows, $role_id, $is_revoked = 0)
    {
        $newbies = $exists = [];
        foreach ($rows as $row) {
            $key = sprintf('m%d:p%d', $row['menu_id'], $row['privilege_id']);
            $newbies[$key] = $row;
        }
        $where = ['role_id' => $role_id];
        $rows = $this->some($where);
        foreach ($rows as $row) {
            $key = sprintf('m%d:p%d', $row['menu_id'], $row['privilege_id']);
            $exists[$key] = $row['id'];
        }
        $this->trans_start();
        //禁用所有
        if ($limit = count($exists)) {
            $this->where_in('id', array_values($exists));
            $this->update(['is_revoked' => 1 - $is_revoked], $where, $limit);
        }
        //启用交叉部分
        if ($remains = array_intersect_key($exists, $newbies)) {
            $this->where_in('id', array_values($remains));
            $this->update(['is_revoked' => $is_revoked], $where, $limit);
        }
        //增加多出部分
        if ($additions = array_diff_key($newbies, $exists)) {
            foreach ($additions as & $row) {
                $row['role_id'] = $role_id;
                $row['is_revoked'] = $is_revoked;
            }
            $this->insert_batch(array_values($additions));
        }
        $this->trans_complete();
    }
}
