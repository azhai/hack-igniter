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

namespace Mylib\ORM;

/**
 * 扩展Model，高级功能
 * 让GROUP BY和ORDER BY同时起作用
 * 例如：查询每个班级中年纪最大的学生
 *
 * $model->group_order_by('class_id', 'age', 'DESC')->all();
 */
trait MY_Senior
{
    protected $_group_order = array();
    protected $_created_field = '';
    protected $_changed_field = '';

    public static function column_by_key(array $rows, $col = null, $key = 'id')
    {
        $result = array();
        if ($row = reset($rows)) {
            if (isset($row[$key])) {
                return array_column($rows, $col, $key);
            }
            $is_whole = null === $col || ! isset($row[$col]);
            foreach ($rows as $row) {
                $id = strtr($key, $row);
                $value = $is_whole ? $row[$col] : $row;
            }
        }

        return $result;
    }

    /**
     * group by然后order by.
     *
     * @param mixed $group
     * @param mixed $order
     * @param mixed $direction
     *
     * @return $this
     */
    public function group_order_by($group, $order = '', $direction = 'ASC')
    {
        if (empty($order)) {
            $order = $this->primary_key();
        }
        $this->_group_order = array(
            $this->protect_identifiers($group),
            $this->protect_identifiers($order),
            $direction,
        );

        return $this;
    }

    /**
     * MySQL的GROUP BY查询之后，ORDER BY不再起作用
     * 必须使用RIGHT JOIN自身的方法，这里自动实现此功能.
     *
     * @param mixed $db
     * @param mixed $table
     * @param mixed $reset
     *
     * @return $this
     */
    public function get_group_order_sql(&$db, $table = '', $reset = true)
    {
        @list($group, $order, $direction) = $this->_group_order;
        $db->where($group.' IS NOT NULL', null, false);
        $db->group_by('_grp_idx', false);
        $sql = $db->get_compiled_select($table, $reset);

        $min_or_max = ('ASC' === $direction) ? 'MIN' : 'MAX';
        $tpl = 'SELECT %s as _grp_idx, %s(%s) as _max_val';
        $select = sprintf($tpl, $group, $min_or_max, $order);
        $from = 'FROM '.$this->protect_identifiers($table);
        $join = $from."\nRIGHT JOIN (\n".$select."\n".$from;
        $tpl = ') SELF ON %s=SELF._grp_idx AND %s=SELF._max_val';
        $tail = "\n".sprintf($tpl, $group, $order)."\n"
            .sprintf('ORDER BY %s %s', $order, $direction);
        $sql = str_replace($from, $join, $sql).$tail;

        return $sql;
    }

    public function before_insert($row, $escape = null)
    {
        if (\is_array($row)) {
            $now = date('Y-m-d H:i:s');
            if (false === $escape) {
                $now = "'".$now."'";
            }
            if ($this->_created_field) {
                $row[$this->_created_field] = $now;
            }
            if ($this->_changed_field) {
                $row[$this->_changed_field] = $now;
            }
        }

        return $row;
    }

    public function before_update(array $set, $escape = null)
    {
        if ($this->_changed_field) {
            $now = date('Y-m-d H:i:s');
            if (false === $escape) {
                $now = "'".$now."'";
            }
            $set[$this->_changed_field] = $now;
        }

        return $set;
    }

    public function before_delete($recycle = false, $escape = null)
    {
        return array('is_removed' => $recycle ? 0 : 1);
    }

    public function undelete($where = '', $limit = null, $escape = null)
    {
        $set = $this->before_delete(true, $escape);
        if ($set && $set = $this->before_update($set, $escape)) {
            return $this->update_unsafe($set, $where, $limit, $escape);
        }
    }

    public function diff_save_data(array $data, $uniq, array $where = null)
    {
        $pkey = $this->primary_key();
        $newbies = self::column_by_key($data, null, $uniq);
        $rows = $this->parse_where($where)->all();
        $exists = self::column_by_key($rows, $pkey, $uniq);
        $this->trans_start();
        //启用交叉部分
        if ($shares = array_intersect_key($exists, $newbies)) {
            $changes = $this->before_delete(true);
            $this->where_in('id', array_values($shares));
            $this->update($changes, null, \count($shares));
        }
        //禁用其他部分
        if ($remains = array_diff_key($exists, $shares)) {
            $this->where_in('id', array_values($remains));
            $this->delete(null, \count($remains));
        }
        //增加多出部分
        if ($additions = array_diff_key($newbies, $exists)) {
            foreach ($additions as &$row) {
                if ($where) {
                    $row = array_merge($row, $where);
                }
            }
            $this->insert_batch(array_values($additions));
        }
        $this->trans_complete();
    }
}
