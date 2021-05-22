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

require_once __DIR__ . '/RoundRobin.php';

/**
 * 奖池
 * Author: 阿债 https://azhai.surge.sh
 */
class Jackpot
{
    protected $id = '';
    protected $eggs = 0;     // 总抽奖次数
    protected $dozen = 1;    // 连抽次数
    protected $prizes = [];  // 奖品权重
    public $robin;
    public $redis;

    public function __construct(array $prizes, $eggs, $dozen = 1)
    {
        $this->eggs = $eggs;
        $this->dozen = $dozen;
        $this->prizes = $prizes;
        $this->robin = new RoundRobin($this->prizes);
    }
    
    public function initCache($redis)
    {
        $this->redis = $redis;
        $this->id = date('Ymd001');
        $total = $this->robin->reset();
        if ($total <= 0) {
            return;
        }
        $this->redis->set('eggs:'. $this->id, $this->eggs);
        $times = floor($this->eggs / $total);
        $prizes = [];
        foreach ($this->prizes as $key => $weight) {
            $prizes[$key] = $weight * $times;
        }
        $prizes[$key] += $this->eggs - $total * $times; // 补给最低奖
        $this->redis->hmSet('przs:'. $this->id, $prizes);
    }

    /**
     * 执行抽奖
     */
    public function draw()
    {
        $remain = $this->redis->decrBy('eggs:'. $this->id, $this->dozen);
        if ($remain < 0) {
            $this->redis->set('eggs:'. $this->id, 0);
            return [];
        }
        $keys = array_keys($this->prizes);
        $result = array_fill_keys($keys, 0);
        for ($i = 0; $i < $this->dozen; $i++) {
            if ($result['Z'] >= 8) {
                $key = $this->robin->setNext('C');
            } else if (($i + $remain) % 7 > 0) {
                $key = $this->robin->next();
            } else if ($result['A'] > 0 || $result['B'] > 1) {
                $key = $this->robin->next();
            } else {
                $key = $this->robin->randNext();
            }
            if ($this->redis->hIncrBy('przs:'. $this->id, $key, -1) >= 0) {
                $result[$key] += 1;
            }
        }
        ksort($result);
        return $result;
    }
}


function test_jackpot()
{
    /* 数据初始化，weight: 权重 */
    $prizes = ['A'=>1, 'B'=>3, 'C'=>10, 'Z'=> 36];
    $redis = new \Redis();
    $redis->connect('127.0.0.1', 6379);
    $jp = new Jackpot($prizes, 1000, 10);
    $jp->initCache($redis);
    
    /* 模拟30次 */
    $result = [];
    for ($i = 0; $i < 100; $i++) {
        $result[] = json_encode($jp->draw());
    }
    
    /* 输出结果 */
    return $result;
}

// $result = test_jackpot();
// $counts = array_count_values($result);
// ksort($counts);
// var_export($counts);
// echo "\n" . implode("\n", $result) . "\n";

