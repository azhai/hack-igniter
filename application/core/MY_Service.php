<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 基础微服务
 */
class MY_Service extends CI_Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('my');
        $this->load->helper('env');
    }
}
