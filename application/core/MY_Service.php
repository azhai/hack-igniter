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
        $instance = self::get_instance();
        if (is_null($instance)) {
            parent::__construct();
        } else {
            $this->load = $instance->load;
        }
        $this->load->helper('my');
        $this->load->helper('env');
    }

    /**
     * __get magic
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param	string	$key
     */
    public function __get($key)
    {
        // Debugging note:
        //	If you're here because you're getting an error message
        //	saying 'Undefined Property: system/core/Model.php', it's
        //	most likely a typo in your model code.
        return self::get_instance()->$key;
    }
}
