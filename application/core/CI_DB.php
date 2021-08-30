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
require_once BASEPATH . 'database/DB_driver.php';
require_once BASEPATH . 'database/DB_query_builder.php';
require_once APPPATH . 'helpers/my_helper.php';
require_once APPPATH . 'helpers/fmt_helper.php';


class CI_DB extends CI_DB_query_builder
{
    public static $last_active_group = false;    //当前连接名
    public static $conn_registry = [];      //数据库连接注册表
    public $expired_timestamp = -1;         //连接失效重连时间
    public $conn_writer = null;
    public $conn_reader = null;

    public function is_pdo_driver()
    {
        if (class_exists('CI_DB_pdo_driver')) {
            return $this instanceof CI_DB_pdo_driver;
        }
    }

    public function is_in_trans()
    {
        return $this->_trans_depth > 0;
    }

    public function set_timeout($seconds)
    {
        return $this->expired_timestamp = time() + $seconds;
    }

    public function initialize()
    {
        if ($this->is_pdo_driver()) {
            $this->options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }
        if ($this->expired_timestamp > 0 && $this->expired_timestamp < time()) {
            $this->close(); //连接过期
            $this->reconnect();
        }
        $name = self::$last_active_group;
        if ($name && $is_exists = isset(self::$conn_registry[$name])) {
            $this->conn_id = self::$conn_registry[$name];
            self::$last_active_group = false; //用完及时清理
        }
        $result = parent::initialize();
        if ($result && $name && !$is_exists) {
            self::$conn_registry[$name] = $this->conn_id;
            if (defined('DB_RECONNECT_TIMEOUT')) {
                $this->set_timeout(constant('DB_RECONNECT_TIMEOUT'));
            }
        }
        return $result;
    }

    public function get_conn_hash($prop = 'conn_id', $offset = 0, $tail = -16)
    {
        if (!$this->$prop) {
            return '';
        }
        if (is_resource($this->$prop)) {
            if (function_exists('mysql_thread_id')) {
                $result = md5(mysql_thread_id($this->$prop));
            } elseif (function_exists('get_resource_id')) {
                $result = md5(get_resource_id($this->$prop));
            } else {
                $result = md5(var_export($this->$prop, true));
            }
        } else {
            $result = spl_object_hash($this->$prop);
        }
        if ($offset || $tail) {
            $result = substr($result, $offset, $tail);
        }
        return $result;
    }

    public function add_reader($conn_id, $save_to_writer = false)
    {
        if ($save_to_writer) {
            $this->conn_writer = $this->conn_id;
            $hash = $this->get_conn_hash('conn_writer', 8);
            log_message('DEBUG', sprintf('conn_writer[%s]', $hash));
        }
        $this->conn_reader = $conn_id;
        $hash = $this->get_conn_hash('conn_reader', 8);
        log_message('DEBUG', sprintf('conn_reader[%s]', $hash));
    }

    public function switch_conn($use_writer = true)
    {
        if ($this->conn_reader) { //读写分离
            if ($use_writer) {
                $this->conn_id = $this->conn_writer;
            } else {
                $this->conn_id = $this->conn_reader;
            }
        }
        return $this->conn_id;
    }

    public function simple_query($sql)
    {
        $this->switch_conn($this->is_in_trans() || $this->is_write_type($sql));
        $hash = $this->get_conn_hash('conn_id', 8);
        if (!starts_with($sql, 'SET SESSION')) {
            log_message('DEBUG', sprintf('conn[%s] SQL: %s;', $hash, wrap_lines($sql)));
        }
        return parent::simple_query($sql);
    }

    public function reset_count()
    {
        $this->_reset_run(array(
            'qb_select' => array(),
            'qb_from' => array(),
            'qb_join' => array(),
            'qb_aliased_tables' => array(),
            'qb_no_escape' => array(),
        ));
    }

    /**
     * Protect Identifiers
     */
    public function protect_identifiers($item, $prefix_single = FALSE, $protect_identifiers = NULL, $field_exists = TRUE)
    {
//        $protect_identifiers = false;
        return parent::protect_identifiers($item, $prefix_single, $protect_identifiers, $field_exists);
    }

    /**
     * Returns an array of table names
     *
     * @param	string	$constrain_by_prefix = FALSE
     * @return	array
     */
    public function list_tables($constrain_by_prefix = FALSE)
    {
        $tables = parent::list_tables($constrain_by_prefix);
        if ($constrain_by_prefix === false || $constrain_by_prefix === '') {
            return $tables;
        }
        //当有表名缓存时，原CI代码不会检查前缀
        $prefix = (string)$constrain_by_prefix;
        return array_filter($tables, function($table) use ($prefix) {
            return starts_with($table, $prefix);
        });
    }

    /**
     * Compile GROUP BY
     */
    protected function _compile_group_by()
    {
        if ($result = parent::_compile_group_by()) {
            $this->qb_groupby = array_unique($this->qb_groupby);
            $result = "\nGROUP BY " . implode(', ', $this->qb_groupby);
        }
        return $result;
    }

    /**
     * Compile ORDER BY
     */
    protected function _compile_order_by()
    {
        if ($result = parent::_compile_order_by()) {
            $this->qb_orderby = array_unique($this->qb_orderby);
            $result = "\nORDER BY " . implode(', ', $this->qb_orderby);
        }
        return $result;
    }
}
