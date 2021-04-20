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

$loader = load_class('Loader', 'core');
$loader->helper('env');
$loader->name_space('Psr\\Log', VENDPATH . 'psr/log/Psr/Log');

use \Psr\Log\LogLevel;
use \Psr\Log\LoggerInterface;

/**
 * 日志
 */
class MY_Logger extends CI_Driver_Library implements LoggerInterface
{
    use \Psr\Log\LoggerTrait;

    protected $valid_drivers = ['file', ];
    protected $lib_name = 'Logger';
    protected $adapter = 'file';
    protected $name = 'access';
    protected $allow_levels = []; //允许的级别

    public function __construct(array $options = [])
    {
        if (isset($options['name']) && $options['name']) {
            $this->setLogName($options['name']);
        }
        if (isset($options['threshold']) && $options['threshold']) {
            $threshold = $options['threshold'];
        } else {
            $threshold = LogLevel::DEBUG;
        }
        $this->setLogLevel($threshold);
    }

    /**
     * Load driver
     *
     * Separate load_driver call to support explicit driver load by library or user
     *
     * @param    string    Driver name (w/o parent prefix)
     * @return    object    Child class
     */
    public function load_driver($child)
    {
        $child_path = sprintf('%s/drivers/%s_%s.php', __DIR__, __CLASS__, $child);
        if (file_exists($child_path)) {
            require_once $child_path;
        }
        $obj = parent::load_driver($child);
        $obj->prepare($this->getLogName());
        $this->adapter = $child;
        return $obj;
    }

    public function replace_with($message, array $context)
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }

    public function log($level, $message, array $context = [])
    {
        $level = strtolower($level);
        if (in_array($level, $this->allow_levels, true)) {
            if ($message && $context) {
                $message = $this->replace_with($message, $context);
            }
            $record = [
                'time' => time(),
                'ipaddr'  => get_real_client_ip(),
                'name'    => $this->getLogName(),
                'level'   => strtoupper($level),
                'sep'     => '-->',
                'content' => $message,
            ];
            $this->{$this->adapter}->writeLine($record);
        }
    }

    /**
     * 日志名称，文件日志还会把它作为文件名的一部分.
     *
     * @return string 日志名称
     */
    public function getLogName()
    {
        return $this->name;
    }

    /**
     * 设置日志名称.
     *
     * @param string $name 日志名称
     * @return this
     */
    public function setLogName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 设置过滤级别.
     *
     * @param string $threshold 过滤级别（低于本级别的不记录）
     * @return this
     */
    public function setLogLevel($threshold = 'DEBUG')
    {
        $threshold = strtoupper($threshold);
        $ref = new ReflectionClass('\\Psr\\Log\\LogLevel');
        $all_levels = $ref->getConstants();
        $level_names = array_keys($all_levels);
        $this->allow_levels = array_values($all_levels);
        $offset = array_search($threshold, $level_names, true);
        if (false !== $offset) {
            array_splice($this->allow_levels, $offset + 1);
        }
        return $this;
    }
}
