<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 实体
 */
class Privilege_model extends MY_Model
{
    use \Mylib\ORM\MY_Nested_set;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_privileges';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';
    public $root_node = null;
    public $priv_nodes = [];

    public function __construct()
    {
        parent::__construct();
        $this->root_node = $this->get_root_node();
        $id = $this->root_node->id();
        $this->priv_nodes[$id] = $this->root_node;
    }

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
            'depth' => 'int',
            'left_no' => 'int',
            'right_no' => 'int',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function get_root_node()
    {
        $where = ['operation' => 'all'];
        return $this->get_nested($where);
    }

    public function get_node_by_id($id)
    {
        if (isset($this->priv_nodes[$id])) {
            return $this->priv_nodes[$id];
        }
        if (isset($this->root_node->progeny_rows[$id])) {
            $data = $this->root_node->progeny_rows[$id];
            $node = $this->root_node->create_child($data);
            return $this->priv_nodes[$id] = $node;
        }
    }
}
