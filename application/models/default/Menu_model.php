<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 菜单
 */
class Menu_model extends MY_Model
{
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Cache_Hash;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_menus';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'test');
    }

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
            'corner' => 'varchar',
            'seqno' => 'tinyint',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function get_relations()
    {
        return [
            'parent' => [
                'model' => FOREIGN_SELF_MODEL,
                'columns' => ['id', 'parent_id', 'title',
                    'url', 'icon', 'corner', 'is_removed']
            ],
            'children' => [
                'type' => FOREIGN_HAS_MANY,
                'model' => FOREIGN_SELF_MODEL,
                'rev_name' => 'parent',
                'fkey' => 'parent_id',
                'columns' => ['id', 'parent_id', 'title',
                    'url', 'icon', 'corner', 'is_removed']
            ],
        ];
    }

    public function cache_fields()
    {
        return [
            'id' => 'int',
            'parent_id' => 'int',
            'title' => 'varchar',
            'url' => 'varchar',
            'icon' => 'varchar',
            'corner' => 'varchar',
            'is_removed' => 'tinyint',
        ];
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'menu:' . $condition['id'];
    }

    /**
     * 根据parent_id分组设置排序号
     * @return [type] [description]
     */
    public function auto_set_seqno()
    {
        $this->parse_select(['pid' => 'parent_id', 'ids' => 'GROUP_CONCAT(id)']);
        $rows = $this->group_by('parent_id')->all();
        foreach ($rows as $row) {
            $ids = explode(',', $row['ids']);
            sort($ids, SORT_NUMERIC);
            foreach ($ids as $i => $id) {
                $this->update(['seqno' => $i * 10 + 10], ['id' => $id]);
            }
        }
    }

    /**
     * 读取菜单和子菜单
     */
    public function get_menu_rows(array $menu_ids)
    {
        $menu_ids = $menu_ids ?: [-1,];
        $this->with_foreign('parent');
        $this->order_by('parent_id', 'ASC')->order_by('seqno', 'ASC');
        $this->where_in('id', $menu_ids)->where('is_removed', 0);
        $this->or_where_in('parent_id', $menu_ids)->where('is_removed', 0);
        return $this->all();
    }

    /**
     * 读取最上两层菜单
     */
    public function get_all_menus()
    {
        $menus = $branch_ids = [];
        $this->with_foreign('children');
        $where = ['parent_id' => 0, 'is_removed' => 0];
        $rows = $this->parse_where($where)->all();
        foreach ($rows as $row) {
            if ($row['children']) {
                $row['children'] = array_column($row['children'], null, 'id');
                $branch_ids = array_merge($branch_ids, array_keys($row['children']));
            }
            $menus[$row['id']] = $row;
        }
        ksort($menus);
        return [$menus, $branch_ids];
    }

    /**
     * 读取授权菜单和子菜单
     */
    public function get_grant_menus(array $menu_ids)
    {
        $menus = $branch_ids = [];
        $rows = $this->get_menu_rows($menu_ids);
        foreach ($rows as $row) {
            if ($pid = $row['parent_id']) {
                if (!isset($menus[$pid])) {
                    $row['parent']['children'] = [];
                    $menus[$pid] = $row['parent'];
                }
                $menus[$pid]['children'][] = $row;
                if ('#' === $row['url']) {
                    $branch_ids[] = $row['id'];
                }
            } else {
                $row['children'] = [];
                $menus[$row['id']] = $row;
            }
        }
        ksort($menus);
        return [$menus, $branch_ids];
    }

    /**
     * 去除未授权菜单和子菜单
     */
    public function get_remain_menus(array $menu_ids)
    {
        $unbranch_ids = [];
        list($menus, $branch_ids) = $this->get_all_menus();
        $rows = $this->get_menu_rows($menu_ids);
        foreach ($rows as $row) {
            if ($pid = $row['parent_id']) {
                if (isset($menus[$pid])) {
                    unset($menus[$pid]['children'][$row['id']]);
                }
                if ('#' === $row['url']) {
                    $unbranch_ids[] = $row['id'];
                }
            } else {
                unset($menus[$row['id']]);
            }
        }
        $branch_ids = array_diff($branch_ids, $unbranch_ids);
        return [$menus, $branch_ids];
    }
}
