<?php

defined('BASEPATH') || exit('No direct script access allowed');

use Mylib\Util\Geo_hilbert;

class Index extends MY_Service
{
    /**
     * 加法.
     *
     * @param int $a 被加数
     * @param int $b 加数，默认1
     */
    public function add($a, $b = 1)
    {
        return $a + $b;
    }

    /**
     * 调用PHP的日期函数.
     *
     * @param string $format 输出格式，参见PHP文档
     * @param false/int $timestamp 时间戳，默认当前时间
     *
     * @return false|string 输出时间的可读格式
     */
    public function date($format = 'Y-m-d H:i:s', $timestamp = false)
    {
        if (false === $timestamp) {
            $timestamp = time();
        }

        return date($format, $timestamp);
    }

    /**
     * 计算大致距离，单位：米.
     *
     * @param array $p1 坐标点，含lat纬度和lng经度
     * @param array $p2 另一个坐标点，含lat和lng
     *
     * @return float 两点间的直线距离
     */
    public function get_around_distance(array $p1, array $p2)
    {
        $gh = new Geo_hilbert($p1);
        $gh2 = new Geo_hilbert($p2);

        return $gh->get_around_distance($gh2->encode());
    }

    /**
     * 计算准确距离，单位：米.
     *
     * @param array $p1 坐标点，含lat纬度和lng经度
     * @param array $p2 另一个坐标点，含lat和lng
     *
     * @return float 两点间的直线距离
     */
    public function get_accuracy_distance(array $p1, array $p2)
    {
        $gh = new Geo_hilbert($p1);

        return $gh->get_accuracy_distance($p2['lng'], $p2['lat']);
    }
}
