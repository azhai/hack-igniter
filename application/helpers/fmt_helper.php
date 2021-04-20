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


if (! function_exists('get_numbers')) {
    /**
     * 保留字符串中的数字和小数点
     */
    function get_numbers($word, $to_int = false)
    {
        $times = preg_match_all('/[\d.]+/', $word, $matches);
        if ($times === 0 || $times === false) {
            return false;
        }
        $number = implode(current($matches));
        return $to_int ? intval($number) : $number;
    }
}


if (! function_exists('format_money')) {
    /**
     * 输出金额
     */
    function format_money($cent)
    {
        return sprintf('%.2f', $cent / 100);
    }
}


if (! function_exists('format_date')) {
    /**
     * 输出日期
     */
    function format_date($datetime)
    {
        if (empty($datetime)) {
            return '';
        } elseif (is_numeric($datetime)) {
            return date('Y-m-d', intval($datetime));
        } else {
            $date = substr($datetime, 0, 10);
            return ('0000-00-00' === $date) ? '' : $date;
        }
    }
}


if (! function_exists('format_stars')) {
    /**
     * 输出0~5颗星
     */
    function format_stars($number = 0, $is_white = false)
    {
        $integer = intval($number);
        if ($integer > 5) {
            $integer = 5;
        } elseif ($integer < 0) {
            $integer = 0;
        }
        return str_repeat($is_white ? '&star;' : '&starf;', $integer);
    }

    function format_stars_white($number = 0)
    {
        return format_stars($number) . format_stars(5 - $number, true);
    }

    function format_moons_half($number = 0)
    {
        $integer = intval($number);
        $stars = ($number - $integer) ? '🌑🌑🌑🌑🌑🌓🌕🌕🌕🌕' : '🌑🌑🌑🌑🌑🌕🌕🌕🌕🌕';
        return mb_substr($stars, 5 - $integer, 5);
    }
}


if (! function_exists('hide_middle')) {
    /**
     * 隐藏中间若干位
     */
    function hide_middle($str, $before = 3, $after = 4)
    {
        $size = strlen($str) - $before - $after;
        if ($size > 0) {
            return substr_replace($str, str_repeat('*', $size), $before, $size);
        } else {
            return str_repeat('*', $before + $after);
        }
    }
}


if (! function_exists('align_right')) {
    /**
     * 输出金额
     */
    function align_right($str, $size)
    {
        $str = str_pad($str, $size, ' ', STR_PAD_LEFT);
        return str_replace(' ', '&nbsp;&nbsp;', $str);
    }
}


if (! function_exists('array_export')) {
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

if (! function_exists('array_part')) {
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


if (! function_exists('last_month_day')) {
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


if (! function_exists('escape_db_input')) {
    /**
     * 过滤$_REQUEST字符串中的危险字符，用于mysql查询
     */
    function escape_db_input($input, $strip_tags=true) {
        if(is_array($input)) {
            foreach($input as $key => $value) {
                $input[$key] = escape_db_input($value);
            }
        } else {
            if(get_magic_quotes_gpc()) {
                if(ini_get('magic_quotes_sybase')){
                    $input = str_replace("''", "'", $input);
                }
                else {
                    $input = stripslashes($input);
                }
            }
            if($strip_tags) {
                $input = strip_tags($input);
            }
            $input = mysql_real_escape_string($input);
            $input = trim($input);
        }
        return $input;
    }
}
