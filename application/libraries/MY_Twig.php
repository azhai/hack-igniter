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

require_once VENDPATH.'autoload.php';

/**
 * 简单封装Twig模板.
 */
class MY_Twig extends Twig_Environment
{
    public function __construct(array $options = [])
    {
        if (isset($option['root'])) {
            $root = $option['root'];
            unset($option['root']);
        } else {
            $root = APPPATH.'templates';
        }
        if (! isset($option['cache'])) {
            $option['cache'] = APPPATH.'cache/twig';
        }
        $loader = new Twig_Loader_Filesystem($root);
        parent::__construct($loader, $options);
    }
}
