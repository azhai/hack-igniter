<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Math_page extends CI_Controller
{
    /**
     * 加法
     * @param integer  $a 被加数
     * @param integer $b 加数，默认1
     */
    public function add($a, $b = 1)
    {
        return $a + $b;
    }
}
