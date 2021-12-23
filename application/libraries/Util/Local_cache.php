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

namespace Mylib\Util;

\defined('LOCAL_CACHE_APCU') || \define('LOCAL_CACHE_APCU', 'apcu');
\defined('LOCAL_CACHE_YAC') || \define('LOCAL_CACHE_YAC', 'yac');
\defined('LOCAL_CONFIG_NAME') || \define('LOCAL_CONFIG_NAME', '_globals_');
\defined('LOCAL_CONFIG_KEY') || \define('LOCAL_CONFIG_KEY', 'local_cache');
\defined('LOCAL_CONFIG_TTL') || \define('LOCAL_CONFIG_TTL', 60);

/**
 * APCU或YAC本地缓存.
 *
 * 配置文件： application/config/globals.json 内容： {"local_cache":"yac"}
 * 使用举例：
 * $local_cache = new \Mylib\Util\Local_cache();
 * $local_cache->retrieveData($cache_key, 300, function($cache_key) use ($redis) {
 *     return [$redis->hGetAll($cache_key), $redis->ttl($cache_key)];
 * });
 */
class Local_cache
{
    public $json_file;
    public $filter;
    protected $cache_type = '';
    protected $yac;

    public function __construct($filename = 'globals.json')
    {
        $this->json_file = sprintf('%s/config/%s', rtrim(APPPATH, '/'), $filename);
        if (! $this->prepare(LOCAL_CACHE_YAC)) {
            $this->flushAll(LOCAL_CACHE_YAC);
        }
        if (! $this->prepare(LOCAL_CACHE_APCU)) {
            $this->flushAll(LOCAL_CACHE_APCU);
        }
        if (empty($this->cache_type)) {
            $this->prepare('');
        }
    }

    /**
     * 单个缓存初始化.
     *
     * @param mixed $cache_type
     */
    public function prepare($cache_type)
    {
        if ($cache_type && ! $this->isSupport($cache_type, true)) {
            return false;
        }
        if ($cache_type) {
            $data = $this->readData(LOCAL_CONFIG_NAME, $cache_type);
        } else {
            $data = self::readGlobalConfigs($this->json_file);
        }

        if ($data && isset($data[LOCAL_CONFIG_KEY])) {
            $this->cache_type = strtolower($data[LOCAL_CONFIG_KEY]);
            if ($this->cache_type && empty($cache_type)) {
                $this->saveData(LOCAL_CONFIG_NAME, $data, LOCAL_CONFIG_TTL, false);
            }
        }

        return $cache_type === $this->cache_type;
    }

    /**
     * 读取全局配置文件.
     *
     * @param mixed $filename
     */
    public static function readGlobalConfigs($filename)
    {
        if (! ($content = @file_get_contents($filename))) {
            return null;
        }
        if (! ($result = @json_decode($content, true))) {
            return null;
        }

        return \is_array($result) ? $result : array();
    }

    /**
     * 是否支持的缓存类型.
     *
     * @param mixed $cache_type
     * @param mixed $create
     */
    public function isSupport($cache_type, $create = false)
    {
        switch (strtolower($cache_type)) {
            case LOCAL_CACHE_APCU:
                return \extension_loaded('apcu') && apcu_enabled();

            case LOCAL_CACHE_YAC:
                $result = \extension_loaded('yac') && class_exists('Yac');
                if ($result && $create) {
                    $this->yac = new Yac();
                }

                return $result;

            default:
                return true;
        }
    }

    /**
     * 清空全部缓存.
     *
     * @param mixed $cache_type
     */
    public function flushAll($cache_type)
    {
        switch (strtolower($cache_type)) {
            case LOCAL_CACHE_APCU:
                return $this->isSupport(LOCAL_CACHE_APCU) ? apcu_clear_cache() : true;

            case LOCAL_CACHE_YAC:
                return $this->yac ? $this->yac->flush() : true;

            default:
                return true;
        }
    }

    /**
     * 读取数据.
     *
     * @param mixed $key
     * @param mixed $ttl
     * @param mixed $cache_type
     */
    public function getConfig($key, $ttl = 0, $cache_type = '')
    {
        $data = $this->readData(LOCAL_CONFIG_NAME, $cache_type);
        if (empty($data) && $ttl > 0) {
            $data = self::readGlobalConfigs($this->json_file);
            $this->saveData(LOCAL_CONFIG_NAME, $data, $ttl, false);
        }

        return isset($data[$key]) ? $data[$key] : null;
    }

    /**
     * 读取数据.
     *
     * @param mixed $key
     * @param mixed $cache_type
     */
    public function readData($key, $cache_type = '')
    {
        $result = null;
        $success = false;
        if (empty($cache_type)) {
            $cache_type = $this->cache_type;
        }
        if (LOCAL_CACHE_YAC === $cache_type) {
            $result = $this->yac->get($key);
            $success = (false !== $result);
        } elseif (LOCAL_CACHE_APCU === $cache_type) {
            $result = apcu_fetch($key, $success);
        }

        return $success ? $result : null;
    }

    /**
     * 写入数据.
     *
     * @param mixed $key
     * @param mixed $data
     * @param mixed $ttl
     * @param mixed $use_filter
     */
    public function saveData($key, $data, $ttl = 0, $use_filter = true)
    {
        if ($filter = $this->filter) {
            if ($use_filter && ! $filter($data)) {
                return false;
            }
        }
        if (LOCAL_CACHE_YAC === $this->cache_type) {
            $result = $this->yac->set($key, $data, $ttl);
        } elseif (LOCAL_CACHE_APCU === $this->cache_type) {
            $result = apcu_store($key, $data, $ttl);
        }

        return $result;
    }

    /**
     * 读取数据.
     *
     * @param mixed $key
     * @param mixed $max_ttl
     */
    public function retrieveData($key, $max_ttl = 0, callable $read = null)
    {
        if ($data = $this->readData($key)) {
            return $data;
        }
        $ttl = 0;
        if ($read) {
            @list($data, $ttl) = $read($key);
        }
        if (empty($data) || $ttl <= -2) {
            return null;
        }
        if ($max_ttl > 0 && $ttl > $max_ttl) {
            $ttl = $max_ttl;
        }
        if ($ttl > 0) {
            $this->saveData($key, $data, $ttl, true);
        }

        return $data;
    }

    /**
     * 删除数据.
     *
     * @param mixed $key
     * @param mixed $cache_type
     */
    public function removeData($key, $cache_type = '')
    {
        if (empty($cache_type)) {
            $cache_type = $this->cache_type;
        }
        if (LOCAL_CACHE_YAC === $cache_type) {
            return $this->yac ? $this->yac->delete($key) : false;
        }
        if (LOCAL_CACHE_APCU === $cache_type) {
            return apcu_delete($key);
        }

        return false;
    }
}
