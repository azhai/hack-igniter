<?php
/**
 * hack-igniter
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @package hack-igniter
 * @author  Ryan Liu (azhai)
 * @link    http://azhai.surge.sh/
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

namespace Mylib\Behavior;


/**
 * 扩展Model，单表存储嵌套集合（树状结构）
 */
trait MY_Nested_set
{
    protected $nested_root = null;

    public function create_item(array $data = [])
    {
        return new MY_Nested_item($data);
    }

    public function get_nested_root()
    {
        if (! $this->nested_root) {
            $this->nested_root = $this->create_item();
        }
        return $this->nested_root;
    }

    public function get_nested_item($id = 0, $field = 'id')
    {
        if (empty($id)) {
            $item = $this->get_nested_root();
            if (1 === $item->right_no()) {
                $right_max = $this->max('right_no');
                $item->right_no($right_max ? $right_max + 1 : 1);
            }
        } else {
            $row = $this->one([$field => $id]);
            $item = $this->create_item($row);
        }
        return $item;
    }

    public function get_flatten_rows($id = 0, $depth = -1, $limit = null)
    {
        $orders = ['left_no' => 'ASC'];
        if (! empty($id)) {
            $root = $this->get_nested_item($id);
            $this->where('left_no >=', $root->left_no());
            $this->where('right_no <=', $root->right_no());
        }
        if ($depth >= 0) { //限制层级
            $this->where('depth <=', $root->depth() + $depth);
        }
        return $this->all($limit, 0, $orders);
    }

    public function move_top_rows(MY_Nested_item $item, $offset = 2)
    {
        $changes = [
            'left_no' => 'left_no + ' . $offset,
            'right_no' => 'right_no + ' . $offset,
        ];
        $where = ['left_no >' => $item->right_no()];
        return $this->update($changes, $where, null, false);
    }

    public function save_nested_one(array $row, $parent_id = 0)
    {
        $parent = $this->move_top_rows($parent_id);
        $item = $parent->append_child($row);
        return $this->insert($item->to_array());
    }

    public function save_nested_set(array $rows, $parent_id = 0, $truncate = false)
    {
        $db = $this->reconnect();
        $table = $this->table_name();
        if ($truncate) {
            $db->truncate($table);
            $parent_id = 0;
        }
        $parent = $this->set_nested_rows($rows, $parent_id);
        if ($parent_id && $count = $parent->count_children()) {
            $this->move_top_rows($parent, $count * 2);
        }
        if ($rows = $parent->get_children_rows()) {
            return $db->insert_batch($table, $rows);
        } else {
            return 0;
        }
    }

    public function set_nested_rows(array $rows, $id = 0)
    {
        $root = $this->get_nested_item($id);
        foreach ($rows as $row) {
            $root->append_child($row);
        }
        return $root;
    }

    public function migrate_nested_rows(array $rows, $id = 0)
    {
        $root = $this->get_nested_item($id);
        foreach ($rows as $row) {
            $row_id = $row['id'];
            $pid = $row['parent']['id'];
            if (isset($root->children[$pid])) {
                $parent = $root->children[$pid];
            } else {
                unset($row['parent']['id']);
                $parent = $root->append_child($row['parent'], $pid);
            }
            unset($row['parent'], $row['id']);
            $parent->append_child($row, $row_id);
        }
        return $root;
    }
}


class MY_Nested_item
{
    public $parent = null;
    public $children = [];
    protected $data = [
        'depth' => 0,
        'left_no' => 0,
        'right_no' => 1,
    ];

    public function __construct(array $data = [])
    {
        if ($data) {
            $this->data = array_merge($this->data, $data);
        }
    }

    public function __call($name, $args)
    {
        if (isset($this->data[$name])) {
            if (empty($args)) {
                return $this->data[$name];
            } else {
                $this->data[$name] = reset($args);
            }
        }
    }

    public static function to_item(array $data = [])
    {
        if ($data instanceof self) {
            return $data;
        } else {
            return new self($data);
        }
    }

    public function to_array()
    {
        return $this->data;
    }

    public function is_root()
    {
        return 0 === $this->left_no();
    }

    public function is_leaf()
    {
        return 0 === $this->count_children();
    }

    public function count_children()
    {
        return ($this->right_no() - $this->left_no() - 1) / 2;
    }

    public function get_children_rows()
    {
        $rows = [];
        foreach ($this->children as $child) {
            $rows[] = $child->to_array();
            if ($ch_rows = $child->get_children_rows()) {
                $rows = array_merge($rows, $ch_rows);
            }
        }
        return $rows;
    }

    protected function _set_parent(MY_Nested_item $item, $id = null, $parent = null)
    {
        if (is_null($parent)) {
            $parent = $this;
        }
        $item->parent = $parent;
        if (is_null($id) || false === $id) {
            $id = count($parent->children);
        }
        $parent->children[$id] = $item;
        do {
            $parent->right_no($parent->right_no() + 2);
        } while ($parent = $parent->parent);
        return $item;
    }

    public function attach_sibling(array $data = [], $id = null)
    {
        $item = self::to_item($data);
        $item->depth($this->depth());
        $right_no = $this->right_no();
        $item->left_no($right_no + 1);
        $item->right_no($right_no + 2);
        $this->_set_parent($item, $id, $this->parent);
        return $item;
    }

    public function append_child(array $data = [], $id = null)
    {
        $item = self::to_item($data);
        $item->depth($this->depth() + 1);
        $right_no = $this->right_no();
        $item->left_no($right_no);
        $item->right_no($right_no + 1);
        $this->_set_parent($item, $id);
        return $item;
    }
}
