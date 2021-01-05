<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 权限控制
 */
class Access_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_access';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'role_name' => 'varchar',
            'resource_type' => 'varchar',
            'resource_args' => 'varchar',
            'perm_code' => 'smallint',
            'actions' => 'varchar',
            'granted_at' => 'timestamp',
            'revoked_at' => 'timestamp',
        ];
    }
}