<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mylib\Util\Geo_Hilbert;


class Builtin extends MY_Service
{
    /**
     * 加法
     * @param integer $a 被加数
     * @param integer $b 加数，默认1
     */
    public function add($a, $b = 1)
    {
        return $a + $b;
    }

    /**
     * 调用PHP的日期函数
     */
    public function date($format = 'Y-m-d H:i:s', $timestamp = false)
    {
        if (false === $timestamp) {
            $timestamp = time();
        }
        return date($format, $timestamp);
    }

    /**
     * 计算大致距离，单位：米
     */
    public function get_around_distance(array $p1, array $p2)
    {
        $gh = new Geo_Hilbert($p1);
        $gh2 = new Geo_Hilbert($p2);
        return $gh->get_around_distance($gh2->encode());
    }

    /**
     * 计算准确距离，单位：米
     */
    public function get_accuracy_distance(array $p1, array $p2)
    {
        $gh = new Geo_Hilbert($p1);
        return $gh->get_accuracy_distance($p2['lng'], $p2['lat']);
    }
}
