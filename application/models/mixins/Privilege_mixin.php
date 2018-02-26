<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 权限
 */
trait Privilege_mixin
{
    use \Mylib\Behavior\MY_Foreign;

    public function get_relations()
    {
        return [
            'roles' => [
                'type' => FOREIGN_MANY_TO_MANY,
                'model' => 'admin/role_model',
                'rev_name' => 'privileges',
                'fkey' => 'privilege_id',
                'another_fkey' => 'role_id',
                'middle_model' => 'admin/role_privilege_model',
            ],
            'menu' => [
                'model' => 'admin/menu_model',
            ],
        ];
    }
}
