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


if (! function_exists('is_winnt')) {
    /**
     * 是否Windows系统，不含Cygwin.
     */
    function is_winnt()
    {
        return 'win' === strtolower(substr(PHP_OS, 0, 3));
    }
}


if (! function_exists('get_real_client_ip')) {
    /**
     * 获取真实客户端IP
     */
    function get_real_client_ip()
    {
        $keys = [
            'HTTP_CLIENT_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR',
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


if (! function_exists('set_language')) {
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


if (! function_exists('replace_with')) {
    /**
     * 将内容字符串中的变量替换掉.
     *
     * @param string $content 内容字符串
     * @param array  $context 变量数组
     * @param string $prefix  变量前置符号
     * @param string $subfix  变量后置符号
     * @return string 当前内容
     */
    function replace_with($content, array $context = [], $prefix = '', $subfix = '')
    {
        if (empty($context)) {
            return $content;
        }
        if (empty($prefix) && empty($subfix)) {
            $replacers = $context;
        } else {
            $replacers = [];
            foreach ($context as $key => $value) {
                $replacers[$prefix . $key . $subfix] = $value;
            }
        }
        $content = strtr($content, $replacers);
        return $content;
    }
}


if (! function_exists('get_similar_len')) {
    /**
     * 开头相同部分的长度
     */
    function get_similar_len($haystack, $needle)
    {
        $len = min(strlen($haystack), strlen($needle));
        while (0 !== strncmp($haystack, $needle, $len)) {
            $len --;
        }
        return $len;
    }
}


if (! function_exists('to_array')) {
    /**
     * 主要用于将对象公开属性转为关联数组
     *
     * @param mixed $value      对象或其他值
     * @param bool  $read_props 读取对象公开属性为数组
     * @return array
     */
    function to_array($value, $read_props = true)
    {
        if (is_array($value)) {
            return $value;
        } elseif (is_object($value) && $read_props) {
            return get_object_vars($value);
        } else {
            return is_null($value) ? [] : [$value];
        }
    }
}


if (! function_exists('array_flatten')) {
    /**
     * 将多维折叠数组变为一维
     *
     * @param array $values     多维数组
     * @param bool $drop_empty  去掉为空的值
     * @return array
     */
    function array_flatten(array $values, $drop_empty = false)
    {
        $result = [];
        array_walk_recursive($values, function ($value) use (&$result, $drop_empty) {
            if (!$drop_empty || !empty($value)) {
                $result[] = $value;
            }
        });
        return $result;
    }
}


if (! function_exists('rand_string')) {
    /**
     * 产生可识别的随机字符串
     */
    function rand_string($length = 8, $shuffles = 2, $good_letters = '')
    {
        if (empty($good_letters)) {
            // 字符池，去掉了难以分辨的0,1,o,O,l,I
            $good_letters = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        }
        srand((float) microtime() * 1000000);
        // 每次可以产生的字符串最大长度
        $gen_length = ceil($length / $shuffles);
        $buffer = '';
        while ($length > 0) {
            $good_letters = str_shuffle($good_letters);
            $buffer .= substr($good_letters, 0, $gen_length);
            $length -= $gen_length;
            $gen_length = min($length, $gen_length);
        }
        return $buffer;
    }
}


if (! function_exists('rand_numbers')) {
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


if (! function_exists('respawn_process')) {
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
