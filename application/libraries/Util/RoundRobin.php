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

namespace Mylib\Util;

/**
 * 权重轮询
 */
class RoundRobin
{
    protected $data = [];      // 原始权重键值对
    protected $currents = [];  // 当前累积权重
    protected $ladders = [];   // 权重阶梯
    protected $total = 0;      // 权重总和

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->reset();
    }
    
    public function reset()
    {
        $keys = array_keys($this->data);
        $this->currents = array_fill_keys($keys, 0); //PHP5.2+
        $this->total = array_sum($this->data);
        return $this->total;
    }

    /**
     * 执行一次
     */
    public function next()
    {
        $best = '';
        $max_weight = 0;
        foreach ($this->data as $key => $weight) {
            $current_weight = $this->currents[$key] += $weight;
            if ($current_weight >= $max_weight) {
                $best = $key;
                $max_weight = $current_weight;
            }
        }
        $this->currents[$best] -= $this->total;
        return $best;
    }

    /**
     * 强制选中一次
     */
    public function setNext($best)
    {
        foreach ($this->data as $key => $weight) {
            $this->currents[$key] += $weight;
        }
        $this->currents[$best] -= $this->total;
        return $best;
    }

    /**
     * 随机选中一次
     */
    public function randNext()
    {
        mt_srand();
        $idx = mt_rand(1, $this->total);
        $total = 0;
        $best = '';
        foreach ($this->data as $key => $weight) {
            $this->currents[$key] += $weight;
            if ($total <= $idx) {
                $best = $key;
            }
            $total += $weight;
        }
        $this->currents[$best] -= $this->total;
        return $best;
    }
}
