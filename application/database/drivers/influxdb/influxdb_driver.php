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

use InfluxDB\Client;
use InfluxDB\Database\RetentionPolicy;
use InfluxDB\Exception as InfluxException;


/**
 * InfluxDB Database Adapter Class
 *
 * @link        https://github.com/influxdata/influxdb-php
 */
class CI_DB_influxdb_driver extends CI_DB
{
    /**
     * Database driver
     *
     * @var    string
     */
    public $dbdriver = 'influxdb';

    /**
     * Compression flag
     *
     * @var    bool
     */
    public $compress = false;

    /**
     * DELETE hack flag
     *
     * Whether to use the MySQL "delete hack" which allows the number
     * of affected rows to be shown. Uses a preg_replace when enabled,
     * adding a bit more processing to all queries.
     *
     * @var    bool
     */
    public $delete_hack = false;

    /**
     * Strict ON flag
     *
     * Whether we're running in strict SQL mode.
     *
     * @var    bool
     */
    public $stricton;

    /**
     * The constructor args of class RetentionPolicy
     *
     * @var array/null
     */
    public $default_policy;

    // --------------------------------------------------------------------

    /**
     * Identifier escape character
     *
     * @var    string
     */
    protected $_escape_char = '"';

    /**
     * InfluxDB Exception
     *
     * @var InfluxException
     */
    protected $_last_error;

    // --------------------------------------------------------------------

    public function __call($name, $args)
    {
        return exec_method_array($this->conn_id, $name, $args);
    }

    // --------------------------------------------------------------------

    public function initialize()
    {
        $result = parent::initialize();
        if (!$this->conn_id->exists()) {
            $policy = $this->newRetentionPolicy($this->default_policy);
            $this->conn_id->create($policy);
        }
        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Database connection
     *
     * @param bool $persistent
     * @return    object
     */
    public function db_connect($persistent = false)
    {
        // Do we have a socket path?
        if (!empty($this->dsn) && strpos($this->dsn, 'influxdb://') !== false) {
            try {
                $this->conn_id = Client::fromDSN($this->dsn);
            } catch (InfluxException $err) {
                $this->_last_error = $err;
            }
        } else {
            $client = new Client($this->hostname, $this->port);
            $this->conn_id = $client->selectDB($this->database);
        }

        return $this->conn_id;
    }

    // --------------------------------------------------------------------

    /**
     * Reconnect
     *
     * Keep / reestablish the db connection if no queries have been
     * sent for a length of time exceeding the server's idle timeout
     *
     * @return void
     */
    public function reconnect()
    {
        if (empty($this->conn_id)) {
            $this->db_connect();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Select the database
     *
     * @param string $database
     * @return    bool
     */
    public function db_select($database = '')
    {
        if ($database === '') {
            $database = $this->database;
        }

        $client = $this->conn_id->getClient();
        if ($this->conn_id = $client->selectDB($database)) {
            $this->database = $database;
            $this->data_cache = [];
            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Set client character set
     *
     * @param string $charset
     * @return    bool
     */
    protected function _db_set_charset($charset)
    {
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Database version number
     *
     * @return    string
     */
    public function version()
    {
        if (isset($this->data_cache['version'])) {
            return $this->data_cache['version'];
        }

        return $this->data_cache['version'] = '';
    }

    // --------------------------------------------------------------------

    /**
     * Execute the query
     *
     * @param string $sql an SQL query
     * @return    mixed
     */
    protected function _execute($sql)
    {
        try {
            $result = $this->conn_id->query($this->_prep_query($sql));
            return $result;
        } catch (InfluxException $err) {
            $this->_last_error = $err;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Prep the query
     *
     * If needed, each database adapter can prep the query string
     *
     * @param string $sql an SQL query
     * @return    string
     */
    protected function _prep_query($sql)
    {
        // influxdb_affected_rows() returns 0 for "DELETE FROM TABLE" queries. This hack
        // modifies the query so that it a proper number of affected rows is returned.
        if ($this->delete_hack === true && preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $sql)) {
            return trim($sql) . ' WHERE 1=1';
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Begin Transaction
     *
     * @return    bool
     */
    protected function _trans_begin()
    {
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Commit Transaction
     *
     * @return    bool
     */
    protected function _trans_commit()
    {
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Rollback Transaction
     *
     * @return    bool
     */
    protected function _trans_rollback()
    {
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Platform-dependent string escape
     *
     * @param string
     * @return    string
     */
    protected function _escape_str($str)
    {
        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Affected Rows
     *
     * @return    int
     */
    public function affected_rows()
    {
        return 0;
    }

    // --------------------------------------------------------------------

    /**
     * Insert ID
     *
     * @return    int
     */
    public function insert_id()
    {
        return 0;
    }

    // --------------------------------------------------------------------

    /**
     * List table query
     *
     * Generates a platform-specific query string so that the table names can be fetched
     *
     * @param bool $prefix_limit
     * @return    string
     */
    protected function _list_tables($prefix_limit = false)
    {
        $sql = sprintf('SHOW MEASUREMENTS ON "%s"', $this->database);
        if ($prefix_limit && is_string($prefix_limit)) {
            $sql .= sprintf(' WITH MEASUREMENT =~ /%s.*/', $prefix_limit);
        }
        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch Field Names
     *
     * @param string $table Table name
     * @return    array
     */
    public function list_fields($table)
    {
        $sql = $this->_field_data($table);
        return $this->query($sql)->list_fields();
    }

    // --------------------------------------------------------------------

    /**
     * Returns an object with field data
     *
     * @param string $table
     * @return    string
     */
    public function _field_data($table)
    {
        return sprintf('SELECT * FROM %s LIMIT 1', $table);
    }

    // --------------------------------------------------------------------

    /**
     * Error
     *
     * Returns an array containing code and message of the last
     * database error that has occurred.
     *
     * @return    array
     */
    public function error()
    {
        if (!empty($this->_last_error)) {
            return [
                'code' => $this->_last_error->getCode(),
                'message' => $this->_last_error->getMessage(),
            ];
        }

        return ['code' => 0, 'message' => ''];
    }

    // --------------------------------------------------------------------

    /**
     * Close DB Connection
     *
     * @return    void
     */
    protected function _close()
    {
        $this->conn_id = null;
    }

    /**
     * Create a instance of RetentionPolicy
     *
     * @return object
     */
    public function newRetentionPolicy($args = null)
    {
        $args = is_array($args) ? $args : func_get_args();
        if (count($args) > 0 && !empty($args[0])) {
            $class = new ReflectionClass('\\InfluxDB\\Database\\RetentionPolicy');
            return $class->newInstanceArgs($args);
        }
    }

    /**
     * Get the RetentionPolicy array
     *
     * @param string $name
     *
     * @return array/null
     */
    public function getRetentionPolicy($name = 'autogen')
    {
        $policies = (array)$this->conn_id->listRetentionPolicies();
        foreach ($policies as $policy) {
            if ($policy['name'] === $name) {
                return $policy;
            }
        }
    }

    /**
     * @param RetentionPolicy $policy
     */
    public function ensureRetentionPolicy(RetentionPolicy $policy)
    {
        $old_policy = $this->getRetentionPolicy($policy->name);
        if (empty($old_policy) || $old_policy['default'] !== $policy->default
            || $old_policy['duration'] !== $policy->duration
            || $old_policy['replicaN'] !== $policy->replication) {
            $this->conn_id->alterRetentionPolicy($policy);
        }
    }

    /**
     * @param string $name
     */
    public function dropRetentionPolicy($name = 'autogen')
    {
        $this->conn_id->query(sprintf('DROP RETENTION POLICY "%s" ON "%s"', $name, $this->database));
    }

    /**
     * @param string $table_name
     */
    public function dropTable($table_name)
    {
        $this->conn_id->query(sprintf('DROP MEASUREMENT "%s"', $table_name));
    }

    /**
     * @param string $shard_id
     */
    public function dropShard($shard_id)
    {
        $this->conn_id->query(sprintf('DROP SHARD "%s"', $shard_id));
    }

    /**
     * @param string $table_name
     */
    public function dropSeries($table_name = '', $is_delete = false, $time = '')
    {
        if (!empty($time)) {
            $this->where('time <', $time);
            $sql = 'DELETE';
        } else {
            $sql = $is_delete ? 'DELETE' : 'DROP SERIES';
        }
        if (!empty($table_name)) {
            $sql .= sprintf(' FROM "%s"', $table_name);
        }
        if ($where = $this->_compile_wh('qb_where')) {
            $sql .= ' WHERE ' . $where;
        }
        $this->_reset_write();
        $this->conn_id->query($sql);
    }
}
