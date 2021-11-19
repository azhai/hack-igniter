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

if (! function_exists('to_corner_span')) {
    /**
     * 生成角标.
     *
     * @param mixed $corner
     */
    function to_corner_span($corner)
    {
        $corner_tpl = '<span class="pull-right label %s</span>';

        return $corner ? sprintf($corner_tpl, $corner) : '';
    }
}

if (! function_exists('to_menu_link')) {
    /**
     * 生成菜单项.
     */
    function to_menu_link(array $menu)
    {
        $menu_tpl = '<a class="J_menuItem" href="%s">%s</a>';
        $title = $menu['title'];
        if ($menu['corner']) {
            $title .= ' '.to_corner_span($menu['corner']);
        }

        return sprintf($menu_tpl, $menu['url'], $title);
    }
}
