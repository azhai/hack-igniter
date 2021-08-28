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

defined('BASEPATH') or exit('No direct script access allowed');


if (!function_exists('get_similar_len')) {
    /**
     * 开头相同部分的长度
     */
    function get_similar_len($haystack, $needle)
    {
        $len = min(strlen($haystack), strlen($needle));
        while (0 !== strncmp($haystack, $needle, $len)) {
            $len--;
        }
        return $len;
    }
}


if (!function_exists('binary_search')) {
    /**
     * 二分（折半）查找算法
     */
    function binary_search($target, $right, $compare)
    {
        assert(is_callable($compare));
        $left = 0;
        do {
            $middle = $left + intval(($right - $left) / 2);
            $sign = $compare($target, $middle);
            if ($sign > 0) { //目标在右侧
                $left = $middle;
            } else if ($sign < 0) { //目标在左侧
                $right = $middle;
            } else {
                break;
            }
        } while ($right - $left > 1);
        return $middle;
    }
}


if (!function_exists('array_flatten')) {
    /**
     * 将多维折叠数组变为一维
     *
     * @param array $values 多维数组
     * @param bool $drop_empty 去掉为空的值
     * @return array
     */
    function array_flatten(array $values, $drop_empty = false)
    {
        $result = [];
        array_walk_recursive($values, function ($value) use (&$result, $drop_empty) {
            if (!$drop_empty || !empty($value)) {
                $result[] = $value;
            }
        });
        return $result;
    }
}


if (!function_exists('array_export')) {
    /**
     * 格式化数组的输出，采用短语法
     */
    function array_export(array $data, $indent = 0)
    {
        $lines = explode("\n", var_export($data, true));
        $max = count($lines) - 1;
        foreach ($lines as $i => &$line) {
            if (0 === $i) {
                $line = '[';
            } elseif ($max === $i) {
                $line = str_repeat(' ', $indent) . ']';
            } else {
                $line = str_repeat(' ', $indent + 4) . ltrim($line);
            }
        }
        return implode("\n", $lines);
    }
}

if (!function_exists('array_part')) {
    /**
     * 只获取数组的一部分
     */
    function array_part($data, $keys = null)
    {
        if (empty($keys) || '*' === $keys) {
            return array_slice($data, 0); // 返回全部
        }
        if (is_string($keys)) {
            $keys = array_map('trim', explode(',', $keys));
        }
        $refer = array_fill_keys($keys, null);
        return array_intersect_key($data, $refer);
    }
}

if (!function_exists('rand_num')) {
    /**
     * 随机整数.
     * @param int $min 最小值
     * @param int $max 最大值
     * @return int
     */
    function rand_num($min, $max)
    {
        return is_php('7.0') ? random_int($min, $max) : rand($min, $max);
    }
}


if (!function_exists('rand_string')) {
    /**
     * 产生可识别的随机字符串
     */
    function rand_string($length = 8, $shuffles = 2, $good_letters = '')
    {
        if (empty($good_letters)) {
            // 字符池，去掉了难以分辨的0,1,o,O,l,I
            $good_letters = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        }
        srand((float)microtime() * 1000000);
        // 每次可以产生的字符串最大长度
        $gen_length = ceil($length / $shuffles);
        $buffer = '';
        while ($length > 0) {
            $good_letters = str_shuffle($good_letters);
            $buffer .= substr($good_letters, 0, $gen_length);
            $length -= $gen_length;
            $gen_length = min($length, $gen_length);
        }
        return $buffer;
    }
}


if (!function_exists('last_month_day')) {
    /**
     * 找出上个月的这一天，没有这一天时使用月末
     * （使用时间戳计算，避免判断跨年）
     */
    function last_month_day($time)
    {
        $day = intval(date('d', $time)); //当月第几天
        $time -= $day * 86400; //退回上月最后一天
        $tail_day = intval(date('d', $time)); //上个月有多长
        if ($day > $tail_day) {
            //上个月较短，没有这几天，使用月末
        } else {
            $time -= ($tail_day - $day) * 86400;
        }
        return date('Y-m-d', $time);
    }
}