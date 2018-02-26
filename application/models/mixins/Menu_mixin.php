<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 菜单
 */
trait Menu_mixin
{
    //use \Mylib\Behavior\MY_Cacheable;
    use \Mylib\Behavior\MY_Foreign;

    public function __construct()
    {
        parent::__construct();
        //$this->add_cache('redis', 'admin');
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'menu:' . $condition['id'];
    }

    public function get_relations()
    {
        return [
            'parent' => [
                'model' => FOREIGN_SELF_MODEL,
            ],
            'children' => [
                'type' => FOREIGN_HAS_MANY,
                'model' => FOREIGN_SELF_MODEL,
                'rev_name' => 'parent',
                'fkey' => 'parent_id',
            ],
            'privileges' => [
                'type' => FOREIGN_HAS_MANY,
                'model' => 'admin/privilege_model',
                'rev_name' => 'menu',
                'fkey' => 'menu_id',
            ],
        ];
    }
}
