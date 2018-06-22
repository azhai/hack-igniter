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
}