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

namespace Mylib\Util;

/**
 * 间隔一段时间或事件触发一次
 * Author: 阿债 https://azhai.surge.sh
 */
class Timer
{
    protected $queue_ref;         // 队列引用
    protected $last_hit_time = 0; // 最后命中时间
    protected $startup_time = 0;         // 启动时间
    protected $startup_time_msec = 0.0;  // 启动时间
    public $interval_secs = 0;    // 最大间隔时间
    public $random_once = 0;      // 随机多少次触发一次
    public $queue_max_size = 0;   // 最大队列长度

    public function __construct($once_times = 0, $gap_secs = 0)
    {
        $this->random_once = (int) $once_times;
        $this->setIntervalSecs((int) $gap_secs);
        $this->startup_time_msec = microtime(true);
        $this->startup_time = round($this->startup_time_msec);
    }

    /**
     * 随机N次命中一次
     */
    public static function randomTimes($base = 7)
    {
        if ($base <= 0) {
            return false;
        } elseif ($base <= 1) {
            return true;
        }
        return mt_rand() % (int) $base === 0;
    }

    /**
     * 经过了多少秒
     */
    public function elapse($get_as_float = false)
    {
        if ($get_as_float) {
            return microtime(true) - $this->startup_time_msec;
        } else {
            return time() - $this->startup_time;
        }
    }

    /**
     * 是否满足一个命中条件
     */
    public function isHitting()
    {
        $result = false;
        if ($this->interval_secs > 0) {
            $result = (time() >= $this->last_hit_time + $this->interval_secs);
        }
        if (empty($result) && $this->random_once > 0) {
            $result = self::randomTimes($this->random_once);
        }
        if (empty($result) && $this->queue_max_size > 0) {
            $result = (\count($this->queue_ref) >= $this->queue_max_size);
        }
        if ($result && $this->last_hit_time > 0) { // 更新最后命中时间
            $this->last_hit_time = time();
        }
        return $result;
    }

    /**
     * 设置间隔时间
     */
    public function setIntervalSecs($secs = 0)
    {
        $this->interval_secs = $secs;
        $this->last_hit_time = $secs > 0 ? time() : 0;
    }

    /**
     * 设置被观察的队列
     */
    public function setQueueAndSize(array &$queue_ref, $max_size = 20)
    {
        $this->queue_ref = &$queue_ref;
        $this->queue_max_size = $max_size;
    }
}
