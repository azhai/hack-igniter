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

    public function initCache($redis, array $stores = null)
    {
        $this->redis = $redis;
        $this->id = date('Ymd001');
        $total = $this->robin->reset();
        if ($total <= 0) {
            return;
        }
        $this->redis->set('eggs:' . $this->id, $this->eggs);

        $eggs = $this->eggs;
        $times = $eggs / $total;
        if (empty($stores)) {
            $stores = [];
            foreach ($this->prizes as $key => $weight) {
                $stores[$key] = round($weight * $times);
                $eggs -= $stores[$key];
            }
            $stores[$key] += $eggs; // 补给最低奖
        }
        $this->redis->delete('przs:' . $this->id);
        $this->redis->hmSet('przs:' . $this->id, $stores);
        return $stores;
    }

    /**
     * 执行抽奖
     */
    public function play($draw)
    {
        $remain = $this->redis->decrBy('eggs:' . $this->id, $this->dozen);
        if ($remain < 0) {
            $this->redis->set('eggs:' . $this->id, 0);
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
//        ksort($total);
        $go_on = true;
        foreach ($total as $key => $num) {
            $n = $this->redis->hIncrBy('przs:' . $this->id, $key, 0 - $num);
            if ($n < 0) { //终止
                // $go_on = false;
            }
        }
        return ['go_on' => $go_on, 'total' => $total, 'progress' => $progress];
    }
}

function test_jackpot()
{
    /* 数据初始化，weight: 权重 */
    $exp_prizes = [
        '冰封城堡' => 1,
        '赠你跑车' => 1,
        '相聚太空' => 5,
        '浪漫游轮' => 28,
    ];
    $exp_robin = new RoundRobin($exp_prizes);
    $prizes = [
        '贵重礼物' => 33,
        '魔法权杖' => 66, '爱心玫瑰' => 66,
        '包治百病' => 88, '一见钟情' => 99,
        '啵啵奶茶' => 2222, '香蕉' => 28888,
    ];
    $stores = [
        '贵重礼物' => 36,
        '魔法权杖' => 38, '爱心玫瑰' => 66,
        '包治百病' => 88, '一见钟情' => 88,
        '啵啵奶茶' => 3999, '香蕉' => 36666,
    ];
    $redis = new \Redis();
    $redis->connect('127.0.0.1', 6379);
    $pot = new Jackpot($prizes, 40000, 100);
    // $stores = $pot->initCache($redis, $stores);
    $stores = $pot->initCache($redis);
    var_export($stores);

    $draw = function ($robin, $total, $i = 0) {
        if ($i == 0) {
            $key = $robin->setNext('啵啵奶茶');
        } else if ($total['贵重礼物'] >= 1 || $total['魔法权杖'] >= 1 || $total['爱心玫瑰'] >= 1
            || $total['包治百病'] >= 1 || $total['一见钟情'] >= 1) {
            $key = $robin->next();
        } else if ($i % 3 > 0) {
            $key = $robin->next();
        } else {
            $key = $robin->randNext();
        }
        return $key;
    };

    /* 模拟多次 */
    $result = [];
    $stamp = microtime(true);
    for ($i = 0; $i < 400; $i++) {
        $res = $pot->play($draw);
        if ($res['go_on'] === false) {
            break;
        }
        $result[] = json_encode($res['total'], 320);
    }
    printf("use %.2f secs\n", microtime(true) - $stamp);

    /* 输出结果 */
    return $result;
}

$result = test_jackpot();
$counts = array_count_values($result);
asort($counts);
var_export($counts);
