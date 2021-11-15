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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 错误页面
 */
class Errors extends MY_Controller
{
    protected $response_type = 'html';

    public function index($action = 'index')
    {
        $this->context = to_array($this->initialize());
        $this->finalize($action);
    }

    public function error_404()
    {
        return $this->index(__FUNCTION__);
    }
}
