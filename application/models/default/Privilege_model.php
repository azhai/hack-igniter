<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 实体
 */
class Privilege_model extends MY_Model
{
    use \Mylib\ORM\MY_Nested_set;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_privileges';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'menu_id' => 'int',
            'operation' => 'varchar',
            'remark' => 'varchar',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }
}
