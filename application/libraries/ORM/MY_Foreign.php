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

$loader = load_class('Loader', 'core');
$loader->helper('inflector');

defined('FOREIGN_BELONGS_TO') or define('FOREIGN_BELONGS_TO', 0);
defined('FOREIGN_HAS_ONE') or define('FOREIGN_HAS_ONE', 1);
defined('FOREIGN_HAS_MANY') or define('FOREIGN_HAS_MANY', 2);
defined('FOREIGN_MANY_TO_MANY') or define('FOREIGN_MANY_TO_MANY', 3);
defined('FOREIGN_SELF_MODEL') or define('FOREIGN_SELF_MODEL', '**SELF**');


/**
 * 扩展Model，定义四种外键关联
 */
trait MY_Foreign
{
    public $foreign_defs = null;    //关联定义
    public $foreign_names = [];     //关联名
    public $foreign_data = [];      //关联表数据
    public $result_ids = [];        //当前表ID
    public $result_data = [];       //当前表数据
    public $except_empty = true;    //不查询为0的外键

    public function __clone()
    {
        $fields = $this->table_fields();
        foreach ($fields as $field) {
            unset($this[$field]);
        }
        $this->foreign_names = [];
        $this->foreign_data = [];
        $this->result_ids = [];
        $this->result_data = [];
    }

    /**
     * 根据外键猜测名称
     */
    public static function get_foreign_name($fkey, $many_to_many = false)
    {
        if (ends_with($fkey, '_id')) {
            $rev_name = substr($fkey, 0, -3);
            if ($many_to_many) {
                $rev_name = plural($rev_name);
            }
            return $rev_name;
        }
    }

    /**
     * 决定要查询的字段
     */
    public static function get_foreign_columns($model, array $rel = [])
    {
        if (isset($rel['columns'])) {
            return $rel['columns'];
        } elseif ($model->is_open_mixin('cacheable')) {
            return array_keys($model->cache_fields());
        } else {
            return '*';
        }
    }

    /**
     * 设置fetch()第三个参数
     */
    public function set_foreign_third_param($name, &$third_param)
    {
        if ($rel = $this->parse_relation($name)) {
            $this->foreign_defs[$name]['third_param'] = &$third_param;
            return $this->foreign_defs[$name];
        }
    }

    /**
     * 设置要读取的关联数据
     */
    public function with_foreign($name = '*')
    {
        if ('*' === $name) {
            $relations = $this->get_relations();
            $names = array_keys($relations);
        } elseif (is_array($name)) {
            $names = array_values($name);
        } else {
            $names = array_filter(func_get_args());
        }
        if ($this->foreign_names = $names) {
            $this->foreign_data = array_fill_keys($names, []);
            $this->_mixin_switches['foreign'] = true;
        }
        return $this;
    }

    /**
     * 从关联表中查询数据，填充到外链
     */
    public function fetch_foreign($name, array $rel = [])
    {
        if (!$this->result_data) {
            return []; //本身没有数据
        }
        if (!$rel) {
            $rel = $this->parse_relation($name, $rel);
        }
        if ($rel && $another = $rel['another_model']) {
            $method = $rel['fetch_method'];
            return $this->$method($another, $name, $rel);
        }
    }

    /**
     * 初始化关系
     */
    public function parse_relation($name, array $rel = [])
    {
        if (is_null($this->foreign_defs)) {
            //使用ArrayObject而不是array，克隆时保持对原始对象foreign_defs属性的引用
            $this->foreign_defs = new \ArrayObject();
        }
        if (!$rel && isset($this->foreign_defs[$name])) {
            return $this->foreign_defs[$name];
        }
        if (!$rel) {
            $relations = $this->get_relations();
            if (!isset($relations[$name])) {
                return [];
            }
            $rel = $relations[$name];
        }
        $rel = $this->fill_relation($rel);
        if (FOREIGN_BELONGS_TO === $rel['type']) {
            if (!isset($rel['fkey'])) {
                $rel['fkey'] = $name . '_id';
            }
        }
        $this->foreign_defs[$name] = $rel;
        return $rel;
    }

    /**
     * 补充关系字段
     */
    public function fill_relation(array $rel)
    {
        if (!isset($rel['type']) || !$rel['type']) {
            $rel['type'] = FOREIGN_BELONGS_TO;
        }
        if (FOREIGN_HAS_ONE === $rel['type']) {
            $rel['fetch_method'] = 'fetch_has_many';
        } elseif (FOREIGN_HAS_MANY === $rel['type']) {
            $rel['fetch_method'] = 'fetch_has_many';
        } elseif (FOREIGN_MANY_TO_MANY === $rel['type']) {
            $rel['fetch_method'] = 'fetch_many_to_many';
        } else {
            $rel['fetch_method'] = 'fetch_belongs_to';
        }
        if (FOREIGN_SELF_MODEL === $rel['model']) {
            $rel['another_model'] = clone $this;
        } else {
            $alias = isset($rel['alias']) ? $rel['alias'] : '';
            $rel['another_model'] = $this->another_model($rel['model'], $alias);
            if (FOREIGN_MANY_TO_MANY === $rel['type']) {
                $rel['middle_model'] = $this->another_model($rel['middle_model']);
            }
        }
        return $rel;
    }

    /**
     * 从结果集中逐行提取外键
     */
    public function fetch_result()
    {
        //已处理过，或查询失败
        if (!$this->result) {
            return [];
        }
        $this->result_ids = [];
        $this->result_data = [];
        //找出外键，设定好外链所在位置
        $pkey = $this->primary_key();
        $relations = [];
        while ($row = $this->result->unbuffered_row('array')) {
            $id = $row[$pkey];
            $this->result_ids[] = $id;
            foreach ($this->foreign_names as $name) {
                $relations[$name] = $this->parse_relation($name);
                if (FOREIGN_BELONGS_TO === $relations[$name]['type']) {
                    $fkey = $relations[$name]['fkey'];
                    if (!$fkey || !isset($row[$fkey])) {
                        continue;
                    } elseif ($this->except_empty && !$row[$fkey]) {
                        continue;
                    }
                    $fval = $row[$fkey];
                } else {
                    $fval = $row[$pkey];
                }
                $this->foreign_data[$name][$fval] = [];
                $row[$name] = &$this->foreign_data[$name][$fval];
            }
            //设定好外链位置的一行
            $this->result_data[] = $row;
        }
        $this->result->free_result();
        if ($this->result_data) {
            foreach ($relations as $name => $rel) {
                $this->fetch_foreign($name, $rel);
            }
        }
        return $this->result_data;
    }

    /**
     * 从关联表中查询BELONGS_TO数据
     */
    public static function fetch_belongs(
        $model,
        $another,
        $name,
        $frows = null,
        $columns = '*'
    ) {
        if (!$model->foreign_data[$name]) {
            return []; //外键没有数据
        }
        $pkey = $another->primary_key();
        if (is_null($frows)) {
            //查询关联表数据
            $ids = array_keys($model->foreign_data[$name]);
            if ($another->is_open_mixin('cacheable')) {
                $frows = $another->get_in_array($ids);
            } else {
                $another->parse_where([$pkey => $ids]);
                $frows = $another->all(null, 0, $columns);
            }
        }
        $foreigns = &$model->foreign_data[$name];
        foreach ($frows as $row) {
            if (isset($row[$pkey])) {
                $fval = $row[$pkey];
                if (isset($foreigns[$fval]) && $foreigns[$fval]) {
                    $foreigns[$fval] = array_merge($row, $foreigns[$fval]);
                } else {
                    $foreigns[$fval] = $row;
                }
            }
        }
        return $frows;
    }

    /**
     * 从关联表中查询HAS_ONE/HAS_MANY数据
     */
    public static function fetch_contains(
        $model,
        $another,
        $name,
        array $rel = [],
        $columns = '*'
    ) {
        $fkey = $rel['fkey'];
        if (isset($rel['rev_name'])) {
            $rev_name = $rel['rev_name'];
        } else {
            $many_to_many = (FOREIGN_MANY_TO_MANY === $rel['type']);
            $rev_name = self::get_foreign_name($fkey, $many_to_many);
        }
        if (method_exists($another, 'set_foreign_third_param')) {
            $another->set_foreign_third_param($rev_name, $model->result_data);
        }
        //查询关联表数据
        $ids = $model->result_ids;
        $another->parse_where([$fkey => $ids]);
        $frows = $another->all(null, 0, $columns);
        $foreigns = &$model->foreign_data[$name];
        $an_fkey = isset($rel['another_fkey']) ? $rel['another_fkey'] : null;
        if ($an_fkey) {
            if (!property_exists($another, 'foreign_data')) {
                $another->foreign_data = [];
            }
            $another->foreign_data[$rev_name] = [];
        }
        $has_one = (FOREIGN_HAS_ONE === $rel['type']);
        foreach ($frows as $row) {
            if (isset($row[$fkey])) {
                $fval = $row[$fkey];
                if ($has_one) {
                    $foreigns[$fval] = $row;
                } elseif ($an_fkey && isset($row[$an_fkey])) {
                    $an_fval = $row[$an_fkey];
                    $another->foreign_data[$rev_name][$an_fval] = ['middle' => $row];
                    $foreigns[$fval][] = &$another->foreign_data[$rev_name][$an_fval];
                } else {
                    $foreigns[$fval][] = $row;
                }
            }
        }
        return $frows;
    }

    /**
     * 从关联表中查询BELONGS_TO数据
     */
    public function fetch_belongs_to($another, $name, array $rel = [])
    {
        $frows = isset($rel['third_param']) ? $rel['third_param'] : null;
        $columns = self::get_foreign_columns($another, $rel);
        return self::fetch_belongs($this, $another, $name, $frows, $columns);
    }

    /**
     * 从关联表中查询HAS_ONE/HAS_MANY数据
     */
    public function fetch_has_many($another, $name, array $rel = [])
    {
        $columns = self::get_foreign_columns($another, $rel);
        return self::fetch_contains($this, $another, $name, $rel, $columns);
    }

    /**
     * 从关联表中查询HAS_MANY中最后一行
     */
    public function fetch_last_foreign($another, $name, array $rel = [])
    {
        $fkey = $rel['fkey'];
        $another->group_order_by($fkey, '', 'DESC')->order_by($fkey);
        $columns = self::get_foreign_columns($another, $rel);
        // $result = yield self::fetch_contains($this, $another, $name, $rel, $columns);
        $result = self::fetch_contains($this, $another, $name, $rel, $columns);
        return $result;
    }

    /**
     * 从关联表中查询MANY_TO_MANY数据
     */
    public function fetch_many_to_many($another, $name, array $rel = [])
    {
        $middle_model = $rel['middle_model'];
        if (isset($rel['rev_name'])) {
            $rev_name = $rel['rev_name'];
        } else {
            $rev_name = self::get_foreign_name($rel['fkey']);
        }
        $frows = self::fetch_contains($this, $middle_model, $name, $rel, '*');
        $columns = self::get_foreign_columns($another, $rel);
        return self::fetch_belongs($middle_model, $another, $rev_name, null, $columns);
    }
}
