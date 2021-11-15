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

namespace Mylib\Mixin;

use \MY_Model;

\defined('INDEX_CACHE_KEY') || \define('INDEX_CACHE_KEY', 'index'); //缓存配置名


/**
 * 索引表
 */
abstract class Index_base extends MY_Model
{
    abstract public function get_cache_key();
    abstract public function get_log_table();
    abstract public function get_interval();

    public function __construct()
    {
        parent::__construct();
        $this->load->cache('redis', INDEX_CACHE_KEY, 'index_cache');
    }

    /**
     * 相对于 $stamp 时间，下个月1号零点的时间戳
     */
    public static function next_month_begin($stamp = 0)
    {
        if ($stamp <= 0) {
            $stamp = time();
        }
        $stamp += 86400 * (32 - date('d', $stamp));
        return strtotime(date('Y-m-1', $stamp));
    }

    /**
     * 更新索引，每隔一段时间（默认一小时）一行记录
     */
    public function _add_log_index($field, $pkey = 'id')
    {
        $interval = $this->get_interval();
        $row = $this->order_by('stamp_number', 'DESC')->one();
        if ($row && $row['stamp_number'] > time() - $interval) { // 无需更新
            return false;
        }
        $suffix = '';
        $db = $this->reconnect();
        $idx_table = $this->table_name();
        $log_table = $this->get_log_table();
        $tpl = "SELECT 0, `{$field}`-`{$field}`%%{$interval} as ts, MIN({$pkey}) as mid, COUNT(*) as cnt, '%s' FROM `{$log_table}%s`";
        if (!empty($row)) { // 继续执行最后
            $stamp = $row['stamp_number'];
            $suffix = $row['table_suffix'];
            $sql = sprintf($tpl, $suffix, $suffix);
            $first = $db->query($sql . " WHERE `{$field}`>={$stamp} AND `{$field}`<{$stamp}+{$interval}")->row_array();
            $this->update(['start_id' => $first['mid'], 'row_count' => $first['cnt']], ['id' => $row['id']], 1);
            $where = " WHERE `{$field}`>={$stamp}+{$interval} AND `{$field}`<" . self::next_month_begin($stamp);
            $full_sql = "INSERT INTO `{$idx_table}` " . $sql . $where . " GROUP BY ts ORDER BY mid";
            $db->query($full_sql);
            echo $full_sql . "\n";
        }
        if (empty($suffix) || $suffix !== date('_Ym')) { // 可能执行当月
            $suffix = date('_Ym');
            $sql = sprintf($tpl, $suffix, $suffix);
            $where = " WHERE `{$field}`<" . self::next_month_begin(time());
            $full_sql = "INSERT INTO `{$idx_table}` " . $sql . $where . " GROUP BY ts ORDER BY mid";
            $db->query($full_sql);
            echo $full_sql . "\n";
        }
        return true;
    }

    /**
     * 根据时间找出起始id
     */
    public function _get_start_id($field, $start, $pkey = 'id', $suffix = '')
    {
        $suffix = $suffix ? '_' . ltrim($suffix, '_') : date('_Ym');
        if ($cache_key = $this->get_cache_key()) {
            $cache_key .= $suffix;
            $stop = $start - $this->get_interval();
            $options = ['limit' => [0, 1]];
            $keys = $this->index_cache->zRevRangeByScore($cache_key, $start, $stop, $options);
            if (\count($keys) > 0) {
                return $keys[0];
            }
        }
        $this->_add_log_index($field, $pkey);
        $find = ['stamp_number <=' => $start, 'table_suffix' => $suffix];
        if ($row = $this->order_by('stamp_number', 'DESC')->one($find)) {
            $this->index_cache->zAdd($cache_key, $row['stamp_number'], $row['start_id']);
            $this->index_cache->expire($cache_key, 86400);
            return $row['start_id'];
        }
    }

    /**
     * 根据时间时间重写where，加上id条件
     */
    public function _rewrite_where($where, $field, $pkey = 'id', $suffix = '')
    {
        $cond = $pkey . ' >=';
        if (empty($where) || !\is_array($where) || isset($where[$cond])) {
            return $where;
        }
        $start = 0;
        foreach ($where as $key => $value) {
            $key = str_replace(['`', ' '], '', strtolower($key));
            if ($key === $field.'>' || $key === $field.'>=') {
                $start = $value;
                break;
            }
        }
        if ($start > 0) {
            $start_id = $this->_get_start_id($field, $start, $pkey, $suffix);
            if (!empty($start_id)) {
                $where[$cond] = $start_id;
            }
        }
        return $where;
    }

    /**
     * 根据时间生成部分SQL
     */
    public function _get_from_where($start, $field, $pkey = 'id', $suffix = '')
    {
        if (empty($suffix)) {
            $suffix = date('_Ym', $start);
        }
        $where = [$field . ' >=' => $start];
        $items = $this->_rewrite_where($where, $field, $pkey, $suffix);
        $conds = '1';
        foreach ($items as $key => $value) {
            $conds .= " AND $key $value";
        }
        $log_table = $this->get_log_table() . $suffix;
        return " FROM `{$log_table}` WHERE {$conds}";
    }
}
