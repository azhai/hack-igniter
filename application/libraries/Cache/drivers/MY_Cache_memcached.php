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
 * Memcache缓存，使用libmemcached
 */
class MY_Cache_memcached extends CI_Cache_memcached
{
    /**
     * 读取或设置内部的memcached对象
     *
     * @param bool $another
     * @return object
     */
    public function instance($another = false)
    {
        if (false !== $another) {
            $this->_memcached = $another;
        }
        return $this->_memcached;
    }
}
