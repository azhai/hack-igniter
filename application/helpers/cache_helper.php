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


if (! function_exists('get_zadd_args')) {
    /**
     * 转换zAdd参数
     */
    function get_zadd_args($cache_key, array $options, array $data, $scol = '', $vcol='')
    {
        $args = array($cache_key,);
        if (! empty($options)) {
            $args[] = $options;
        }
        foreach ($data as $value => $score) {
            $args[] = floatval(empty($scol) ? $score : $score[$scol]);
            $args[] = strval(empty($vcol) ? $value : $score[$vcol]);
        }
        return $args;
    }
}

if (! function_exists('zadd_more')) {
    /**
     * zAdd多条数据
     */
    function zadd_more($redis, $cache_key, array $data, $scol = '', $vcol='')
    {
        foreach (array_chunk($data, 10, true) as $chunk) {
            $args = get_zadd_args($cache_key, array(), $chunk, $scol, $vcol);
            exec_method_array($redis, 'zAdd', $args);
        }
    }
}

if (! function_exists('zincrby_more')) {
    /**
     * zIncrBy多条数据
     */
    function zincrby_more($redis, $cache_key, array $data, $scol = '', $vcol='')
    {
        foreach ($data as $value => $score) {
            $value = empty($vcol) ? $value : $score[$vcol];
            $score = empty($scol) ? $score : $score[$scol];
            $redis->zIncrBy($cache_key, $score, $value);
        }
        //阿里云下不可用
//        foreach (array_chunk($data, 10, true) as $chunk) {
//            $args = get_zadd_args($cache_key, ['INCR'], $chunk, $scol, $vcol);
//            exec_method_array($redis, 'zAdd', $args);
//        }
    }
}
