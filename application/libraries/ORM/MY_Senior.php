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
    protected $_group_order = [];

    /**
     * group by然后order by
     * @return $this
     */
    public function group_order_by($group, $order = '', $direction = 'ASC')
    {
        if (empty($order)) {
            $order = $this->primary_key();
        }
        $this->_group_order = [
            $this->protect_identifiers($group),
            $this->protect_identifiers($order),
            $direction,
        ];
        $this->_mixin_switches['senior'] = true;
        return $this;
    }

    /**
     * MySQL的GROUP BY查询之后，ORDER BY不再起作用
     * 必须使用RIGHT JOIN自身的方法，这里自动实现此功能
     * @return $this
     */
    public function get_group_order_sql(& $db, $table = '', $reset = true)
    {
        if (empty($this->_group_order)) {
            return $db->get_compiled_select($table, $reset);;
        }
        @list($group, $order, $direction) = $this->_group_order;
        $db->where($group . ' IS NOT NULL', null, false);
        $db->group_by('_grp_idx', false);
        $sql = $db->get_compiled_select($table, $reset);

        $min_or_max = ('ASC' === $direction) ? 'MIN' : 'MAX';
        $tpl = 'SELECT %s as _grp_idx, %s(%s) as _max_val';
        $select = sprintf($tpl, $group, $min_or_max, $order);
        $from = 'FROM ' . $this->protect_identifiers($table);
        $join = $from . "\nRIGHT JOIN (\n" . $select . "\n" . $from;
        $tpl = ') SELF ON %s=SELF._grp_idx AND %s=SELF._max_val';
        $tail = "\n" . sprintf($tpl, $group, $order) . "\n"
            . sprintf('ORDER BY %s %s', $order, $direction);
        $sql = str_replace($from, $join, $sql) . $tail;
        return $sql;
    }
}
