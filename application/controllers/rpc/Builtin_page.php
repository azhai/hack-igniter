<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Builtin_page extends MY_Controller
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
}
