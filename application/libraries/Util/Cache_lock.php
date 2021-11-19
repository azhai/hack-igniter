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

/**
 * Redis缓存锁
 */
class Cache_lock
{
    public const CACHE_KEY_SPIN_MULTI = 'spin-locks';
    public const CACHE_KEY_TAIL_VALUE = 'value';
    public const CACHE_KEY_TAIL_EXPIRE = 'expire';
    protected $redis;

    /**
     * 构造函数，传递一个redis对象
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * 单个自旋锁，适用于较长时间.
     *
     * @param string $name 锁名
     * @param int    $ttl  新的时效
     *
     * @return bool 是否成功
     */
    public function spin_lock($name, $ttl = 3600)
    {
        if ($this->redis->setNx($name, time() + $ttl)) { //原先不存在
            $this->redis->expire($name, $ttl);

            return true;
        }

        return false;
    }

    /**
     * 一组自旋锁，适用于较短时间.
     *
     * @param string $name 锁名
     * @param int    $ttl  新的时效
     *
     * @return bool 是否成功
     */
    public function spin_add($name, $ttl = 10)
    {
        $value = $this->redis->zScore(self::CACHE_KEY_SPIN_MULTI, $name);
        if (empty($value) || $value < time()) { //原先不存在或已过期
            $this->redis->zAdd(self::CACHE_KEY_SPIN_MULTI, time() + $ttl, $name);

            return true;
        }

        return false;
    }

    /**
     * 最后若干个排队
     *
     * @param string $name  锁名
     * @param int    $times 重试次数
     * @param int    $ttl   自动解锁时间
     *
     * @return int -1:失败 0:成功（临界点） 1:成功
     */
    public function tail_lock($name, $times = 10, $ttl = 5)
    {
        $sleep_msec = rand(10, 200) * 1000;
        while (--$times >= 0) { // lock>=0 继续执行 -1执行并解锁 <-1等待锁
            $value = $this->redis->hIncrBy($name, self::CACHE_KEY_TAIL_VALUE, -1);
            if ($value >= 0) {
                return 1;
            }
            if (-1 === $value) {
                $this->redis->hSet($name, self::CACHE_KEY_TAIL_EXPIRE, time() + $ttl);

                return 0;
            }
            usleep($sleep_msec);
            if ($this->redis->hGet($name, self::CACHE_KEY_TAIL_EXPIRE) < time()) {
                $this->redis->hSet($name, self::CACHE_KEY_TAIL_VALUE, 0);  //释放锁
            }
        }

        return -1;
    }

    /**
     * 强制解锁或初始化倒数(队列长度-排队数量).
     *
     * @param string $name  锁名
     * @param int    $value 数值 0:解锁 1+:初始化
     * @param int    $ttl   缓存失效时间，默认31天
     *
     * @return bool 是否成功
     */
    public function tail_change($name, $value = 0, $ttl = 2678400)
    {
        $result = $this->redis->hSet($name, self::CACHE_KEY_TAIL_VALUE, $value);
        $this->redis->expire($name, $ttl);

        return $result;
    }
}
