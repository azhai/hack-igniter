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

defined('BASEPATH') or exit('No direct script access allowed');


if (! function_exists('exec_log_args')) {
    /**
     * 将参数格式化，并与前缀信息拼接为字符串
     *
     * @param array/string $args 日志格式和参数
     * @return string
     */
    function exec_log_args($args, $with_head = false)
    {
        if (is_null($args) || $args === '') {
            return '';
        } elseif (is_scalar($args)) {
            return strval($args);
        }
        if ($with_head) {
            $head = sprintf('%s [#%d] ', date('Y-m-d H:i:s'), posix_getpid());
            $args[0] = $head . $args[0];
        }
        return exec_function_array('sprintf', $args);
    }
}


if (! function_exists('log_debug')) {
    /**
     * 记录信息到日志或屏幕
     *
     * @param string $log 日志内容
     * @param string ...$args 其他参数
     * @return bool
     */
    function log_debug($log)
    {
        $log = exec_log_args(func_get_args(), true);
        log_debug_direct($log);
    }

    function log_debug_direct($log)
    {
        if (PHP_SAPI === 'cli') {
            echo $log . "\n";
        } else {
            log_message('DEBUG', $log);
        }
    }
}


if (! function_exists('log_error')) {
    /**
     * 记录错误到日志或屏幕
     *
     * @param string $log 日志内容
     * @param string ...$args 其他参数
     * @return bool
     */
    function log_error($log)
    {
        $log = exec_log_args(func_get_args(), true);
        log_error_direct($log);
    }

    function log_error_direct($log)
    {
        if (PHP_SAPI === 'cli') {
            file_put_contents('php://stderr', $log . "\n", FILE_APPEND);
        } else {
            log_message('ERROR', $log);
        }
    }
}


if (! function_exists('log_trace')) {
    /**
     * 输出PHP调用栈
     */
    function log_trace()
    {
        $e = new Exception();
        $trace = explode("\n", $e->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();
        for ($i = 0; $i < $length; $i++) {
            // replace '#someNum' with '$i)', set the right ordering
            $result[] = ($i + 1) . ')' . substr($trace[$i], strpos($trace[$i], ' '));
        }
        return log_error("\t" . implode("\n\t", $result));
    }
}

if (! function_exists('starts_with')) {
    /**
     * 开始的字符串相同.
     *
     * @param string $haystack 可能包含子串的字符串
     * @param string $needle   要查找的子串
     *
     * @return bool
     */
    function starts_with($haystack, $needle)
    {
        return 0 === strncmp($haystack, $needle, strlen($needle));
    }
}

if (! function_exists('ends_with')) {
    /**
     * 结束的字符串相同.
     *
     * @param string $haystack 可能包含子串的字符串
     * @param string $needle   要查找的子串
     *
     * @return bool
     */
    function ends_with($haystack, $needle)
    {
        $ndlen = strlen($needle);

        return 0 === $ndlen || (strlen($haystack) >= $ndlen
                && 0 === substr_compare($haystack, $needle, -$ndlen));
    }
}

if (! function_exists('convert_string')) {
    /**
     * 将内容转为另一种编码
     *
     * @param string $word     原始字符串
     * @param string $encoding 目标编码
     *
     * @return string 转换后的字符串
     */
    function convert_string($word, $encoding = 'UTF-8')
    {
        $encoding = strtoupper($encoding);
        if (function_exists('mb_detect_encoding')) {
            return mb_detect_encoding($word, $encoding, true) ?
                $word : mb_convert_encoding($word, $encoding, 'UTF-8, GBK');
        }
        if (function_exists('iconv')) {
            $from_encoding = ('UTF-8' === $encoding) ? 'GBK' : 'UTF-8';

            return iconv($from_encoding, $encoding.'//IGNORE', $word);
        }
    }
}

if (! function_exists('from_json')) {
    /**
     * json反序列化.
     *
     * @param mixed $data
     */
    function from_json($data)
    {
        return json_decode($data, true, 512, JSON_ERROR_DEPTH);
    }
}

if (! function_exists('to_json')) {
    /**
     * json序列化.
     *
     * @param mixed $data
     */
    function to_json($data)
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

if (! function_exists('to_array')) {
    /**
     * 主要用于将对象公开属性转为关联数组.
     *
     * @param mixed $value      对象或其他值
     * @param bool  $read_props 读取对象公开属性为数组
     *
     * @return array
     */
    function to_array($value, $read_props = true)
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_object($value) && $read_props) {
            return get_object_vars($value);
        }

        return null === $value ? array() : array($value);
    }
}

if (! function_exists('array_column')) {
    /**
     * 为PHP5.4及以下实现，但不支持PHP7的对象数组.
     *
     * @param mixed      $column_key
     * @param null|mixed $index_key
     */
    function array_column(array $input, $column_key, $index_key = null)
    {
        if (empty($index_key) && ! is_numeric($index_key)) {
            $index_key = null;
        }
        $result = array();
        foreach ($input as $row) {
            if (null === $column_key) {
                $value = $row;
            } elseif (isset($row[$column_key])) {
                $value = $row[$column_key];
            } else {
                $value = null;
            }
            if (null !== $index_key && isset($row[$index_key])) {
                $result[$row[$index_key]] = $value;
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }
}

if (! function_exists('exec_method_array')) {
    /**
     * 调用类/对象方法.
     * 不使用call_user_func_array()的理由同上.
     *
     * @param object /class $clsobj 对象/类
     * @param string $method 方法名
     * @param array  $args   参数数组，长度限制5个元素及以下
     *
     * @return mixed 执行结果，没有找到可执行方法时返回null
     */
    function exec_method_array($clsobj, $method, array $args = array())
    {
        $args = array_values($args);
        if (is_object($clsobj)) {
            switch (count($args)) {
                case 0:
                    return $clsobj->{$method}();

                case 1:
                    return $clsobj->{$method}($args[0]);

                case 2:
                    return $clsobj->{$method}($args[0], $args[1]);

                case 3:
                    return $clsobj->{$method}($args[0], $args[1], $args[2]);

                case 4:
                    return $clsobj->{$method}($args[0], $args[1], $args[2], $args[3]);

                case 5:
                    return $clsobj->{$method}($args[0], $args[1], $args[2], $args[3], $args[4]);
            }
        }
        if (method_exists($clsobj, $method)) {
            $ref = new ReflectionMethod($clsobj, $method);
            if ($ref->isPublic() && ! $ref->isAbstract()) {
                if ($ref->isStatic()) {
                    return $ref->invokeArgs(null, $args);
                }

                return $ref->invokeArgs($clsobj, $args);
            }
        }
    }
}

if (! function_exists('exec_function_array')) {
    /**
     * 调用函数/闭包/可invoke的对象
     * 不使用call_user_func_array()，因为它有几个限制：
     * 一是函数的默认参数会丢失；
     * 二是$args中如果有引用参数，那么它们必须以引用方式传入；
     * 三是性能较低，只有反射的一半多一点。
     *
     * @param string /Closure/object $func 函数名/闭包/含__invoke方法的对象
     * @param array $args 参数数组，长度限制5个元素及以下
     *
     * @return mixed 执行结果，没有找到可执行函数时返回null
     */
    function exec_function_array($func, array $args = array())
    {
        $args = array_values($args);

        switch (count($args)) {
            case 0:
                return $func();

            case 1:
                return $func($args[0]);

            case 2:
                return $func($args[0], $args[1]);

            case 3:
                return $func($args[0], $args[1], $args[2]);

            case 4:
                return $func($args[0], $args[1], $args[2], $args[3]);

            case 5:
                return $func($args[0], $args[1], $args[2], $args[3], $args[4]);

            default:
                if (is_object($func)) {
                    $ref = new ReflectionMethod($func, '__invoke');

                    return $ref->invokeArgs($func, $args);
                }
                if (is_callable($func)) {
                    $ref = new ReflectionFunction($func);

                    return $ref->invokeArgs($args);
                }
        }
    }
}

if (! function_exists('exec_callback')) {
    /**
     * 执行给定的函数或方法.
     *
     * @param mixed $callback
     */
    function exec_callback($callback, array $args = array())
    {
        if (is_array($callback) && count($callback) >= 2) {
            list($object, $method) = array_splice($callback, 0, 2, $args);

            return exec_method_array($object, $method, $callback);
        }
        assert(is_callable($callback));

        return exec_function_array($callback, $args);
    }
}
