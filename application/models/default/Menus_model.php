<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 菜单
 */
class Menus_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_menus';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'parent_id' => 'int',
            'title' => 'varchar',
            'url' => 'varchar',
            'icon' => 'varchar',
            'seqno' => 'tinyint',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }
}