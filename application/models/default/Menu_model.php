<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 菜单
 */
class Menu_model extends MY_Model
{
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_menus';

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
     * 读取两层菜单
     */
    public function get_menu_rows($where = null)
    {
        $this->with_foreign('children');
        $this->order_by('parent_id', 'ASC');
        $this->order_by('seqno', 'ASC');
        return $this->some($where);
    }

    /**
     * 根据parent_id分组设置排序号
     * @return [type] [description]
     */
    public function auto_set_seqno()
    {
        $cols = ['parent_id as pid', 'GROUP_CONCAT(id) as ids'];
        $rows = $this->group_by('parent_id')->all(null, 0, $cols);
        foreach ($rows as $row) {
            $ids = explode(',', $row['ids']);
            sort($ids, SORT_NUMERIC);
            foreach ($ids as $i => $id) {
                $this->update(['seqno' => $i*10+10], ['id' => $id]);
            }
        }
    }
}
