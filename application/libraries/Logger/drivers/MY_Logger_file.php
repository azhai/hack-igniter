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

defined('LOG_WRITE_FILE_FREQ') || define('LOG_WRITE_FILE_FREQ', 1); //写文件的概率


/**
 * 文件日志，按主题、日期分割文件
 */
class MY_Logger_file extends CI_Driver
{
    public $time_format = 'Y-m-d H:i:s';
    protected $filename = '';
    protected $records = [];
    protected $closed = false;

    /**
     * 构造函数
     */
    public function __construct()
    {
        register_shutdown_function(function ($self) {
            $self->__destruct();
        }, $this);
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        if ($this->closed === false) {
            $this->close();
        }
        $this->closed = true;
    }

    public function close()
    {
        $this->writeFiles();
        $this->records = null;
    }

    /**
     * 设置文件位置
     * @param string $name 日志主题，同时也是日志文件名
     * @param string $directory 日志目录
     */
    public function prepare($name = '', $directory = false)
    {
        $this->filename = ($name ? $name . '_' : '') . '%s.log';
        if ($directory === false) {
            if (!($directory = config_item('log_path'))) {
                $directory = APPPATH . 'logs';
            }
        }
        if (is_dir($directory) || mkdir($directory, 0777, true)) {
            $this->filename = rtrim($directory, '/') . DIRECTORY_SEPARATOR . $this->filename;
        }
    }

    public function writeFiles()
    {
        foreach ($this->records as $date => & $records) {
            $file = sprintf($this->filename, $date);
            $appends = implode('', $records);
            $bytes = file_put_contents($file, $appends, FILE_APPEND | LOCK_EX);
            if ($bytes !== false) { //写入成功，清除已写记录
                $records = [];
            }
        }
    }

    public function writeLine(array $record)
    {
        $today = date('Ymd', $record['time']);
        if (!isset($this->records[$today])) {
            $this->records[$today] = [];
        }
        $record['time'] = date($this->time_format, $record['time']);
        $line = implode(' ', $record) . PHP_EOL;
        $this->records[$today][] = $line;
        if (LOG_WRITE_FILE_FREQ >= 1 || LOG_WRITE_FILE_FREQ >= random_int(1, 10000) / 10000) {
            $this->writeFiles();
        }
    }
}
