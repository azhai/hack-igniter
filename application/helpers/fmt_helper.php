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


if (! function_exists('replace_with')) {
    /**
     * 将内容字符串中的变量替换掉.
     *
     * @param string $content 内容字符串
     * @param array  $context 变量数组
     * @param string $prefix  变量前置符号
     * @param string $subfix  变量后置符号
     * @return string 当前内容
     */
    function replace_with($content, array $context = [], $prefix = '', $subfix = '')
    {
        if (empty($context)) {
            return $content;
        }
        if (empty($prefix) && empty($subfix)) {
            $replacers = $context;
        } else {
            $replacers = [];
            foreach ($context as $key => $value) {
                $replacers[$prefix . $key . $subfix] = $value;
            }
        }
        $content = strtr($content, $replacers);
        return $content;
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
