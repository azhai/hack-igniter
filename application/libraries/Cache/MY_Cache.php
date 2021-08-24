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

/**
 * 缓存观察者，实现了观察者模式
 */
class MY_Cache extends CI_Cache implements SplObserver
{
    const CACHE_NONE = '###CACHE_NONE###'; //空数据占位符

    /**
     * @param SplSubject $subject
     * @return mixed
     */
    public function update(SplSubject $subject)
    {
        //检查参数
        if (func_num_args() < 3) {
            throw new BadMethodCallException();
        }
        $args = func_get_arg(2);
        if (!is_array($args)) {
            throw new InvalidArgumentException();
        }
        //执行更新
        if ($method = func_get_arg(1)) {
            array_unshift($args, $subject);
            return exec_method_array($this, $method, $args);
        }
    }

    /**
     * 检查是否占位符
     *
     * @param $key 缓存键
     * @return null|bool 是空值时返回true
     */
    public function is_none($key)
    {
        $driver = $this->{$this->_adapter};
        if (method_exists($driver, __FUNCTION__)) {
            return $driver->is_none($key);
        } else {
            return self::CACHE_NONE === $driver->get($key);
        }
    }

    /**
     * 保存占位符，防止缓存穿透
     *
     * @param     $key 缓存键
     * @param int $ttl 失效时间，单位：秒
     * @return bool 成功返回true
     */
    public function save_none($key, $ttl = 60)
    {
        $driver = $this->{$this->_adapter};
        if (method_exists($driver, __FUNCTION__)) {
            return $driver->save_none($key, $ttl);
        } else {
            return $driver->save($key, self::CACHE_NONE, $ttl, true);
        }
    }

    /**
     * 读取缓存值
     *
     * @param SplSubject $subject
     * @param mixed $conditon
     * @return mixed
     */
    public function read_cache(SplSubject $subject, $condition)
    {
        $type = $subject->cache_type();
        $key = $subject->cache_key($condition);
        if (empty($type)) {
            return $this->get($key);
        } elseif ('json' !== $type && $this->is_none($key)) {
            return [];
        } else {
            $driver = $this->{$this->_adapter};
            $method = 'get_' . $type;
            return $driver->$method($key);
        }
    }

    /**
     * 写缓存，类型和时效由$subject确定
     *
     * @param SplSubject $subject
     * @param mixed $conditon
     * @param mixed $states
     * @return mixed
     */
    public function write_cache(SplSubject $subject, $condition, $states)
    {
        $type = $subject->cache_type();
        $key = $subject->cache_key($condition);
        $ttl = $subject->cache_timeout();
        if (empty($type)) {
            return $this->save($key, $states, $ttl, true);
        } elseif ('json' !== $type && empty($states)) {
            $this->delete($key);
            return $this->save_none($key, $ttl);
        } else {
            $this->delete($key);
            $driver = $this->{$this->_adapter};
            $method = 'put_' . $type;
            return $driver->$method($key, $states, $ttl);
        }
    }

    /**
     * 删除缓存
     *
     * @param SplSubject $subject
     * @param mixed $conditon
     * @return mixed
     */
    public function delete_cache(SplSubject $subject, $condition)
    {
        $key = $subject->cache_key($condition);
        if (!is_array($key)) {
            return $this->delete($key);
        }
        foreach ($key as $k) {
            $result = $this->delete($k);
        }
        return $result;
    }

    /**
     * 缓存数据加上一个步进值，不存在当作0处理
     *
     * @param SplSubject $subject
     * @param mixed $conditon
     * @param int|float $offset
     * @return mixed
     */
    public function incr_cache(SplSubject $subject, $condition, $offset = 1)
    {
        $key = $subject->cache_key($condition);
        if (!is_array($key)) {
            return $this->increment($key, $offset);
        }
        foreach ($key as $k) {
            $result = $this->increment($k, $offset);
        }
        return $result;
    }

    /**
     * 缓存数据减去一个步进值
     *
     * @param SplSubject $subject
     * @param mixed $conditon
     * @param int|float $offset
     * @return mixed
     */
    public function decr_cache(SplSubject $subject, $condition, $offset = 1)
    {
        $key = $subject->cache_key($condition);
        if (!is_array($key)) {
            return $this->decrement($key, $offset);
        }
        foreach ($key as $k) {
            $result = $this->decrement($k, $offset);
        }
        return $result;
    }
}
