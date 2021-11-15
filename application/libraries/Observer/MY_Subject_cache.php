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

namespace Mylib\Observer;

/**
 * 数据生产者的容器
 */
class MY_Subject_cache extends MY_Subject
{
    protected $_producer = null;       //真实的数据生产者，通常是Model对象
    protected $_cache_type = 'json';    //数据类型
    protected $_cache_timeout = 3600;   //缓存时间

    public function __construct($producer)
    {
        $this->producer($producer);
        $this->add_signal('read_cache', self::TYPE_NOTIFY_ONCE);
        $this->add_signal('write_cache', self::TYPE_NOTIFY_ALL);
        $this->add_signal('delete_cache', self::TYPE_NOTIFY_ALL);
        $this->add_signal('incr_cache', self::TYPE_NOTIFY_ALL);
        $this->add_signal('decr_cache', self::TYPE_NOTIFY_ALL);
    }

    public function producer($another = false)
    {
        if (false !== $another) {
            $this->_producer = $another;
        }
        return $this->_producer;
    }

    /**
     * @return int
     */
    public function cache_timeout($another = false)
    {
        if (is_numeric($another)) {
            $this->_cache_timeout = (int) $another;
        } elseif (method_exists($this->_producer, 'cache_timeout')) {
            $this->_cache_timeout = $this->_producer->cache_timeout();
        }
        return $this->_cache_timeout;
    }

    /**
     * @return string
     */
    public function cache_type($another = false)
    {
        if (\is_string($another)) {
            $this->_cache_type = $another;
        } elseif (method_exists($this->_producer, 'cache_type')) {
            $this->_cache_type = $this->_producer->cache_type();
        }
        return $this->_cache_type;
    }

    /**
     * @return string
     */
    public function cache_key($condition)
    {
        if (method_exists($this->_producer, 'cache_key')) {
            return $this->_producer->cache_key($condition);
        } else {
            $class = \get_class($this->_producer);
            $class = strtolower($class);
            if (ends_with($class, '_model')) {
                $class = substr($class, 0, -6); //去除_model
            }
            if (\is_array($condition)) {
                $args = array_values($condition);
            } else {
                $args = to_array($condition);
            }
            array_unshift($args, $class);
            return implode(':', $args);
        }
    }

    /**
     * @return array
     */
    public function states()
    {
        return $this->_producer->states();
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function condition($value = null)
    {
        return $this->_producer->condition($value);
    }
}
