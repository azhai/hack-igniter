<?php
/**
 * hack-igniter.
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @author  Ryan Liu (azhai)
 *
 * @see    http://azhai.surge.sh/
 *
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

namespace Mylib\ORM;

use Mylib\Observer\MY_Subject_cache;

/**
 * 扩展Model，优先从缓存中读取.
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
    protected $_cache_subject;

    /**
     * @param object|string $cache  缓存对象或缓存参数
     * @param string        $class  缓存类名
     * @param null|mixed    $params
     *
     * @return mixed
     */
    public function add_cache($cache, $params = null)
    {
        if (! \is_object($cache) && $params) {
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
     * @param null|mixed $data
     *
     * @return array
     */
    public function states($data = null)
    {
        if (empty($data)) {
            $data = to_array($this);
        } elseif (! \is_array($data)) {
            $data = to_array($data);
        }
        if ($fields = $this->cache_fields()) {
            $data = array_intersect_key($data, $fields);
        }

        return $data;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function condition($value = null)
    {
        $indexes = $this->table_indexes();
        if (null === $value) {
//            require_once APPPATH . 'helpers/fmt_helper';
//            $result = array_part($this, $indexes);
            $result = array();
            foreach ($indexes as $index) {
                $result[$index] = $this[$index];
            }
        } else {
            $result = array_combine($indexes, to_array($value));
        }

        return $result ? $result : array();
    }
}
