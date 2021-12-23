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

if (! function_exists('get_zadd_args')) {
    $loader = load_class('Loader', 'core');
    $loader->helper('cache');
}

/**
 * 分段缓存最近数据.
 */
trait MY_Cache_recent
{
    public static $empty_set_keys = 'empty_set_recent_keys'; //空集合

    /**
     * 最大缓存时长
     */
    public function get_recent_max_ttl()
    {
        return 86400 * 92; //一个季度
    }

    /**
     * 时间字段.
     */
    public function get_recent_time_field()
    {
        return 'created_at';
    }

    /**
     * 缓存前缀
     */
    public function get_recent_prefix()
    {
        return '';
    }

    /**
     * 其他前缀.
     */
    public function get_recent_extra_hash(array $extra_where)
    {
        return '_' . base64_encode(http_build_query($extra_where));
    }

    /**
     * 其他条件.
     */
    public function get_recent_extra_where()
    {
        return array();
    }

    /**
     * 时间段内有活动的用户，先从缓存中读取.
     *
     * @param mixed      $redis
     * @param mixed      $start
     * @param null|mixed $stop
     * @param mixed      $save_key
     */
    public function load_users_range($redis, $start, $stop = null, $save_key = '')
    {
        if ($save_key && $redis) { //先检查缓存
            if ($redis->sIsMember(self::$empty_set_keys, $save_key)) {
                return array();
            }
            $options = array('withscores' => true);
            if ($user_data = $redis->zRevRangeByScore($save_key, '+inf', 0, $options)) {
                return $user_data;
            }
        }
        $time_field = $this->get_recent_time_field();
        $stop = empty($stop) ? time() : (int) $stop;
        $where = array($time_field.' >=' => $start, $time_field.' <=' => $stop);
        $where = array_replace($this->get_recent_extra_where(), $where); //次序不可颠倒
        $user_data = $this->get_users_where($where, $start, $stop);
        if ($save_key && $redis) { //保存到缓存
            if (empty($user_data)) {
                $redis->sAdd(self::$empty_set_keys, $save_key);

                return array();
            }
            $chunk_data = array_chunk($user_data, 10, true);
            foreach ($chunk_data as $chunk) {
                $args = get_zadd_args($save_key, array(), $chunk);
                exec_method_array($redis, 'zAdd', $args);
            }
            if (ends_with($save_key, '_day')) {
                $ttl = $this->get_recent_max_ttl();
            } else {
                $ttl = strtotime('midnight') + 86400 - time(); //当天
            }
            $redis->expire($save_key, $ttl);
        }

        return $user_data;
    }

    /**
     * 时间段内有活动的人数.
     *
     * @param mixed      $redis
     * @param mixed      $where
     * @param null|mixed $stop
     */
    public function count_users($redis, $where, $stop = null)
    {
        $time_field = $this->get_recent_time_field();
        $today = strtotime('midnight');
        $start = isset($where[$time_field.' >=']) ? $where[$time_field.' >='] : $today;
        $stop = isset($where[$time_field.' <=']) ? $where[$time_field.' <='] : $stop;
        $stop = (empty($stop) || $stop > time()) ? time() : (int) $stop;
        $where_keys = array('id', $time_field.' >=', $time_field.' <=');
        if ($extra_where = $this->get_recent_extra_where()) {
            $where_keys = array_merge($where_keys, array_keys($extra_where));
        }
        $curr_hour = time() - time() % 3600;
        //当前小时以后没有缓存，或有其他条件不能使用缓存
        if ($start >= $curr_hour || array_diff(array_keys($where), $where_keys)) {
            $user_data = $this->get_users_where($where, $start, $stop);

            return \count($user_data);
        }
        //数据量太大而且缓存覆盖不到，拒绝查询
        if ($start < $today - $this->get_recent_max_ttl()) {
            return;
        }

        $prefix = $this->get_recent_prefix();
        if ($extra_where && $extra_where = array_intersect_key($extra_where, $where)) {
            $prefix .= $this->get_recent_extra_hash($extra_where);
        }
        $all_data = array();
        //分段循环，尽量使用缓存
        while ($start <= $stop) {
            $save_key = '';
            if ($start < $today) { //按天查
                $prev = strtotime(date('Y-m-d', $start));
                $next = $prev + 86400 - 1;
                if ($start === $prev) { //整天查询，用得到缓存
                    $save_key = $prefix.sprintf('_%s_day', date('Y-m-d', $prev));
                }
            } else { //按小时查
                $prev = $start - $start % 3600;
                $next = $prev + 3600 - 1;
                if ($start === $prev && $start < $curr_hour) { //整小时查询，用得到缓存
                    $save_key = $prefix.sprintf('_%s_h%s', date('Y-m-d', $prev), date('H', $prev));
                }
            }
            if ($next > $stop) { //结束时间不足一天或一小时，不能使用缓存
                $next = $stop;
                $save_key = '';
            }
            $all_data[] = $this->load_users_range($redis, $start, $next, $save_key);
            $start = $next + 1;
        }

        return \count(exec_function_array('array_replace', $all_data));
    }
}
