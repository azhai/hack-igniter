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

namespace Mylib\Mixin;

\defined('REDIS_DEFAULT_POOL_SIZE') || \define('REDIS_DEFAULT_POOL_SIZE', 1); //默认连接池大小

/**
 * 缓存连接池.
 */
trait Cache_mixin
{
    /**
     * 加载Redis缓存.
     *
     * @param string/array $params 连接配置或配置名称
     * @param string/null $object_name 对象名称，为空将会得到类似 user_redis_cache 字符串
     * @param false $force 强制重载
     */
    public function load_redis_cache($params = 'default', $object_name = null, $force = false)
    {
//        $loader = load_class('Loader', 'core');
        $loader = get_instance()->load;
        if (empty($object_name) && \is_string($params)) {
            $object_name = $params.'_redis';
        }

        return $loader->cache('redis', $params, $object_name, $force);
    }

    /**
     * 使用Redis连接池.
     *
     * @param string $params    配置名称
     * @param int    $pool_size 池子大小 -1:每次都重连 0:使用全局设置 1:单例 2+:最大连接数
     */
    public function load_redis_pool($params = 'default', $pool_size = 0)
    {
        $object_name = $params.'_redis';
        if (0 === $pool_size) {
            $pool_size = \constant('REDIS_DEFAULT_POOL_SIZE'); //全局设置
        }
        if ($pool_size >= 2) { //使用连接池
            $object_name .= sprintf('%03d', rand(1, $pool_size));
        }
        $force = ($pool_size < 0); //每次都重连
        $object = $this->load_redis_cache($params, $object_name, $force);

        return $object ? $object->redis->instance() : null;
    }
}
