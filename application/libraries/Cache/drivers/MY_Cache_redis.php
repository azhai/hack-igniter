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
 * Redis缓存
 */
class MY_Cache_redis extends CI_Cache_redis
{
    /**
     * 其他连接参数
     *
     * @param array $config
     */
    public function set_options(array & $config)
    {
        if (isset($config['database']) && $config['database']) {
            $db_index = intval($config['database']);
            $this->_redis->select($db_index);
        }
    }

    /**
     * 读取或设置内部的redis对象
     *
     * @param bool $another
     * @return object
     */
    public function instance($another = false)
    {
        if (false !== $another) {
            $this->_redis = $another;
        }
        return $this->_redis;
    }

    /**
     * 检查锁是否已存在，不存在时加锁一定时间
     *
     * @param     $cache_key 锁名
     * @param int $ttl       新的时效
     * @return bool 是否成功
     */
    public function add_lock($cache_key, $ttl = 3600)
    {
        $redis = $this->instance();
        $old_ttl = intval($redis->ttl($cache_key));
        if (-2 === $old_ttl) { //不存在
            $redis->set($cache_key, time() + $ttl, $ttl);
            return true;
        } else {
            return false;
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
        $redis = $this->instance();
        if (Redis::REDIS_STRING === $redis->type($key)) {
            return MY_Cache::CACHE_NONE === $redis->get($key);
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
        $redis = $this->instance();
        return $redis->set($key, MY_Cache::CACHE_NONE, $ttl);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_json($id)
    {
        $json = $this->instance()->get($id);
        return json_decode($json, true);
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $ttl
     * @return mixed
     */
    public function put_json($id, $data, $ttl = 60)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this->instance()->set($id, $json, $ttl);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_hash($id)
    {
        return $this->instance()->hGetAll($id);
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $ttl
     * @return mixed
     */
    public function put_hash($id, $data, $ttl = 60)
    {
        $redis = $this->instance();
        $result = $redis->hmSet($id, $data);
        $redis->expire($id, $ttl);
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_zset($id)
    {
        return $this->instance()->zRangeByScore(
            $id,
            '-inf',
            '+inf',
            ['withscores' => true]
        );
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $ttl
     * @return mixed
     */
    public function put_zset($id, $data, $ttl = 60)
    {
        $redis = $this->instance();
        foreach ($data as $key => $score) {
            $result = $redis->zAdd($id, $score, $key);
        }
        $redis->expire($id, $ttl);
        return $result;
    }
}
