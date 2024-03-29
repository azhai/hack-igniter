<?php
/**
 * hack-igniter.
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @author  Ryan Liu (azhai)
 *
 * @see    http://azhai.surge.sh/
 *
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 基础Controller.
 */
class MY_Controller extends CI_Controller
{
    public $base_url = '';
    public $page_url = '';
    public $curr_page = '';
    public $curr_action = '';
    public $db_conns = array();
    public $tpl_alias = array();
    protected $context = array();
    protected $request_method = 'get';
    protected $response_type = 'html';

    /**
     * 构造函数.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('my');
        $this->load->helper('env');
    }

    public function _remap($action, $params = array())
    {
        if (! method_exists($this, $action)) {
            $action = 'index';
        } else {
            $ref = new \ReflectionMethod($this, $action);
            if (! $ref->isPublic()) {
                return show_404(); //私有方法
            }
        }
        $this->context = (array) $this->initialize();
        $data = exec_method_array($this, $action, $params);
        if (is_array($data)) {
            $this->context = array_replace($this->context, $data);
        }
        $this->finalize($action);
    }

    /**
     * 默认首页.
     *
     * @return array/null 变量数组
     */
    public function index()
    {
    }

    protected static function get_theme_path($theme_name = 'default')
    {
        return APPPATH.rtrim('themes/'.$theme_name, '/').'/';
    }

    /**
     * Get the directory of current.
     */
    protected function get_current_path()
    {
        $dir = trim($this->router->directory, '/');

        return APPPATH.rtrim('controllers/'.$dir, '/').'/';
    }

    protected function get_template($action = '')
    {
        if ($action && isset($this->tpl_alias[$action])) {
            $action = $this->tpl_alias[$action];
        }
        $page_action = trim($this->get_page_url($action), '/');
        if ('twig' === $this->response_type) {
            return $page_action.'.twig';
        }
        $view_path = $this->get_current_path().'views/';
        $tpl_file = $view_path.$page_action.'.php';
        if (file_exists($tpl_file)) {
            return $tpl_file;
        }
    }

    protected function get_page_url($action = '', array $args = array(), $with_dir = false)
    {
        if (empty($action)) {
            $action = $this->curr_action;
        }
        $result = sprintf('/%s/%s/', $this->curr_page, $action);
        if ($with_dir) {
            $dir = $this->router->directory;
            $result = '/'.strtolower(trim($dir, '/')).$result;
        }
        if ($args = array_filter($args, 'strlen')) {
            $result .= '?'.http_build_query($args);
        }

        return $result;
    }

    protected function get_globals()
    {
        $theme_name = defined('SITE_THEME_NAME') ? SITE_THEME_NAME : '';
        $static_url = defined('SITE_STATIC_URL') ? SITE_STATIC_URL : '/static/';

        return array(
            'static_url' => rtrim($static_url, '/'),
            'base_url' => $this->base_url,
            'page_url' => $this->page_url,
            'theme_dir' => self::get_theme_path($theme_name),
            'curr_page' => $this->curr_page,
            'curr_action' => $this->curr_action,
        );
    }

    protected function initialize()
    {
        $this->load->helper('url');
        $this->curr_page = $this->uri->rsegments[1];
        $this->curr_action = $this->uri->rsegments[2];
        $this->base_url = rtrim(base_url(), '/');
        $this->page_url = $this->get_page_url();
        $this->request_method = $this->input->method(false);

        return array();
    }

    protected function finalize($action)
    {
        if ('json' === $this->response_type) {
            $content = $this->render_json($this->context);
        } else {
            $file = $this->get_template($action);
            $globals = $this->get_globals();
            $render = 'render_'.$this->response_type;
            $content = $this->{$render}($file, $this->context, $globals);
        }
        return exit($content);
    }

    protected function render_json(array $context = array())
    {
        $this->load->library('MY_Templater', array(), 'tpl');
        $this->tpl->setContentType('json');
        return exit(to_json($context));
    }

    protected function render_html($template, array $context = array(), array $globals = array())
    {
        $this->load->library('MY_Templater', array(), 'tpl');
        $this->tpl->setContentType('html');
        $this->tpl->addGlobal($globals);
        $this->tpl->addFrameFile($template);

        return $this->tpl->render($context);
    }

    protected function render_twig($template, array $context = array(), array $globals = array())
    {
        $this->load->library('MY_Twig', array(), 'twig');
        foreach ($globals as $name => $value) {
            $this->twig->addGlobal($name, $value);
        }

        return $this->twig->render($template, $context);
    }
}
