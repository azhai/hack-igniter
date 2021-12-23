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

if (! class_exists('CI_Loader')) {
    require_once BASEPATH.'core/Loader.php';
}
require_once APPPATH . 'database/MY_DB.php';
require_once APPPATH . 'core/CI_DB.php';

class MY_Loader extends CI_Loader
{
    //根据namespace前缀，自动加载类文件
    protected $third_parties = array();
    protected $multi_prefixes = array();

    public function __construct()
    {
        parent::__construct();
        $this->name_space('Mylib', APPPATH.'libraries/');
    }

    public function initialize()
    {
        parent::initialize();
        spl_autoload_register(array($this, 'auto_load'));
    }

    public function set_alias($object, $class, $alias = '')
    {
        if (null !== $alias && false !== $alias) {
            if (empty($alias) || ! is_string($alias)) {
                $alias = strtolower($class);
            }
            $CI = &get_instance();
            $CI->{$alias} = $object;
        }

        return $object;
    }

    public function database($params = '', $return = false, $query_builder = null)
    {
        CI_DB::$last_active_group = false;
        if (true === $return) { //实现单例连接
            //保留当前的配置名，用于实现单例连接
            if (is_string($params) && false === strpos($params, '://')) {
                CI_DB::$last_active_group = $params;
            }

            return MY_DB($params, $query_builder);
        } else {
            return parent::database($params, $return, $query_builder);
        }
    }

    public function model($model, $name = '', $db_conn = false)
    {
        if (empty($name) && is_string($model) && '' !== $model) {
            if ($name = strrchr($model, '/')) {
                $name = ltrim($name, '/');
            } else {
                $name = $model;
            }
        }

        return parent::model($model, lcfirst($name), $db_conn);
    }

    /**
     * 加载Service.
     *
     * @param mixed $name
     * @param mixed $alias
     */
    public function service($name, $alias = '')
    {
        $base_class = config_item('subclass_prefix').'Service';
        if (! class_exists($base_class, false)) {
            $app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;
            if (file_exists($app_path.$base_class.'.php')) {
                require_once $app_path.$base_class.'.php';
            }
        }
        $serv_path = APPPATH.'services'.DIRECTORY_SEPARATOR;
        $name = trim($name, '/');
        if (file_exists($serv_path.$name.'.php')) {
            require_once $serv_path.$name.'.php';
        }
        $class = strrchr($name, '/');
        if (false === $class) {
            $class = ucfirst($name);
        } else {
            $class = ucfirst(ltrim($class, '/'));
        }
        if (! class_exists($class, false)) {
            return;
        }
        $object = new $class();

        return $this->set_alias($object, $class, $alias);
    }

    public function name_space($prefix, $path)
    {
        $path = rtrim($path, '/\\').DIRECTORY_SEPARATOR;
        $prefix = trim($prefix, '\\_');
        $this->third_parties[$prefix] = $path;
        if (false !== strpos($prefix, '\\')) { //复杂前缀另建索引
            $this->multi_prefixes[] = $prefix;
            ksort($this->multi_prefixes);
        }
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file the file to require
     * @param bool   $once
     *
     * @return bool true if the file exists, false if not
     */
    public static function require_file($file, $once = false)
    {
        if (empty($file) || ! file_exists($file)) {
            return false;
        }
        if ($once) {
            require_once $file;
        } else {
            require $file;
        }

        return true;
    }

    /**
     * 加载缓存，例如使用配置中名为user的redis缓存.
     *
     * Example:
     * $CI = get_instance();
     * $CI->load->cache('redis', 'user', 'user_cache');
     * $redis = $CI->user_cache->redis->instance();
     *
     * @param string $adapter 缓存类型，如redis/memcached
     * @param string/array $driver_params 连接配置或配置名称
     * @param string/null $object_name 对象名称，为空将会得到类似 user_redis_cache 字符串
     *
     * @return object
     */
    public function cache($adapter, $driver_params = 'default', $object_name = null)
    {
        return $this->get_driver_library('cache', $adapter, $driver_params, $object_name);
    }

    /**
     * Library Loader.
     *
     * @param mixed  $library     Library name
     * @param array  $params      Optional parameters to pass to the library class constructor
     * @param string $object_name An optional object name to assign to
     *
     * @return object
     */
    public function library($library, $params = null, $object_name = null)
    {
        if (is_array($library)) {
            foreach ($library as $key => $value) {
                $object_name = is_numeric($key) ? null : $key;
                parent::library($value, $params, $object_name);
            }

            return $this;
        }
        if (empty($object_name)) {
            $object_name = basename($library);
            $prefix = config_item('subclass_prefix');
            if (starts_with($object_name, $prefix)) {
                $object_name = substr($object_name, strlen($prefix));
            }
            $object_name = lcfirst($object_name);
        }

        return parent::library($library, $params, $object_name);
    }

    /**
     * Driver Loader.
     *
     * @param string|string[] $library     Driver name(s)
     * @param array           $params      Optional parameters to pass to the driver
     * @param string          $object_name An optional object name to assign to
     *
     * @return bool|object Object or FALSE on failure if $library is a string
     *                     and $object_name is set. CI_Loader instance otherwise.
     */
    public function driver($library, $params = null, $object_name = null)
    {
        if (is_array($library)) {
            foreach ($library as $key => $value) {
                $object_name = is_numeric($key) ? null : $key;
                parent::driver($value, $params, $object_name);
            }

            return $this;
        }
        if (! strpos($library, '/')) {
            $prefix = config_item('subclass_prefix');
            if (starts_with($library, $prefix)) {
                $libdir = substr($library, strlen($prefix));
            } else {
                $libdir = $library;
            }
            $library = ucfirst($libdir).'/'.$library;
        }

        return parent::driver($library, $params, $object_name);
    }

    /**
     * 加载驱动，返回库对象
     *
     * @param string       $lib_name      库名
     * @param array|string $params        库配置
     * @param mixed        $driver_params 驱动配置
     * @param null|string  $object_name
     *
     * @return object
     */
    public function get_driver_library(
        $lib_name,
        $params,
        $driver_params = null,
        $object_name = null
    ) {
        @list($adapter, $params) = $this->_get_adapter_params($params);
        if (empty($object_name)) {
            $object_name = $this->get_object_name($lib_name, $adapter, $driver_params);
        }
        $CI = get_instance();
        if (isset($CI->{$object_name})) {
            return $CI->{$object_name};
        }
        $driver_params = $this->_proc_driver_params($adapter, $driver_params);
        if ($this->driver($lib_name, $params, $object_name)) {
            $object = $CI->{$object_name};
            if (method_exists($object->{$adapter}, 'set_options')) {
                $object->{$adapter}->set_options($driver_params);
            }
        }

        return $object;
    }

    /**
     * 拼接对象名.
     *
     * @param string       $lib_name      库名
     * @param array|string $params        库配置
     * @param mixed        $driver_params 驱动配置
     * @param mixed        $adapter
     *
     * @return string
     */
    public function get_object_name($lib_name, $adapter, $driver_params = null)
    {
        $object_name = lcfirst($adapter).'_'.$lib_name;
        if (empty($driver_params)) {
            return $object_name;
        }
        if (is_string($driver_params)) {
            return $driver_params.'_'.$object_name;
        }
        $object_hash = md5(serialize($driver_params));

        return $object_name.'_'.substr($object_hash, 0, 8);
    }

    protected function auto_load($class)
    {
        $class = trim($class, '\\_');
        $first = strstr($class, '\\', true);
        if (! isset($this->third_parties[$first])) { //尝试遍历复杂前缀
            $i = count($this->multi_prefixes);
            do {
                if (--$i < 0) {
                    return false;
                }
                $first = $this->multi_prefixes[$i];
            } while (! starts_with($class, $first));
        }
        $name = substr($class, strlen($first) + 1);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $name);
        $fullpath = $this->third_parties[$first].$path.'.php';
        if (self::require_file($fullpath)) {
            $autoload = false;

            return class_exists($class, $autoload)
                || interface_exists($class, $autoload)
                || trait_exists($class, $autoload);
        }
    }

    /**
     * 提取适配器类型.
     *
     * @param array|string $params 库配置
     *
     * @return array
     */
    protected function _get_adapter_params($params)
    {
        if (is_array($params)) {
            $adapter = $params['adapter'];
        } else {
            $adapter = $params;
            $params = array('adapter' => $adapter);
        }

        return array($adapter, $params);
    }

    /**
     * 处理和保存驱动配置.
     *
     * @param string $adapter       驱动
     * @param mixed  $driver_params 配置名或参数
     *
     * @return array
     */
    protected function _proc_driver_params($adapter, $driver_params = null)
    {
        $config = get_instance()->config;
//        log_message('DEBUG', 'driver_params before: ' . json_encode($driver_params));
        if (! is_array($driver_params)) {
            //同一配置文件只加载一次
            $config->load($adapter, true, true);
            $all_params = $config->item('all_'.$adapter);
            if (empty($all_params)) { //将全部参数另存
                $all_params = $config->item($adapter);
                $config->set_item('all_'.$adapter, $all_params);
            }
            if (is_string($driver_params) && isset($all_params[$driver_params])) {
                $driver_params = $all_params[$driver_params]; //使用当前部分
            } else {
                $driver_params = $all_params;
            }
        }
//        log_message('DEBUG', 'driver_params after: ' . json_encode($driver_params));
        $config->set_item($adapter, $driver_params);

        return $driver_params;
    }
}
