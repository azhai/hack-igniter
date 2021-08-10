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

    public function __construct(array $data = null)
    {
        if (!empty($data)) {
            $this->reset($data);
        }
    }

    /**
     * 返回空的结果
     */
    public function empty_result()
    {
        return array_fill_keys(array_keys($this->data), 0); // 每种奖品数量
    }

    /**
     * 初始化权重
     */
    public function reset(array $data = null)
    {
        if (!empty($data)) {
            $this->data = $data;
        }
        asort($this->data, SORT_NUMERIC);
        $keys = array_keys($this->data);
        $this->currents = array_fill_keys($keys, 0); //PHP5.2+
        $this->total = array_sum($this->data);
        return $this->total;
    }

    /**
     * 更新部分权重
     */
    public function update(array $changes, $is_offset = false)
    {
        foreach ($changes as $key => $value) {
            if (!isset($this->data[$key])) {
                continue;
            }
            if ($is_offset) {
                $this->data[$key] += $value;
            } else {
                $this->data[$key] = $value;
            }
        }
        return $this->reset();
    }

    /**
     * 自动分配库存
     */
    public function allocate($eggs, array $stocks = [])
    {
        $first_key = '';
        reset($this->data);
        $times = $eggs / $this->total;
        foreach ($this->data as $key => $weight) {
            if (empty($first_key)) {
                $first_key = $key;
            }
            $stocks[$key] = round($weight * $times);
            $eggs -= $stocks[$key];
        }
        $stocks[$first_key] += $eggs; // 补给最低奖
        return $stocks;
    }

    /**
     * 执行一次
     */
    public function next()
    {
        $best = '';
        $max_weight = 0;
        foreach ($this->data as $key => $weight) {
            $this->currents[$key] += $weight;
            $current_weight = $this->currents[$key];
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
        if (!isset($this->data[$best]) || $this->data[$best] <= 0) {
            return 0;
        }
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
        $best = '';
        if ($this->total < 1) {
            return $best;
        }
        mt_srand();
        $idx = mt_rand(1, $this->total);
        $total = 0;
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
