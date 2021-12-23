<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 用户角色.
 */
class User_role_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_user_role';

    public function table_indexes($another = false)
    {
        return array('id');
    }

    public function table_fields()
    {
        return array(
            'id' => 'int',
            'user_uid' => 'char',
            'role_name' => 'varchar',
        );
    }
}
