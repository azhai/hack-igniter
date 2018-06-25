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

/**
 * 基础Model，设置了数据库和表名
 * 例如用户Model：
 *
 *  class User_model extends MY_Model
 *  {
 *      protected $_db_key = 'default';
 *      protected $_db_key_ro = 'default';
 *      protected $_table_name = 't_cron';
 *
 *      // 获取主键
 *      public function table_indexes()
 *      {
 *          return ['id', ];
 *      }
 *
 *      // 字段字典
 *      public function table_fields()
 *      {
 *          return [
 *              'id' => 'int',
 *              'username' => 'varchar',
 *              'nickname' => 'varchar',
 *              'gender' => 'char',
 *              'mobile' => 'varchar',
 *              'created_at' => 'datetime',
 *              'changed_at' => 'datetime',
 *              'is_removed' => 'tinyint',
 *          ];
 *      }
 *  }
 */
class MY_Model extends CI_Model implements ArrayAccess
{
    //use \Mylib\ORM\MY_Senior;

    public $result = null;

    //通用连接
    protected $_db_key = '';
    protected $_db_key_ro = '';
    protected $_db_conn = null;
    //表名，不含prefix
    protected $_table_name = '';
    protected $_table_indexes = false; //未初始化
    protected $_table_fields = [];
    //开启的功能
    protected $_mixin_switches = [];

    public function __construct()
    {
        $this->load->helper('env');
    }

    /**
     * 在Model对象上调用db（CI_Query_builder对象）的方法
     */
    public function __call($name, $args)
    {
        $lower_name = strtolower($name);
        if (method_exists($this, $name)) {
            throw new Exception('Method in a trait');
        }
        $db = $this->reconnect();
        if (in_array($lower_name, ['sum', 'min', 'max', 'avg'], true)) {
            $table = $this->table_name();
            $name = 'select_' . $lower_name;
            exec_method_array($db, $name, $args);
            $row = $db->get($table)->row_array();
            return reset($row);
        } else {
            $result = exec_method_array($db, $name, $args);
            return ($result === $db) ? $this : $result;
        }
    }

    /**
     * 加载其他的Model类
     * @param string $name Model类名
     * @return object/null
     */
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

    /**
     * 当前Model是否开启了某个高级功能Mixin
     * 例如：
     *   在拥有MY_Foreign的Model上调用with_foreign()后，
     *   就开启了外键关联查询功能。
     * @param string $name Mixin名，小写不带后缀，如cache
     * @return bool
     */
    public function is_open_mixin($name)
    {
        if (isset($this->_mixin_switches[$name])) {
            return $this->_mixin_switches[$name];
        } else {
            return false;
        }
    }

    /**
     * 确保数据库连接
     * @param  bool $force 强制重连
     * @param  bool $use_writer 强制使用主库
     * @return object/null
     */
    public function reconnect($force = false, $use_writer = false)
    {
        if ($force) {
            $this->_db_conn->close();
        }
        if (empty($this->_db_conn)) {
            $this->_db_conn = $this->load->database($this->_db_key, true);
            if (!empty($this->_db_key_ro) && $this->_db_key_ro != $this->_db_key) {
                //连接只读数据库，在simple_query()中实现连接的切换
                if ($db = $this->load->database($this->_db_key_ro, true)) {
                    $this->_db_conn->add_reader($db->conn_id, true);
                }
            }
        }
        if ($use_writer) { //读写分离下使用主库
            $this->_db_conn->switch_conn(true);
        }
        //确保连接正常
        $this->_db_conn->reconnect();
        $this->_db_conn->initialize();
        return $this->_db_conn;
    }

    public function last_conn()
    {
        return $this->_db_conn;
    }

    public function last_query()
    {
        if ($db = $this->last_conn()) {
            return $db->last_query();
        }
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

    public function base_table_name($another = false)
    {
        if (false !== $another) {
            $this->_table_name = $another;
        }
        return $this->_table_name;
    }

    /**
     * 获取当前完整表名，用于分表情况
     * 在MY_Monthly中覆盖了此方法
     * @return string 完整表名
     */
    public function table_name($another = false)
    {
        return $this->base_table_name($another);
    }

    /**
     * 获取主键，返回数组（可能是多个字段）
     * 推荐用Django标准，每张表（多对多关联的中间表除外）都有一个主键，名称为id
     * @return array
     */
    public function table_indexes($another = false)
    {
        if (is_array($another)) {
            $this->_table_indexes = $another;
        } else {
            $this->table_fields();
        }
        return $this->_table_indexes;
    }

    /**
     * 找出表的第一个主键字段
     * @return string
     */
    public function primary_key()
    {
        $indexes = $this->table_indexes();
        return $indexes ? reset($indexes) : '';
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

    /**
     *
     * @param  string  $column [description]
     * @param  bool $reset  [description]
     * @return int          [description]
     */
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

    public function all($limit = null, $offset = 0, $columns = '*')
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if ($columns && '*' !== $columns) {
            $db->select($columns);
        }
        $this->result = $db->get($table, $limit, $offset);
        return $this->fetch_result();
    }

    public function some($where, $limit = null, $columns = '*')
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if ($columns && '*' !== $columns) {
            $db->select($columns);
        }
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

    public function one($where = null, $type = 'array', $columns = '*')
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if ($columns && '*' !== $columns) {
            $db->select($columns);
        }
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

    /**
     * 插入一条记录
     * @param array $row 二维数组 key:字段名  value: 值
     * @param bool $is_replace 是否换用REPLACE INTO
     * @return mixed
     */
    public function insert_unsafe($row, $is_replace = false, $escape = null)
    {
        $row = to_array($row);
        $table = $this->table_name();
        $db = $this->reconnect();
        $method = $is_replace ? 'replace' : 'insert';
        if ($db->$method($table, $row, $escape)) {
            $result = $db->insert_id();
            return $result;
        }
    }

    public function insert($row, $is_replace = false, $escape = null)
    {
        return $this->insert_unsafe($row, $is_replace, $escape);
    }

    /**
     * 插入一条记录
     */
    public function insert_batch(array $rows = null, $escape = null, $batch_size = 100)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        return $db->insert_batch($table, $rows, $escape, $batch_size);
    }

    /**
     * 删除记录
     * @param array/string/null $where
     * @return bool
     */
    public function delete($where = '', $limit = null, $escape = null)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        if (!empty($where)) {
            $db->where($where, '', $escape);
        }
        $result = $db->delete($table, '', $limit);
        if ($this->is_open_mixin('cacheable')) {
            $this->cache_subject()->delete_cache($where);
        }
        return $result;
    }

    /**
     * 更新记录
     * @param array $set
     * @param array/string/null $where
     * @return bool
     */
    public function update_unsafe(array $set, $where = null, $limit = null, $escape = null)
    {
        $table = $this->table_name();
        $db = $this->reconnect();
        $db->set($set, '', $escape);
        if (!empty($where)) {
            $db->where($where, '', $escape);
        }
        $db->update($table, null, null, $limit);
        if ($this->is_open_mixin('cacheable')) {
            $this->cache_subject()->delete_cache($where);
        }
        return $db->affected_rows();
    }

    public function update(array $set, $where = null, $limit = null, $escape = null)
    {
        return $this->update_unsafe($set, $where, $limit, $escape);
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

    /**
     * 开启事务
     */
    public function trans_start($test_mode = false)
    {
        $db = $this->reconnect(false, true); //强制使用主库
        $hash = $db->get_conn_hash('conn_id', 8);
        log_message('DEBUG', sprintf('conn[%s] trans_start', $hash));
        $result = $db->trans_start($test_mode);
        return $result;
    }

    /**
     * 执行事务，失败时回滚并抛出异常
     */
    public function trans_complete($errmsg = 'Transaction is failure.')
    {
        $db = $this->reconnect(false, true); //强制使用主库
        $hash = $db->get_conn_hash('conn_id', 8);
        log_message('DEBUG', sprintf('conn[%s] trans_complete', $hash));
        $result = $db->trans_complete();
        if (false !== $errmsg && false === $db->trans_status()) {
            $error = $db->error() ?: ['code' => 1, 'message' => $errmsg];
            throw new Exception($error['message'], $error['code']);
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
