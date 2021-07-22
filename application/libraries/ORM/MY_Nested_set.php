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

namespace Mylib\ORM;

/**
 * 扩展Model，单表存储嵌套集合（树状结构）
 */
trait MY_Nested_set
{
    protected $nested_root = null;

    public function create_node(array $data = [])
    {
        return new MY_Nested_node($data);
    }

    public function get_nested_root()
    {
        if (!$this->nested_root) {
            $this->nested_root = $this->create_node();
        }
        return $this->nested_root;
    }

    public function get_nested_node($id = 0, $where = null)
    {
        if (empty($id) && empty($where)) {
            $node = $this->get_nested_root();
            if ($node->right_no() <= 1) {
                $right_max = $this->max('right_no');
                $node->right_no($right_max ? $right_max + 1 : 1);
            }
        } else {
            if (is_numeric($id) && $id > 0) {
                $where = ['id' => $id];
            }
            $data = $this->one($where);
            $node = $this->create_node($data);
        }
        return $node;
    }

    public function get_nested($where = null, $depth = -1)
    {
        $rows = $this->get_flatten_rows($where, $depth);
        $data = $rows ? array_shift($rows) : [];
        $node = $this->create_node($data);
        if ($rows) {
            $node->progeny_rows = array_column($rows, null, 'id');
        }
        return $node;
    }

    public function get_flatten_rows($where = null, $depth = -1, $limit = null)
    {
        if (!empty($where)) {
            $root = $this->get_nested_node(0, $where);
            $this->where('left_no >=', $root->left_no());
            $this->where('right_no <=', $root->right_no());
        }
        if ($depth >= 0) { //限制层级
            $this->where('depth <=', $root->depth() + $depth);
        }
        return $this->order_by('left_no', 'ASC')->all($limit);
    }

    public function move_top_rows(MY_Nested_node $node, $offset = 2)
    {
        $changes = [
            'left_no' => 'left_no + ' . $offset,
            'right_no' => 'right_no + ' . $offset,
        ];
        $where = ['left_no >' => $node->right_no()];
        return $this->update($changes, $where, null, false);
    }

    public function save_nested_one(array $row, $parent_id = 0)
    {
        $parent = $this->move_top_rows($parent_id);
        $node = $parent->append_child($row);
        return $this->insert($node->to_array());
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
        $root = $this->get_nested_node($id);
        foreach ($rows as $row) {
            $root->append_child($row);
        }
        return $root;
    }

    public function migrate_nested_rows(array $rows, $id = 0)
    {
        $root = $this->get_nested_node($id);
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


/**
 * 树状节点
 */
class MY_Nested_node
{
    public $parent = null;
    public $children = [];
    public $progeny_rows = [];
    protected $data = [
        'depth' => 0,
        'left_no' => 0,
        'right_no' => 0,
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

    public static function to_node(array $data = [])
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

    public function get_self_parent_ids()
    {
        $result = [];
        $node = $this;
        do {
            $result[] = $node->id();
        } while ($node = $node->parent);
        return $result;
    }

    public function count_children()
    {
        return ($this->right_no() - $this->left_no() - 1) / 2;
    }

    public function create_child(array $data)
    {
        $node = new self($data);
        $left_no = $node->left_no();
        $right_no = $node->right_no();
        foreach ($this->progeny_rows as $key => $row) {
            if ($row['left_no'] <= $left_no) {
                continue;
            }
            if ($row['right_no'] >= $right_no) {
                break;
            }
            $node->progeny_rows[$key] = &$this->progeny_rows[$key];
        }
        $node->parent = $this;
        return $node;
    }

    protected function _children_to_progeny_rows()
    {
        $rows = [];
        foreach ($this->children as $child) {
            $rows[] = $child->to_array();
            if ($ch_rows = $child->get_progeny_rows()) {
                $rows = array_merge($rows, $ch_rows);
            }
        }
        return $rows;
    }

    public function get_progeny_rows()
    {
        if (empty($this->progeny_rows) && $this->children) {
            $rows = $this->_children_to_progeny_rows();
            $this->progeny_rows = array_column($rows, null, 'id');
        }
        return $this->progeny_rows;
    }

    public function get_options($column, $index = null, $include_self = false)
    {
        $rows = $this->get_progeny_rows();
        $result = $rows ? array_column($rows, $column, $index) : [];
        if ($include_self) {
            $col_val = $this->data[$column] ?? '';
            if ($index && $idx_val = $this->data[$index]) {
                $result = array_merge([$idx_val => $col_val], $result);
            } else {
                array_unshift($result, $col_val);
            }
        }
        return $result;
    }

    protected function _set_parent(MY_Nested_node $node, $id = null, $parent = null)
    {
        if (is_null($parent)) {
            $parent = $this;
        }
        $node->parent = $parent;
        if (is_null($id) || false === $id) {
            $id = count($parent->children);
        }
        $parent->children[$id] = $node;
        do {
            $parent->right_no($parent->right_no() + 2);
        } while ($parent = $parent->parent);
        return $node;
    }

    public function attach_sibling(array $data = [], $id = null)
    {
        $node = self::to_node($data);
        $node->depth($this->depth());
        $right_no = $this->right_no();
        $node->left_no($right_no + 1);
        $node->right_no($right_no + 2);
        $this->_set_parent($node, $id, $this->parent);
        return $node;
    }

    public function append_child(array $data = [], $id = null)
    {
        $node = self::to_node($data);
        $node->depth($this->depth() + 1);
        $right_no = $this->right_no();
        $node->left_no($right_no);
        $node->right_no($right_no + 1);
        $this->_set_parent($node, $id);
        return $node;
    }
}
