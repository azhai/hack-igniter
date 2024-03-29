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

if (! function_exists('whereis')) {
    /**
     * 调用Linux Bash的whereis命令，输出命令的完整路径
     * exec和shell_exec的区别是它只返回输出的最后一行.
     *
     * @param mixed $cmd
     * @param mixed $only_binary
     */
    function whereis($cmd, $only_binary = false)
    {
        $opts = $only_binary ? '-b' : '';
        $output = @exec(sprintf('/bin/whereis %s %s', $opts, $cmd));
        if (empty($output)) {
            return null;
        }
        $pieces = explode(':', $output, 2);
        if (2 === count($pieces) && trim($pieces[0]) === $cmd) {
            return strtok(ltrim($pieces[1]), " \r\n");
        }

        return false;
    }
}

if (! function_exists('toolkit_gen_files')) {
    /**
     * 生成直观的图表（与火焰图相近）.
     *
     * 安装
     * sudo apt install -y graphviz
     * go install github.com/tideways/toolkit@latest
     * https://github.com/tideways/php-xhprof-extension.git
     * 火焰图用法：
     * toolkit analyze-xhprof -m 0.0003 61274e7955c6d.json > 61274e7955c6d.txt
     * toolkit generate-xhprof-graphviz -t 0.0003 -o 61274e7955c6d.dot 61274e7955c6d.json
     * dot -T svg -o 61274e7955c6d.svg 61274e7955c6d.dot
     *
     * @param mixed $filename
     * @param mixed $ext
     * @param mixed $min
     */
    function toolkit_gen_files($filename, $ext = 'json', $min = 0)
    {
        $outfile = $filename.'.'.ltrim($ext, '.');
        if (empty($outfile) || ! file_exists($outfile)) {
            return;
        }
        if ($toolkit = whereis('toolkit', true)) {
            shell_exec(sprintf('%s analyze-xhprof -m %f %s > %s.txt', $min, $toolkit, $outfile, $filename));
            shell_exec(sprintf('%s generate-xhprof-graphviz -t %f -o %s.dot %s', $min, $toolkit, $filename, $outfile));
        }
        if ($toolkit && $dot = whereis('dot', false)) {
            shell_exec(sprintf('%s -T svg -o %s.svg %s.dot', $dot, $filename, $filename));
        }
    }
}

if (! function_exists('xhprof_open')) {
    /**
     * 开启xhprof.
     */
    function xhprof_open()
    {
        static $options = array(
            'nob' => 'XHPROF_FLAGS_NO_BUILTINS',
            'cpu' => 'XHPROF_FLAGS_CPU',
            'mem' => 'XHPROF_FLAGS_MEMORY',
        );
        $value = 0;
        $prefix = is_php('7.0') ? 'TIDEWAYS_' : '';
        $opts = func_get_args();
        foreach ($opts as $key) {
            $key = strtolower($key);
            if (isset($options[$key])) {
                $name = $prefix.$options[$key];
                $value |= constant($name);
            }
        }
        if ($prefix) {
            return tideways_xhprof_enable($value);
        }

        return xhprof_enable($value);
    }
}

if (! function_exists('xhprof_close')) {
    /**
     * 关闭xhprof并保存结果.
     *
     * @param mixed $tk_min
     * @param mixed $base_name
     * @param mixed $out_dir
     */
    function xhprof_close($tk_min = -1, $base_name = '', $out_dir = false)
    {
        $xhprof_data = is_php('7.0') ? tideways_xhprof_disable() : xhprof_disable();
        if (empty($xhprof_data)) {
            return false;
        }
        $out_dir = $out_dir ? realpath($out_dir) : sys_get_temp_dir();
        if (empty($base_name)) {
            $base_name = 'xhprof-'.uniqid();
        }
        $filename = $out_dir.DIRECTORY_SEPARATOR.$base_name;
        $content = json_encode($xhprof_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        @file_put_contents($filename.'.json', $content);
        if ($tk_min >= 0) { //生成调用耗时图表
            toolkit_gen_files($filename, '.json', $tk_min);
        }

        return $filename;
    }
}
