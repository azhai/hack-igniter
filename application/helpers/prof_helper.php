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


if (!function_exists('whereis')) {
    /**
     * 调用Linux Bash的whereis命令，输出命令的完整路径
     * exec和shell_exec的区别是它只返回输出的最后一行
     */
    function whereis($cmd, $only_binary = false)
    {
        $opts = $only_binary ? '-b' : '';
        $output = @exec(sprintf('/bin/whereis %s %s', $opts, $cmd));
        if (empty($output)) {
            return null;
        }
        $pieces = explode(':', $output, 2);
        if (count($pieces) === 2 && trim($pieces[0]) === $cmd) {
            return strtok(ltrim($pieces[1]), " \r\n");
        }
        return false;
    }
}


if (!function_exists('toolkit_gen_files')) {
    /**
     * 生成直观的图表（与火焰图相近）.
     *
     * 安装
     * sudo apt install -y graphviz
     * go install github.com/tideways/toolkit@latest
     * https://github.com/tideways/php-xhprof-extension.git
     * 火焰图用法：
     * toolkit analyze-xhprof -m 0.0003 -o 61274e7955c6d.txt 61274e7955c6d.json
     * toolkit generate-xhprof-graphviz -o 61274e7955c6d.dot 61274e7955c6d.json
     * dot -T svg -t 0.0003 -o 61274e7955c6d.svg 61274e7955c6d.dot
     */
    function toolkit_gen_files($filename, $ext = 'json', $min = 0)
    {
        $outfile = $filename . '.' .  ltrim($ext, '.');
        if (empty($outfile) || !file_exists($outfile)) {
            return;
        }
        if ($toolkit = whereis('toolkit', true)) {
            shell_exec(sprintf('%s analyze-xhprof -m %f -o %s.txt %s', $min, $toolkit, $filename, $outfile));
            shell_exec(sprintf('%s generate-xhprof-graphviz -t %f -o %s.dot %s', $min, $toolkit, $filename, $outfile));
        }
        if ($toolkit && $dot = whereis('dot', false)) {
            shell_exec(sprintf('%s -T svg -o %s.svg %s.dot', $dot, $filename, $filename));
        }
    }
}


if (!function_exists('xhprof_open')) {
    /**
     * 开启xhprof
     */
    function xhprof_open()
    {
        static $options = [
            'nob' => 'XHPROF_FLAGS_NO_BUILTINS',
            'cpu' => 'XHPROF_FLAGS_CPU',
            'mem' => 'XHPROF_FLAGS_MEMORY',
        ];
        $value = 0;
        $is_php7 = is_php_gte('7');
        $opts = func_get_args();
        foreach ($opts as $key) {
            $key = strtolower($key);
            if (isset($options[$key])) {
                $name = ($is_php7 ? 'TIDEWAYS_' : '') . $options[$key];
                $value |= constant($name);
            }
        }
        if ($is_php7) {
            return tideways_xhprof_enable($value);
        } else {
            return xhprof_enable($value);
        }
    }
}


if (!function_exists('xhprof_close')) {
    /**
     * 关闭xhprof并保存结果
     */
    function xhprof_close($tk_min = -1, $base_name = '', $out_dir = false)
    {
        $is_php7 = is_php_gte('7');
        $xhprof_data = $is_php7 ? tideways_xhprof_disable() : xhprof_disable();
        if (empty($xhprof_data)) {
            return false;
        }
        $out_dir = $out_dir ? realpath($out_dir) : sys_get_temp_dir();
        if (empty($base_name)) {
            $base_name = 'xhprof-' . uniqid();
        }
        $filename = $out_dir . DIRECTORY_SEPARATOR . $base_name;
        @file_put_contents($filename . '.json', json_encode($xhprof_data, 320));
        if ($tk_min >= 0) { //生成调用耗时图表
            toolkit_gen_files($filename, '.json', $tk_min);
        }
        return $filename;
    }
}

