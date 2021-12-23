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

namespace Mylib\Util;

/**
 * 打包批量处理
 * Example:
 * class HistoryModel
 * {
 *     use \Mylib\Util\Bundle;.
 *
 *     public function __construct()
 *     {
 *         parent::__construct();
 *         //超过50个或1秒钟执行一次complete()
 *         $this->add_timer(50, 1);
 *     }
 *
 *     public function process(array $batch_data)
 *     {
 *         return $this->insert_batch($batch_data);
 *     }
 * }
 */
trait Bundle
{
    protected $_timer; //定时器
    protected $_queue = array();   //待处理队列

    public function __destruct()
    {
        $this->complete();
    }

    /**
     * 批量处理方法.
     *
     * @param array $batch_data 累积的批量数据
     */
    public function process(array $batch_data)
    {
    }

    /**
     * 处理目前累积的数据.
     */
    public function complete()
    {
        $batch_data = array_splice($this->_queue, 0);
        if (\count($batch_data) > 0) {
            $this->process($batch_data);
        }
    }

    /**
     * 设置定时器和队列最大长度.
     *
     * @param int $chunk_size 队列最大长度
     * @param int $gap_secs   定时器间隔时间
     */
    public function add_timer($chunk_size, $gap_secs = 1)
    {
        $this->_timer = new \Mylib\Util\Timer(0, $gap_secs);
        $this->_timer->setQueueAndSize($this->_queue, $chunk_size);
    }

    /**
     * 添加一个数据，如果命中则会处理一批累积数据.
     *
     * @param mixed $data
     */
    public function add_data($data)
    {
        if (null !== $data) {
            $this->_queue[] = $data;
        }
        if ($this->_timer->isHitting()) {
            $this->complete();
        }
    }
}
