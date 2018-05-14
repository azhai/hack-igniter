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


class CI_DB extends CI_DB_query_builder
{
    public static $active_group = false;    //当前连接名
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

    public function set_timeout($seconds)
    {
        return $this->expired_timestamp = time() + $seconds;
    }

    public function initialize()
    {
        if ($is_pdo = $this->is_pdo_driver()) {
            $this->options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }
        if ($this->expired_timestamp > 0 && $this->expired_timestamp < time()) {
            $this->close(); //连接过期
        }
        $name = self::$active_group;
        if ($name && $is_exists = isset(self::$conn_registry[$name])) {
            $this->conn_id = self::$conn_registry[$name];
        }
        $result = parent::initialize();
        if ($result) {
            if ($is_pdo && $this->database) {
                $this->db_select($this->database);
            }
            if ($name && ! $is_exists) {
                self::$conn_registry[$name] = $this->conn_id;
                if (defined('DB_RECONNECT_TIMEOUT')) {
                    $this->set_timeout(constant('DB_RECONNECT_TIMEOUT'));
                }
            }
        }
        return $result;
    }

    public function get_conn_hash($tail = 0)
    {
        if (!$this->conn_id) {
            return '';
        }
        $result = spl_object_hash($this->conn_id);
        if ($tail > 0) {
            $result = substr($result, 0 - $tail);
        }
        return $result;
    }

    public function simple_query($sql)
    {
        if ($this->conn_reader) { //读写分离
            if ($this->is_write_type($sql)) {
                $this->conn_id = $this->conn_writer;
            } else {
                $this->conn_id = $this->conn_reader;
            }
        }
        $hash = $this->get_conn_hash(8);
        log_message('DEBUG', sprintf('conn[%s] SQL: %s;', $hash, $sql));
        return parent::simple_query($sql);
    }

    public function reset_count()
    {
        $this->_reset_run(array(
            'qb_select'     => array(),
            'qb_from'       => array(),
            'qb_join'       => array(),
            'qb_aliased_tables' => array(),
            'qb_no_escape'      => array(),
        ));
    }
}
