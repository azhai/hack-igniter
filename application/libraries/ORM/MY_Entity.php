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

defined('BASEPATH') or exit('No direct script access allowed');
require_once VENDPATH . 'php-handlersocket/src/HandlerSocket.php';

/**
 * 扩展Model，使用handlersocket快速读写
 */
trait MY_Entity
{
    //HandlerSocket连接
    protected $_hs_conn = null;
    protected $_hs_conn_ro = null;

    protected function _get_hs_config()
    {
        $configs = $this->load->config('handlersocket', true, true);
        if (isset($configs['handlersocket'][$this->_db_key])) {
            return $configs['handlersocket'][$this->_db_key];
        } else {
            return ['host' => '127.0.0.1', 'port' => 0, 'port_ro' => 0];
        }
    }

    public function hs_conn($another = false, $readonly = false)
    {
        $suffix = $readonly ? '_ro' : '';
        $prop = '_hs_conn' . $suffix;
        if (false !== $another) {
            $this->$prop = $another;
        } elseif (is_null($this->$prop)) {
            $conf = $this->_get_hs_config();
            $host = $conf['host'];
            $port = $conf['port' . $suffix];
            $hs = new \HandlerSocket($this->db_name(), $host, $port, $readonly);
            $fields = array_keys($this->table_fields());
            $hs->open($this->table_name(), $fields);
            $this->$prop = $hs;
        }
        return $this->$prop;
    }

    public function hs_all($limit = null, $offset = 0, array $orders = [])
    {
        $key = null;
        $op = '>=';
        if (count($orders) > 0) {
            list($key, $direct) = each($orders);
            $op = ('DESC' === strtoupper($direct)) ? '<=' : '>=';
        }
        $hs = $this->hs_conn(false, false);
        return $hs->all($key, $op, null, intval($limit), $offset);
    }

    public function hs_some($where)
    {
        assert(is_array($where));
        list($key, $value) = each($where);
        $hs = $this->hs_conn(false, false);
        return $hs->in($key, to_array($value));
    }

    public function hs_one($where = null, $type = '')
    {
        assert(is_array($where));
        assert('array' === $type);
        list($key, $value) = each($where);
        $hs = $this->hs_conn(false, false);
        return $hs->get($key, $value);
    }

    public function hs_insert($row, $replace = false, $escape = null)
    {
        assert(false === $replace);
        $row = to_array($row);
        $hs = $this->hs_conn(false, false);
        return $hs->insert($row);
    }

    public function hs_delete($where = '', $limit = null, $escape = null)
    {
        assert(is_array($where));
        $value = reset($where);
        $hs = $this->hs_conn(false, false);
        return $hs->delete($value);
    }

    public function hs_update(array $set, $where = null, $limit = null, $escape = null)
    {
        assert(is_array($where));
        list($key, $value) = each($where);
        $hs = $this->hs_conn(false, false);
        return $hs->update($set, $key, $value);
    }
}
