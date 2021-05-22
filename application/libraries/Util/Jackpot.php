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
    public function play($draw = null)
    {
        $remain = $this->redis->decrBy('eggs:'. $this->id, $this->dozen);
        if ($remain < 0) {
            $this->redis->set('eggs:'. $this->id, 0);
            return [];
        }
        $keys = array_keys($this->prizes);
        $total = array_fill_keys($keys, 0); // 每种奖品数量
        $progress = []; // 中奖过程
        for ($i = 0; $i < $this->dozen; $i++) {
            if (is_null($draw)) {
                $key = $this->robin->next();
            } else {
                $key = $draw($this->robin, $total, $i + $remain);
            }
            $progress[] = $key;
            $total[$key] += 1;
        }
        ksort($total);
        foreach ($total as $key => $num) {
            $n = $this->redis->hIncrBy('przs:'. $this->id, $key, 0 - $num);
            if ($n < 0) { //报错
            }
        }
        return ['total' => $total, 'progress' => $progress];
    }
}


function test_jackpot()
{
    /* 数据初始化，weight: 权重 */
    $prizes = ['A'=>1, 'B'=>3, 'C'=>10, 'Z'=> 36];
    $redis = new \Redis();
    $redis->connect('127.0.0.1', 6379);
    $pot = new Jackpot($prizes, 100 * 10000, 100);
    $pot->initCache($redis);
    
    $draw = function($robin, $total, $i = 0) {
        if ($total['Z'] >= 90) {
            $key = $robin->setNext('C');
        } else if ($i % 7 > 0) {
            $key = $robin->next();
        } else if ($total['A'] >= 10 || $total['B'] >= 20) {
            $key = $robin->next();
        } else {
            $key = $robin->randNext();
        }
        return $key;
    };
        
    /* 模拟多次 */
    $result = [];
    $stamp = microtime(true);
    for ($i = 0; $i < 10000; $i++) {
        $res = $pot->play($draw);
        $result[] = json_encode($res['total']);
    }
    printf("use %.2f secs\n", microtime(true) - $stamp);
    
    /* 输出结果 */
    return $result;
}

$result = test_jackpot();
$counts = array_count_values($result);
ksort($counts);
var_export($counts);
// echo "\n" . implode("\n", $result) . "\n";

