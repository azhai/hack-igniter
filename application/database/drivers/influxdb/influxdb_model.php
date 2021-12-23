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


require_once VENDPATH . 'autoload.php';
//$loader = load_class('Loader', 'core');
//$loader->name_space('InfluxDB', VENDPATH . 'influxdb/influxdb-php/src');

use InfluxDB\Database;
use InfluxDB\Exception as InfluxException;
use InfluxDB\Point;


/**
 * NotSupportedException represents an exception caused by accessing features that are not supported.
 */
class NotSupportedInfluxException extends InfluxException
{
    protected $method = '';

    public function __construct($method = '', $code = 0, $previous = null)
    {
        $named = ($this->method = $method) ? ' named ' . $this->method : '';
        $message = 'The method' . $named . ' is not supported in InfluxDB !';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Not Supported';
    }
}

/**
 * InfluxDB Model Class
 *
 * @link        https://github.com/influxdata/influxdb-php
 */
trait CI_DB_influxdb_model
{
    public function get_early_time()
    {
        return 0;
    }

    /**
     * 过滤掉无值（null或空字符串）的字段
     */
    public function get_validate()
    {
        return function ($v) {
            if (is_string($v)) {
                $v = strtolower(trim($v));
                return $v !== '' && $v !== 'null';
            } else {
                return !is_null($v);
            }
        };
    }

    public function build_data_tags(array $row)
    {
        if (method_exists($this, 'table_tags') && $tags = $this->table_tags()) {
            return array_intersect_key($row, $tags);
        }
        return [];
    }

    public function set_to_point($table, array $row, $time_field = '')
    {
        if ($time_field && isset($row[$time_field])) {
            $time = intval($row[$time_field]);
            unset($row[$time_field]);
        } else {
            $time = time();
        }
        if ($time < $this->get_early_time()) {
            return;
        }

        $row = array_filter($row, $this->get_validate());
        if ($tags = $this->build_data_tags($row)) {
            $fields = array_diff_key($row, $tags);
        } else {
            $fields = $row;
        }
        return new Point($table, null, $tags, $fields, $time);
    }

    public function delete($where = '', $limit = null, $escape = null)
    {
        $table = $this->table_name();
        $db = $this->ensure_conn();
        if (!empty($where)) {
            $this->parse_where($where, $escape);
        }
        return $db->delete($table, '', $limit);
    }

    public function insert($row, $is_replace = false, $escape = null)
    {
        if (empty($row) || !is_array($row)) {
            return;
        }
        $table = $this->table_name();
        $db = $this->ensure_conn();
        $time_field = $this->get_time_field();
        if ($point = $this->set_to_point($table, $row, $time_field)) {
            return $db->writePoints([$point, ], Database::PRECISION_SECONDS);
        }
    }

    /**
     * 插入一条记录
     */
    public function insert_batch(array $rows = null, $escape = null, $batch_size = 100)
    {
        $table = $this->table_name();
        $db = $this->ensure_conn();
        $time_field = $this->get_time_field();

        // Batch this baby
        $affected_rows = 0;
        foreach (array_chunk($rows, $batch_size) as $chunks) {
            $points = [];
            foreach ($chunks as $row) {
                if ($point = $this->set_to_point($table, $row, $time_field)) {
                    $points[] = $point;
                }
            }
            if ($count = count($points)) {
                $db->writePoints($points, Database::PRECISION_SECONDS);
                $affected_rows += $count;
            }
        }
        return $affected_rows;
    }

    /**
     * Replace
     *
     * Compiles an replace into string and runs the query
     *
     * @param string    the table to replace data into
     * @param array    an associative array of insert values
     * @return    bool    TRUE on success, FALSE on failure
     * @throws NotSupportedInfluxException
     */
    public function replace($row, $is_replace = false, $escape = null)
    {
        throw new NotSupportedInfluxException('replace');
    }

    /**
     * UPDATE
     *
     * Compiles an update string and runs the query.
     *
     * @param string $table
     * @param array $set An associative array of update values
     * @param mixed $where
     * @param int $limit
     * @return    bool    TRUE on success, FALSE on failure
     * @throws NotSupportedInfluxException
     */
    public function update(array $set, $where = null, $limit = null, $escape = null)
    {
        throw new NotSupportedInfluxException('update');
    }

    /**
     * 行数统计，使用COUNT(id)
     *
     * @param string $column 字段，必须是field而不是tag
     * @param bool $reset 执行后是否不保留where条件
     * @return int 行数
     */
    public function count($column = 'id', $reset = true)
    {
        $table = $this->table_name();
        $db = $this->ensure_conn();
        $db->select(sprintf('COUNT(%s)', $column));
        $sql = $this->get_compiled_select($table, $reset);
        $result = $db->query($sql);
        $count = 0;
        if ($result && $row = $result->row_array()) {
            $count = isset($row['count']) ? (int)$row['count'] : 0;
        }
        $db->reset_count();
        return $count;
    }

    /**
     * 限制时间
     *
     * @param string $time_desc 时间描述
     * @return $this
     */
    public function time_after($time_desc)
    {
        $this->where('time >= ' . $time_desc);
        return $this;
    }

    /**
     * 限制时间为从现在往前多少时间
     *
     * @param string $time_val 时间描述
     * @return $this
     */
    public function time_since($time_val = '24h')
    {
        return $this->time_after('now() - ' . $time_val);
    }
}
