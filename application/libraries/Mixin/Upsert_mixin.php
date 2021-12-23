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

/**
 * 更新或插入
 *
 * 请确保 set 中有修改时间等值一定会改变的字段，
 * 否则 affected_rows 判断不准确而导致重复插入数据。
 */
trait Upsert_mixin
{
    /**
     * 更新或插入数据
     */
    public function upsert_to(array $set, array $where, $escape = null)
    {
        $table = $this->table_name();
        $db = $this->ensure_conn();
        $db->set($set, '', $escape);
        if (! empty($where)) {
            $db->where($where, '', $escape);
        }
        $db->update($table, null, null, 1); //最多更新1行
        if ($db->affected_rows() > 0) { //已存在，使用更新
            return false;
        }
        $set = array_replace($set, $where);
        return $db->insert($table, $set, $escape); //插入新行的自增ID
    }

    /**
     * 自增或插入数据
     */
    public function increase_to(array $set, array $where, $incr_cols)
    {
        $table = $this->table_name();
        $db = $this->ensure_conn();
        if (! is_array($incr_cols)) {
            $incr_cols = array_slice(func_get_args(), 2);
        }
        foreach ($set as $key => $value) {
            if (in_array($key, $incr_cols, true)) {
                $value = sprintf('%s + %s', $key, $value);
                $db->set($key, $value, false); //不要escape
            } else {
                $db->set($key, $value, null);
            }
        }
        if (! empty($where)) {
            $db->where($where, '', null);
        }
        $db->update($table, null, null, 1); //最多更新1行
        if ($db->affected_rows() > 0) { //已存在，使用更新
            return false;
        }
        $set = array_replace($set, $where);
        return $db->insert($table, $set, null); //插入新行的自增ID
    }
}
