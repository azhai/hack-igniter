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

use Mylib\Observer\MY_Subject_cache;

/**
 * 扩展Model，优先从缓存中读取
 *
 * 临时使用，在加载model后
 *  $this->load->model('default/user_model');
 *  $this->user_model->add_cache('redis', 'admin');
 *
 * 或者永久使用，在model构造函数中
 *  public function __construct()
 *  {
 *      parent::__construct();
 *      $this->add_cache('redis', 'admin');
 *  }
 */
trait MY_Cacheable
{
    protected $_cache_subject = null;

    /**
     * @param string|object $cache 缓存对象或缓存参数
     * @param string        $class 缓存类名
     * @return mixed
     */
    public function add_cache($cache, $params = null)
    {
        if (! is_object($cache) && $params) {
            $cache = $this->load->cache($cache, $params);
        }
        if ($cache) {
            $this->_mixin_switches['cacheable'] = true;
            return $this->cache_subject()->attach($cache);
        }
    }

    public function cache_subject()
    {
        if (empty($this->_cache_subject)) {
            $this->_cache_subject = new MY_Subject_cache($this);
        }
        return $this->_cache_subject;
    }

    public function cache_fields()
    {
        if (method_exists($this, 'table_fields')) {
            return $this->table_fields();
        }
    }

    /**
     * @return array
     */
    public function states($data = null)
    {
        if (empty($data)) {
            $data = to_array($this);
        } elseif (!is_array($data)) {
            $data = to_array($data);
        }
        if ($fields = $this->cache_fields()) {
            $data = array_intersect_key($data, $fields);
        }
        return $data;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function condition($value = null)
    {
        $indexes = $this->table_indexes();
        if (is_null($value)) {
            $result = [];
            foreach ($indexes as $index) {
                $result[$index] = $this[$index];
            }
        } else {
            $result = array_combine($indexes, to_array($value));
        }
        return $result ? $result : [];
    }

    public function get_as($value, $type = '')
    {
        $where = $this->condition($value);
        if (empty($where)) {
            return;
        }
        $row = $this->cache_subject()->read_cache($where);
        if (empty($row)) {
            if ($fields = $this->cache_fields()) {
                $this->select(array_keys($fields));
            }
            $result = $this->one($where, $type);
            $data = $this->states($result);
            $this->cache_subject()->write_cache($where, $data);
        } elseif ('array' === $type) {
            $result = $row;
        } else {
            $result = empty($type) ? clone $this : new $type();
            foreach ($row as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function get_array($value)
    {
        return $this->get_as($value, 'array');
    }

    public function get_in_array($values, $key = '')
    {
        $result = [];
        $remains = [];
        //找出已缓存的
        foreach ($values as $value) {
            if (empty($key)) {
                $where = $this->condition($value);
            } else {
                $where = [$key => $value];
            }
            $row = $this->cache_subject()->read_cache($where);
            //保持$result和$values相同的次序
            if (empty($row)) {
                $remains[] = $value;
                $result[$value] = [];
            } else {
                $result[$value] = $row;
            }
        }
        //读数据库和缓存剩下的
        if (count($remains) > 0) {
            if (empty($key)) {
                $key = key($where);
            }
            if ($fields = $this->cache_fields()) {
                $this->select(array_keys($fields));
            }
            $rows = $this->parse_where([$key => $remains])->all();
            foreach ($rows as $row) {
                $value = $row[$key];
                $result[$value] = $row;
                $where = [$key => $value];
                $data = $this->states($row);
                $this->cache_subject()->write_cache($where, $data);
            }
        }
        return $result;
    }

    public function save($row = null, $key = null, $escape = null)
    {
        $row = $row ? to_array($row) : $this->status();
        if (is_string($key) && isset($row[$key])) {
            $where = [$key => $row[$key]];
        } elseif (is_array($key)) {
            $where = $key;
        } else {
            $where = $this->condition();
        }
        if ($where) { //先尝试更新
            $result = $this->update($row, $where, 1, $escape);
            if ($result) {
                return $result;
            }
        }
        return $this->insert($row, true, $escape);
    }
}
