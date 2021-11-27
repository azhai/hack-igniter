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

defined('MAX_READ_ROWS') || define('MAX_READ_ROWS', 5000);

/**
 * 扩展Model，按月分表，表名带_yyyymm的后缀
 *
 *  class Visit_log_model extends MY_Model
 *  {
 *      use \Mylib\ORM\MY_Monthly;
 *
 *      // Model本身定义的连接、表名、主键、字段 ......
 *
 *      // 排序字段，倒序排列
 *      public function get_sort_field()
 *      {
 *          return 'created_at';
 *      }
 *  }
 *
 *  // 在Controller的方法中使用
 *
 *  $this->load->model('visitor_log_model');
 *  // 找出2018年到现在，用户id=1的最晚100条访问记录
 *  $where = ['user_id' => 1];
 *  $count = $this->visit_log_model->count_more($where, '2018-01-01', null);
 *  $rows = $this->visit_log_model->all_more($where, '2018-01-01', null, 100);
 */
trait MY_Monthly
{
    public $beginning;

    /**
     * 获取月初的时间戳.
     *
     * @param null|mixed $time
     */
    public static function get_begin_stamp($time = null)
    {
        if (is_null($time)) {
            return mktime(0, 0, 0, date('n'), 1);
        }
        if (func_num_args() > 1) {
            $year = func_get_arg(0);
            $month = func_get_arg(1);
        } else {
            if (! is_numeric($time)) {
                $time = strtotime($time);
            }
            $year = date('Y', $time);
            $month = date('n', $time);
        }

        return mktime(0, 0, 0, intval($month), 1, intval($year));
    }

    /**
     * 获取执行的终点时间戳.
     *
     * @param null|mixed $start
     */
    public function get_finishing($start = null, array &$tables = [])
    {
        if ($start) {
            return self::get_begin_stamp($start);
        }
        if (count($tables) > 0 && $min_table = min($tables)) { // 找出最早的表
            $min_tail = substr($min_table, strlen($this->table_name()) + 1);
            $year = substr($min_tail, 0, 4);
            $month = substr($min_tail, 4, 2);

            return self::get_begin_stamp($year, $month);
        }

        return self::get_begin_stamp($start);
    }

    /**
     * 用于排序的字段，一般是倒序排列
     * 如果想按名称正序排列，使用下面的代码：
     * return 'name ASC';.
     */
    public function get_sort_field()
    {
        return 'id';
    }

    /**
     * 初始化日历为当前时间，指向当前月份的数据表.
     *
     * @param int/string/null $time
     */
    public function init_calendar($time = null)
    {
        $this->beginning = self::get_begin_stamp($time);
    }

    /**
     * 当前指向的数据表完整名称.
     *
     * @param mixed $another
     *
     * @return string 表完整名称
     */
    public function table_name($another = false)
    {
        if (is_null($this->beginning)) {
            $this->init_calendar();
        }
        $tail = date('Ym', $this->beginning);

        return $this->base_table_name().'_'.$tail;
    }

    /**
     * 向过去移动几个月.
     *
     * @param int $offset
     *
     * @return $this
     */
    public function backward($offset = 1)
    {
        if (is_null($this->beginning)) {
            $this->init_calendar();
        }
        $offset = intval($offset);
        if (0 !== $offset) {
            $year = date('Y', $this->beginning);
            $month = date('n', $this->beginning) - $offset;
            $this->beginning = self::get_begin_stamp($year, $month);
        }

        return $this;
    }

    /**
     * 向未来移动几个月.
     *
     * @param int $offset
     *
     * @return $this
     */
    public function forward($offset = 1)
    {
        return $this->backward(0 - $offset);
    }

    /**
     * 跨表统计行数，可以定义时间范围.
     *
     * @param mixed      $where
     * @param null|mixed $start
     * @param null|mixed $stop
     */
    public function count_more($where = [], $start = null, $stop = null)
    {
        $base_name = $this->base_table_name();
        $tables = $this->list_tables($base_name.'_');
        $start = $this->get_finishing($start);
        $this->init_calendar($stop);

        $count = 0;
        while ($this->beginning >= $start) {
            $table = $this->table_name();
            //中间有表不存在，并不连续
            if (! in_array($table, $tables, true)) {
                $this->backward();
                continue;
            }
            if ($where) {
                $this->parse_where($where);
            }
            $curr_count = $this->count();
            $count += $curr_count;
            $this->backward();
        }

        return $count;
    }

    /**
     * 跨表查询，可以定义时间范围.
     *
     * @param mixed      $where
     * @param null|mixed $start
     * @param null|mixed $stop
     * @param null|mixed $limit
     * @param mixed      $offset
     * @param mixed      $fields
     */
    public function all_more($where, $start = null, $stop = null, $limit = null, $offset = 0, $fields = '*')
    {
        if (empty($offset) || $offset <= 0) {
            $offset = 0;
        }
        $base_name = $this->base_table_name();
        $tables = $this->list_tables($base_name.'_');
        $start = $this->get_finishing($start, $tables);
        $this->init_calendar($stop);
        $order = [$this->get_sort_field() => 'DESC'];

        $result = [];
        $remain = 0;
        while ($this->beginning >= $start) {
            $table = $this->table_name();
            //中间有表不存在，并不连续
            if (! in_array($table, $tables, true)) {
                $this->backward();
                continue;
            }
            //未达到偏移位置，先消耗偏移量
            if ($offset > 0) { //偏移量
                if ($where) {
                    $this->parse_where($where);
                }
                $remain = $this->count();
                $offset -= $remain;
                if ($offset >= 0) {
                    $this->backward();
                    continue;
                }
            }

            //查询数据，倒序
            if ($where) {
                $this->parse_where($where);
            }
            //第一次查询时需要计算剩余偏移量
            $offset = ($offset < 0) ? $offset + $remain : 0;
            $rows = $this->order_by($order)->all($limit, $offset, $fields);
            $offset = 0; //过后偏移量清零
            $curr_count = count($rows);
            if ($curr_count > 0) {
                $result = array_merge($result, $rows);
            }
            //有读取数量限制
            if (is_numeric($limit)) {
                $limit -= $curr_count; //扣除本次已读
                if ($limit <= 0) {
                    break;
                }
            }
            $this->backward();
        }

        return $result;
    }

    /**
     * 跨表查询，可以定义时间范围.
     *
     * @param mixed      $where
     * @param null|mixed $start
     * @param null|mixed $stop
     * @param null|mixed $limit
     * @param int        $page
     * @param mixed      $fields
     */
    public function page_more($where, $start = null, $stop = null, $limit = null, $page = 1, $fields = '*')
    {
        //准备工作
        $remain = is_numeric($limit) && $limit > 0 ? (int) $limit : MAX_READ_ROWS;
        $offset = $remain > 0 && is_numeric($page) && $page > 0 ? ($page - 1) * $remain : 0;
        $base_name = $this->base_table_name();
        $tables = $this->list_tables($base_name.'_');
        $start = $this->get_finishing($start, $tables);
        $this->init_calendar($stop);

        //开始循环
        $order = [$this->get_sort_field() => 'DESC'];
        $result = ['count' => 0, 'maxpage' => 0, 'data' => []];
        while ($this->beginning >= $start) {
            $table = $this->table_name();
            //中间有表不存在，并不连续
            if (! in_array($table, $tables, true)) {
                $this->backward();
                continue;
            }
            //计算当前表行数
            if ($where) {
                $this->parse_where($where);
            }
            $curr_count = $this->count();
            $result['count'] += $curr_count;

            //数据已读
            if ($remain <= 0) {
                $this->backward();
                continue;
            }
            //未达到偏移位置，先消耗偏移量
            if ($offset > 0) { //偏移量
                $offset -= $curr_count;
                if ($offset >= 0) {
                    $this->backward();
                    continue;
                }
            }

            //第一次查询时需要计算剩余偏移量
            $offset = ($offset < 0) ? $offset + $curr_count : 0;
            if ($where) {
                $this->parse_where($where); //查询数据，倒序
            }
            $rows = $this->order_by($order)->all($remain, $offset, $fields);
            if (! empty($rows)) {
                $result['data'] = array_merge($result['data'], $rows);
                $remain -= count($rows); //扣除本次已读
            }
            $offset = 0; //过后偏移量清零
            $this->backward();
        }

        //计算最大页码
        if ($result['count'] > 0 && $limit > 0) {
            $result['maxpage'] = (int) ceil($result['count'] / $limit);
        }
        return $result;
    }

    /**
     * 在所有分表中查询数据.
     *
     * @param mixed      $where
     * @param mixed      $group
     * @param null|mixed $start
     * @param null|mixed $stop
     * @param null|mixed $order
     * @param mixed      $fields
     */
    public function group_more($where, $group, $start = null, $stop = null, $order = null, $fields = '*')
    {
        $base_name = $this->base_table_name();
        $tables = $this->list_tables($base_name.'_');
        $start = $this->get_finishing($start, $tables);
        $this->init_calendar($stop);

        $result = [];
        while ($this->beginning >= $start) {
            $table = $this->table_name();
            if (! in_array($table, $tables, true)) {
                $this->backward();
                continue;
            }
            if ($where) {
                $this->parse_where($where);
            }
            if ($group) {
                $this->group_by($group);
            }
            if ($order) {
                $this->order_by($order);
            }
            $rows = $this->all(null, 0, $fields);
            if (count($rows) > 0) {
                $result = array_merge($result, $rows);
            }
            $this->backward();
        }

        return $result;
    }

    /**
     * 更新记录.
     *
     * @param array/string/null $where
     * @param null|mixed $start
     * @param null|mixed $limit
     * @param null|mixed $escape
     *
     * @return int
     */
    public function update_more(array $set, $where, $start = null, $limit = null, $escape = null)
    {
        $base_name = $this->base_table_name();
        $tables = $this->list_tables($base_name.'_');
        $start = $this->get_finishing($start, $tables);
        $this->init_calendar();

        $total = 0;
        while ($this->beginning >= $start) {
            $table = $this->table_name();
            if (! in_array($table, $tables, true)) {
                $this->backward();
                continue;
            }
            $curr_count = $this->update_unsafe($set, $where, $limit, $escape);
            $total += $curr_count;
            if (is_numeric($limit)) { //数量限制
                $limit -= $curr_count;
                if ($limit <= 0) {
                    break;
                }
            }
            $this->backward();
        }

        return $total;
    }

    /**
     * 创建当前月份的数据表.
     *
     * @return bool
     */
    public function create_current_table()
    {
        $tpl = 'CREATE TABLE IF NOT EXISTS `%s` LIKE `%s`';
        $sql = sprintf($tpl, $this->table_name(), $this->base_table_name());
        $db = $this->reconnect();

        return $db->simple_query($sql);
    }

    /**
     * 写入一行.
     *
     * @param mixed      $row
     * @param mixed      $is_replace
     * @param null|mixed $escape
     *
     * @return int/null
     */
    public function insert($row, $is_replace = false, $escape = null)
    {
        $this->init_calendar();

        try {
            return $this->insert_unsafe($row, $is_replace, $escape);
        } catch (\Exception $e) {
            $this->create_current_table();

            return $this->insert_unsafe($row, $is_replace, $escape);
        }
    }
}
