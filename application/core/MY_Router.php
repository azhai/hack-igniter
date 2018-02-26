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

class MY_Router extends CI_Router
{
    /**
     * Set request route
     *
     * Takes an array of URI segments as input and sets the class/method
     * to be called.
     *
     * @used-by CI_Router::_parse_routes()
     * @param   array $segments URI segments
     * @return  void
     */
    protected function _set_request($segments = [])
    {
        if (isset($_SERVER['CI_DIR']) && $_SERVER['CI_DIR']) {
            $this->set_directory($_SERVER['CI_DIR']);
        } elseif (count($segments) > 0) {
            $this->set_directory(array_shift($segments));
        }
        if (empty($segments)) {
            $segments = ['index'];
        }
        return parent::_set_request($segments);
    }

    /**
     * Set default controller
     *
     * @return    void
     */
    protected function _set_default_controller()
    {
        if (empty($this->default_controller)) {
            show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
        }
        $segments = explode('/', $this->default_controller);
        return $this->_set_request($segments);
    }

    /**
     * Set class name
     *
     * @param   string $class Class name
     * @return  void
     */
    public function set_class($class)
    {
        if (!ends_with($class, '_page')) {
            $class = ucfirst($class) . '_page';
        }
        return parent::set_class($class);
    }

    /**
     * Get the directory of current module
     */
    public function get_class()
    {
        $class = lcfirst($this->class);
        if (ends_with($class, '_page')) {
            $class = substr($class, 0, -strlen('_page'));
        }
        return $class;
    }

    /**
     * Get the directory of current module
     */
    public function get_module_dir()
    {
        return APPPATH . 'controllers/' . rtrim($this->directory, '/');
    }
}
