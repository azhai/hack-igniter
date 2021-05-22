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
        $best = '';
        mt_srand();
        $idx = mt_rand(1, $this->total);
        foreach ($this->data as $key => $weight) {
            if ($idx >= $weight) {
                $idx -= $weight;
                $best = $key;
            }
            $this->currents[$key] += $weight;
        }
        $this->currents[$best] -= $this->total;
        return $best;
    }
}


function test_rrd()
{
    /* 数据初始化，weight: 权重 */
    $data = ['A'=>1, 'B'=>2, 'C'=>5];
    $rrd = new RoundRobin($data);
    $times = 80;
    
    /* 模拟多次 */
    $result = [];
    for ($i = 0; $i < $times; $i++) {
        if ($i > 0 && $i % 10 == 0) {
            $result[] = '|';
        }
        if ($i > 0 && $i % 16 == 0) { //周期
            $result[] = "\t";
        }
        $result[] = $rrd->next();
    }
    $result[] = "\n";
    
    
    /* 模拟多次 */
    $rrd->reset();
    for ($i = 0; $i < $times; $i++) {
        if ($i > 0 && $i % 10 == 0) {
            $result[] = '|';
        }
        if ($i > 0 && $i % 16 == 0) { //周期
            $result[] = "\t";
        }
        if ($i == 8) {
            $result[] = $rrd->setNext('B');
        } else {
            $result[] = $rrd->next();
        }
    }
    $result[] = "\n";
    
    /* 模拟多次 */
    $rrd->reset();
    for ($i = 0; $i < $times; $i++) {
        if ($i > 0 && $i % 10 == 0) {
            $result[] = '|';
        }
        if ($i > 0 && $i % 16 == 0) { //周期
            $result[] = "\t";
        }
        if ($i == 8) {
            $result[] = $rrd->setNext('C');
        } else {
            $result[] = $rrd->next();
        }
    }
    $result[] = "\n";
    
    /* 输出结果 */
    return implode(' ', $result);
}


function test_rand_rrd()
{
    /* 数据初始化，weight: 权重 */
    $data = ['A'=>1, 'B'=>3, 'C'=>10, 'Z'=> 36];
    $rrd = new RoundRobin($data);
    $times = 160;
    
    /* 模拟多次 */
    $result = [];
    for ($i = 0; $i < $times; $i++) {
        if ($i > 0 && $i % 10 == 0) {
            $result[] = ($i % 80 == 0) ? "\n" : '|';
        }
        if ($i % 7 == 0) {
            $result[] = $rrd->randNext();
        } else {
            $result[] = $rrd->next();
        }
    }
    $result[] = "\n";
    
    /* 输出结果 */
    return implode(' ', $result);
}

// echo str_replace("\n ", "\n", test_rrd()) . "\n";
// echo str_replace("\n ", "\n", test_rand_rrd()) . "\n";

