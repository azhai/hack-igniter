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

class MY_Controller extends CI_Controller
{
    protected $context = [];
    protected $module = '';
    protected $request_method = 'get';
    protected $response_type = 'html';
    public $db_conns = [];

    public function _remap($action, $params = [])
    {
        if (!is_cli() && starts_with($action, '_')) {
            return show_404(); //私有方法
        }
        $this->context = (array) $this->initialize();
        if (!method_exists($this, $action)) {
            $action = 'index';
        }
        $data = exec_method_array($this, $action, $params);
        if (is_array($data)) {
            $this->context = array_replace($this->context, $data);
        }
        $this->finalize($action);
    }

    public static function get_theme_path($theme_name = 'default')
    {
        return APPPATH . rtrim('themes/' . $theme_name, '/') . '/';
    }

    protected function get_template($action = '')
    {
        if (empty($action)) {
            $rsegments = $this->router->uri->rsegments;
            $action = $rsegments ? $rsegments[2] : 'index';
        }
        $view_dir = $this->router->get_module_dir() . '/views/';
        $tpl_file = $view_dir . sprintf('%s/%s.php', $this->module, $action);
        if (file_exists($tpl_file)) {
            return $tpl_file;
        }
    }

    public function get_page_url($action = '', array $args = [])
    {
        $segments = $this->router->uri->segments;
        $pieces = array_slice($segments, 0, 2);
        $result = '/' . implode('/', $pieces) . '/';
        if ($action) {
            $result .= $action . '/';
        }
        if ($args = array_filter($args, 'strlen')) {
            $result .= '?' . http_build_query($args);
        }
        return $result;
    }

    protected function get_globals()
    {
        $theme_name = defined('SITE_THEME_NAME') ? SITE_THEME_NAME : '';
        $static_url = defined('SITE_STATIC_URL') ? SITE_STATIC_URL : '/static/';
        $result = [
            'static_url' => rtrim($static_url, '/'),
            'page_url' => $this->page_url,
            'theme_dir' => self::get_theme_path($theme_name),
            'curr_module' => $this->module,
            'store_layout' => 'store_base', //前台layout
            'curr_controller' => $this->uri->rsegments[1],
            'curr_action' => $this->uri->rsegments[2],
        ];
        return $result;
    }

    protected function initialize()
    {
        $this->load->helper('url');
        if (empty($this->module)) {
            $this->module = $this->router->get_class();
        }
        $this->base_url = rtrim(base_url(), '/');
        $this->page_url = $this->get_page_url();
        $this->request_method = $this->input->method(false);
        return [];
    }

    protected function finalize($action)
    {
        if ('json' === $this->response_type) {
            $content = $this->render_json($this->context);
        } else {
            $file = $this->get_template($action);
            $globals = $this->get_globals();
            $render = 'render_' . $this->response_type;
            $content = $this->$render($file, $this->context, $globals);
        }
        return die($content);
    }

    protected function render_json(array $context = [])
    {
        $this->load->library('MY_Templater', [], 'tpl');
        $this->tpl->setContentType('json');
        return json_encode($context);
    }

    protected function render_html($template, array $context = [], array $globals = [])
    {
        $this->load->library('MY_Templater', [], 'tpl');
        $this->tpl->setContentType('html');
        $this->tpl->addGlobal($globals);
        $this->tpl->addFrameFile($template);
        return $this->tpl->render($context);
    }

    protected function render_twig($template, array $context = [], array $globals = [])
    {
        require_once VDRPATH . 'Twig/lib/Twig/Autoloader.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(APPPATH . 'templates');
        $twig = new Twig_Environment($loader, [
            'cache' => APPPATH . 'templates/compiled',
        ]);
        foreach ($globals as $name => $value) {
            $twig->addGlobal($name, $value);
        }
        $tpl = $twig->load($template);
        return $tpl->render($context);
    }

    /**
     * 格式化输出json格式数据
     */
    protected function lb_output($status, $message, $data = array())
    {
        $result = array(
            'status_code' => $status,
            'status_msg' => $message,
        );
        if (!empty($data)) {
            $result['data'] = $data;
        }
        echo json_encode($result);
        exit;
    }

    public function index()
    {
    }
}
