<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 角色
 */
trait Role_mixin
{
    use \Mylib\Behavior\MY_Foreign;

    public function get_relations()
    {
        return [
            'privileges' => [
                'type' => FOREIGN_MANY_TO_MANY,
                'model' => 'admin/privilege_model',
                'rev_name' => 'roles',
                'fkey' => 'role_id',
                'another_fkey' => 'privilege_id',
                'middle_model' => 'admin/role_privilege_model',
            ],
        ];
    }
}
