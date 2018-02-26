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

namespace Mylib\Behavior;


/**
 * 扩展Model，按月分表，表名带_yyyymm的后缀
 */
trait MY_Monthly
{
    protected $_table_year = 0;
    protected $_table_month = 0;

    protected function init_calendar()
    {
        $this->_table_year = intval(date('Y'));
        $this->_table_month = intval(date('m'));
    }

    public function table_name($another = false)
    {
        if (false !== $another) {
            $this->_table_name = $another;
        }
        if (0 === $this->_table_year) {
            $this->init_calendar();
        }
        return sprintf(
            '%s_%04d%02d',
            $this->_table_name,
            $this->_table_year,
            $this->_table_month
        );
    }

    public function get_time_field()
    {
        if (property_exists($this, '_time_field')) {
            return $this->_time_field;
        }
        return 'created_at';
    }

    public function get_timestamp()
    {
        return mktime(0, 0, 0, $this->_table_month, 1, $this->_table_year);
    }

    public function backward($offset = 1)
    {
        if (0 === $this->_table_year) {
            $this->init_calendar();
        }
        $offset = intval($offset);
        if (0 !== $offset) {
            $diff_year = floor($offset / 12);
            $diff_month = $offset % 12;
            if ($diff_month < $this->_table_month) {
                $this->_table_month -= $diff_month;
                $this->_table_year -= $diff_year;
            } else {
                $this->_table_month -= ($diff_month - 12);
                $this->_table_year -= ($diff_year + 1);
            }
        }
        return $this->table_name();
    }

    public function forward($offset = 1)
    {
        return $this->backward(0 - $offset);
    }

    protected function _iter_month(array& $where, $start = null, $stop = null)
    {
        $time_field = $this->get_time_field();
        $first_time = is_null($start) && is_null($stop);
        if (true === $first_time) { //找出起止时间
            $start = $stop = 0;
            if (isset($where[$time_field . ' >='])) {
                $start = $where[$time_field . ' >='];
            }
            if (isset($where[$time_field . ' <'])) {
                $stop = $where[$time_field . ' <'];
            }
        } else {
            $stop = $this->get_timestamp() - 1;
            if (!is_numeric($start)) {
                $stop = date('Y-m-d H:i:s', $stop);
            }
            $where[$time_field . ' <'] = $stop;
            $this->backward(); //退回一个月
        }
        $table_name = $this->table_name();
        if ($start > $stop || !$this->table_exists($table_name)) {
            return array(false, false); //停止循环
        } else {
            return array($start, $stop);
        }
    }

    public function count_where($where = [])
    {
        $result = 0;
        $where = (array)$where;
        $this->init_calendar();
        list($start, $stop) = $this->_iter_month($where);
        while ($stop) {
            if ($where) {
                $this->where($where);
            }
            $count = $this->count();
            $result += $count;
            list($start, $stop) = $this->_iter_month($where, $start, $stop);
        }
        return $result;
    }

    public function all_where($where = [], $limit = null, $offset = 0, $sort = [])
    {
        $result = [];
        if (empty($offset) || $offset <= 0) {
            $offset = 0;
        }
        $where = (array)$where;
        if (is_string($sort)) {
            $sort = [$sort => 'DESC'];
        } else {
            $sort = (array)$sort;
        }
        $this->init_calendar();
        list($start, $stop) = $this->_iter_month($where);
        while ($stop) {
            if ($offset > 0) { //偏移量
                if ($where) {
                    $this->where($where);
                }
                $count = $this->count();
                $offset -= $count;
            }
            if ($offset <= 0) {
                $offset = ($offset < 0) ? $offset + $count : 0;
                if ($where) {
                    $this->where($where);
                }
                foreach ($sort as $field => $direction) {
                    $this->order_by($field, $direction);
                }
                $rows = $this->all($limit, $offset);
                $result = array_merge($result, $rows);
                if (is_numeric($limit)) { //数量限制
                    $limit -= count($rows);
                    if ($limit <= 0) {
                        break;
                    }
                }
            }
            list($start, $stop) = $this->_iter_month($where, $start, $stop);
        }
        return $result;
    }
}
