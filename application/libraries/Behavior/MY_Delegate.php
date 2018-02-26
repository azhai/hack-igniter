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

namespace Mylib\Behavior;


/**
 * 代理
 */
trait MY_Delegate
{
    protected $_delegates = [];

    /**
     */
    public function check_delegate($delegate)
    {
        return true;
    }

    /**
     */
    public function register($delegate, $name, $alias = '')
    {
        assert($this->check_delegate($delegate));
        $this->register_wild($delegate, $name, $alias);
    }

    /**
     */
    public function register_wild($delegate, $name, $alias = '')
    {
        if (!empty($alias)) {
            $this->_delegates[$name] = $alias;
            $name = $alias;
        }
        if (!is_null($delegate)) {
            $this->_delegates[$name] = $delegate;
        }
    }
}
