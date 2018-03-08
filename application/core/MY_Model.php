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

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model implements ArrayAccess
{
    public $result = null;

    //通用连接
    protected $_db_key = '';
    protected $_db_key_ro = '';
    protected $_db_conn = null;
    //表名，不含prefix
    protected $_table_name = '';
    protected $_table_indexes = false; //未初始化
    protected $_table_fields = [];

    public function __construct()
    {
        $this->load->helper('env');
    }

    public function __clone()
    {
        $fields = $this->table_fields();
        foreach ($fields as $field) {
            unset($this[$field]);
        }
    }

    public function __call($name, $args)
    {
        $lower_name = strtolower($name);
        if (method_exists($this, $name)) {
            throw new Exception('Method in a trait');
        }
        if (in_array($lower_name, ['sum', 'min', 'max', 'avg'], true)) {
            $db = $this->reconnect();
            $table = $this->table_name();
            $name = 'select_' . $lower_name;
            exec_method_array($db, $name, $args);
            $row = $db->get($table)->row_array();
            return reset($row);
        } else {
            $db = $this->reconnect();
            $result = exec_method_array($db, $name, $args);
            return ($result === $db) ? $this : $result;
        }
    }

    public function another_model($name, $alias = '')
    {
        if (empty($alias)) {
            if ($last = strrchr($name, '/')) {
                $alias = substr($last, 1);
            } else {
                $alias = $name;
            }
        }
        //查询关联表数据
        $this->load->model($name, $alias);
        return $this->$alias;
    }

    public function reconnect($force = false)
    {
        if ($force) {
            $this->_db_conn->close();
        }
        if (empty($this->_db_conn)) {
            $this->_db_conn = $this->load->database($this->_db_key, true);
            if (!empty($this->_db_key_ro) && $this->_db_key_ro != $this->_db_key) {
                //连接只读数据库，在simple_query()中实现连接的切换
                if ($db = $this->load->database($this->_db_key_ro, true)) {
                    $this->_db_conn->conn_writer = $this->_db_conn->conn_id;
                    $this->_db_conn->conn_reader = $db->conn_id;
                }
            }
        }
        //确保连接正常
        $this->_db_conn->reconnect();
        $this->_db_conn->initialize();
        return $this->_db_conn;
    }

    public function last_query()
    {
        $db = $this->reconnect();
        return $db->last_query();
    }

    public function db_name($another = false)
    {
        $db = $this->reconnect();
        if (false !== $another) {
            $db->db_select($another);
            return $another;
        } else {
            return $db->database;
        }
    }

    public function table_name($another = false)
    {
        if (false !== $another) {
            $this->_table_name = $another;
        }
        return $this->_table_name;
    }

    public function table_indexes($another = false)
    {
        if (is_array($another)) {
            $this->_table_indexes = $another;
        } else {
            $this->table_fields();
        }
        return $this->_table_indexes;
    }

    public function table_fields()
    {
        if (empty($this->_table_fields)) {
            $table_indexes = [];
            $table = $this->table_name();
            $db = $this->reconnect();
            $columns = $db->field_data($table);
            foreach ($columns as $c) {
                $this->_table_fields[$c->name] = $c->type;
                if ($c->primary_key) {
                    $table_indexes[] = $c->name;
                }
            }
            if (false === $this->_table_indexes) {
                $this->_table_indexes = $table_indexes;
            }
        }
        return $this->_table_fields;
    }

    public function count($column = '*', $reset = true)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if ($reset && '*' === $column) {
            return $db->count_all_results($table, true);
        }
        $db->select(sprintf('COUNT(%s)', $column));
        $sql = $db->get_compiled_select($table, $reset);
        $result = $db->query($sql);
        $count = 0;
        if ($result && $row = $result->row_array()) {
            $count = reset($row);
        }
        if (empty($reset)) {
            $db->reset_count();
        }
        return $count;
    }

    public function fetch_result()
    {
        if (!$this->result) {
            return [];
        }
        return $this->result->result_array();
    }

    public function all($limit = null, $offset = 0, array $orders = [])
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if (count($orders) > 0) {
            foreach ($orders as $key => $direct) {
                $db->order_by($key, $direct);
            }
        }
        $this->result = $db->get($table, $limit, $offset);
        return $this->fetch_result();
    }

    public function some($where, $limit = null)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                if (!is_array($value)) {
                    $db->where($key, $value);
                } elseif (count($value) <= 1) {
                    $db->where($key, reset($value));
                } else {
                    $db->where_in($key, $value);
                }
            }
        } else {
            $db->where($where);
        }
        $this->result = $db->get($table, $limit);
        return $this->fetch_result();
    }

    public function one($where = null, $type = 'array')
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        $this->result = $db->get_where($table, $where, 1);
        $rows = $this->fetch_result();
        if (is_string($type) && $type) {
            if ('array' === strtolower($type)) {
                return $rows ? $rows[0] : [];
            }
        } else {
            $type = get_class($this);
        }
        return $this->result->row(0, $type);
    }

    public function insert($row, $replace = false, $escape = null)
    {
        $row = to_array($row);
        $table = $this->table_name();
        $db = $this->reconnect();
        $method = $replace ? 'replace' : 'insert';
        if ($db->$method($table, $row, $escape)) {
            $result = $db->insert_id();
            return $result;
        }
    }

    public function insert_batch(array $set = null, $escape = null, $batch_size = 100)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        return $db->insert_batch($table, $set, $escape, $batch_size);
    }

    public function delete($where = '', $limit = null, $escape = null)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if (!empty($where)) {
            $db->where($where, '', $escape);
        }
        $result = $db->delete($table, '', $limit);
        if (method_exists($this, 'cache_subject')) {
            $this->cache_subject()->delete_cache($where);
        }
        return $result;
    }

    public function update(array $set, $where = null, $limit = null, $escape = null)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        $db->set($set, '', $escape);
        if (!empty($where)) {
            $db->where($where, '', $escape);
        }
        $db->update($table, null, null, $limit);
        if (method_exists($this, 'cache_subject')) {
            $this->cache_subject()->delete_cache($where);
        }
        return $db->affected_rows();
    }

    /**
     * 更新或插入数据
     */
    public function upsert(array $set, array $where, $field = 'changed_at', $escape = null)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        //更新时间、确保affected_rows在没有其他更新值时也返回1
        $set[$field] = date('Y-m-d H:i:s');
        $db->set($set, '', $escape);
        if (!empty($where)) {
            $db->where($where, '', $escape);
        }
        $result = $db->update($table, null, null, 1); //最多更新1行
        if (0 === $db->affected_rows()) {
            //没有改变任何行
            $set = array_merge($set, $where);
            $result = $db->insert($table, $set, $escape);
        }
        return $result;
    }

    protected function is_protected($offset)
    {
        return starts_with($offset, '_');
    }

    /**
     * Whether a offset exists
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     *                      The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        if (!$this->is_protected($offset)) {
            return property_exists($this, $offset);
        }
    }

    /**
     * Offset to retrieve
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (!$this->is_protected($offset)) {
            if (isset($this->$offset)) {
                return $this->$offset;
            }
        }
    }

    /**
     * Offset to set
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->is_protected($offset)) {
            $this->$offset = $value;
        }
    }

    /**
     * Offset to unset
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (!$this->is_protected($offset)) {
            unset($this->$offset);
        }
    }
}
