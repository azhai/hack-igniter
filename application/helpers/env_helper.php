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


if (!function_exists('is_winnt')) {
    /**
     * 是否Windows系统，不含Cygwin.
     */
    function is_winnt()
    {
        return 'win' === strtolower(substr(PHP_OS, 0, 3));
    }
}


if (!function_exists('is_testing_mode')) {
    /**
     * 是否测试环境
     */
    function is_testing_mode()
    {
        if (is_cli() && count($_SERVER['argv']) > 1) {
            return in_array('testing', $_SERVER['argv']);
        }
        if (!isset($_SERVER) || empty($_SERVER)) {
            return false;
        }
        if (isset($_SERVER['CI_ENV'])) {
            return 'development' === $_SERVER['CI_ENV'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            return '.test' === substr($_SERVER['SERVER_NAME'], -5);
        }
        return false;
    }
}


if (!function_exists('sleep_until')) {
    /**
     * 休眠直到某个时刻
     */
    function sleep_until($next_time, $half = false)
    {
        $sleep_secs = $next_time - microtime(true);
        if ($half && $sleep_secs > 0.5) {
            $sleep_secs -= 0.5;
        }
        if ($sleep_secs > 0) {
            usleep($sleep_secs * pow(10, 6));
        }
    }
}


if (!function_exists('get_real_client_ip')) {
    /**
     * 获取真实客户端IP
     */
    function get_real_client_ip()
    {
        $keys = [
            'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR',
        ];
        foreach ($keys as $key) {
            $ipaddr = isset($_SERVER[$key]) ? $_SERVER[$key] : '';
            if ($ipaddr && strlen($ipaddr) >= 7) {
                return $ipaddr;
            }
        }
        return '0.0.0.0';
    }
}


if (!function_exists('set_language')) {
    /**
     * 设置语言.
     */
    function set_language($locale_dir, $language = 'zh_CN', $domian = 'messages')
    {
        putenv('LANG=' . $language);
        setlocale(LC_ALL, $language);
        if (function_exists('bindtextdomain')) {
            bindtextdomain($domian, $locale_dir);
            textdomain($domian);
        }
    }
}


if (!function_exists('rand_numbers')) {
    /**
     * 产生几个数字，仅用于Linux/Unix/MacOS下
     */
    function rand_numbers($length = 8)
    {
        if (is_winnt()) {
            return;
        }
        $bytes = ceil($length / 2);
        $binary = file_get_contents('/dev/urandom', false, null, 0, $bytes);
        // 每次转为整数的长度不超过12x4bit，否则可能变为科学计数法
        $hex_array = str_split(bin2hex($binary), 12);
        $buffer = '';
        foreach ($hex_array as $hex) {
            $buffer .= substr(hexdec($hex), 0 - strlen($hex));
        }
        return $buffer;
    }
}


if (!function_exists('respawn_process')) {
    /**
     * 启动一个相同的CLI进程，并退出当前进程
     */
    function respawn_process()
    {
        if (!is_cli() || is_winnt()) {
            return;
        }
        //$cmdline = $_SERVER['_'] . ' ' . implode(' ', $_SERVER['argv']); // 完整命令
        $pid = getmypid();
        $content = file_get_contents('/proc/' . $pid . '/cmdline');
        $cmdline = trim(str_replace("\0", ' ', $content)); // 完整命令
        $filename = '/proc/' . $pid . '/fd/1';
        $outfile = is_link($filename) ? readlink($filename) : false; //输出重定向
        $filename = '/proc/' . $pid . '/fd/2';
        $errfile = is_link($filename) ? readlink($filename) : false; //错误重定向
        if ($outfile) {
            $cmdline .= ('/dev/null' === $outfile) ? ' > /dev/null' : ' >> ' . $outfile;
        }
        if ($errfile) {
            $cmdline .= ($errfile === $outfile) ? ' 2>&1' : ' >> ' . $errfile;
        }
        //必须放在后台运行，将新进程托管给init进程（pid=1）
        //否则，当前进程将成为新进程的父进程，无法退出
        shell_exec($cmdline . ' &'); //后台进程
        exit(0);
    }
}
