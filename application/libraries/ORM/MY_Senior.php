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
        $this->_group_order = [$group, $order, $direction];
        $this->_mixin_switches['senior'] = true;
        return $this;
    }

    /**
     * MySQL的GROUP BY查询之后，ORDER BY不再起作用
     * 必须使用RIGHT JOIN自身的方法，这里自动实现此功能
     * @return $this
     */
    public function process_group_order_by()
    {
        if (empty($this->_group_order)) {
            return $this;
        }
        @list($group, $order, $direction) = $this->_group_order;
        $table = $this->table_name();
        $select = $this->db->get_select_string(false) ?: '*';
        $where = $this->db->get_where_string(false, false);
        $min_or_max = ('ASC' === $direction) ? 'MIN' : 'MAX';
        $sql = 'SELECT `%s` as _grp_idx, %s(`%s`) as _max_val FROM `%s`';
        $sub_query = sprintf($sql, $group, $min_or_max, $order, $table);
        if ($where) {
            $sub_query .= ' WHERE ' . $where;
        }
        $sub_query .= ' GROUP BY _grp_idx';

        $miner = new Miner($this->db->mysql_pool);
        $miner->select($select)->from($table);
        $criteria = sprintf('`%s`=SELF._max_val', $order);
        $miner->right_join('(' . $sub_query . ')', $criteria, 'SELF');
        $miner->order_by($order, $direction);
        $this->db = $miner;
        return $this;
    }
}
