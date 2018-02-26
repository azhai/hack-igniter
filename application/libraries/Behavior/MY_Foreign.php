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
    public $foreign_defs = null;  //关联定义
    public $foreign_data = [];  //关联表数据
    public $result_ids = [];    //当前表ID
    public $result_data = [];   //当前表数据

    /**
     * 找出表的第一个主键字段
     */
    public static function get_model_pkey($model)
    {
        $indexes = $model->table_indexes();
        $pkey = $indexes ? reset($indexes) : 'id';
        return $pkey;
    }

    /**
     * 根据外键猜测名称
     */
    public static function get_foreign_name($fkey, $is_singular = false)
    {
        if (ends_with($fkey, '_id')) {
            $rev_name = substr($fkey, 0, -3);
            if (!$is_singular) {
                $rev_name .= 's';
            }
            return $rev_name;
        }
    }

    /**
     * 设置fetch()第三个参数
     */
    public function set_foreign_third_param($name, &$third_param)
    {
        $relations = $this->parse_relations();
        $this->foreign_defs[$name]['third_param'] = &$third_param;
        return $this->foreign_defs[$name];
    }

    /**
     * 初始化关系
     */
    public function parse_relations()
    {
        if ($this->foreign_defs) {
            return $this->foreign_defs;
        }
        //使用ArrayObject而不是array，克隆时保持对原始对象foreign_defs属性的引用
        $this->foreign_defs = new \ArrayObject();
        $relations = $this->get_relations();
        foreach ($relations as $name => $rel) {
            if (!isset($rel['type'])) {
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
                if (!isset($rel['fkey'])) {
                    $rel['fkey'] = $name . '_id';
                }
            }
            $belong_fkeys[$name] = $rel['fkey'];
            if (FOREIGN_SELF_MODEL === $rel['model']) {
                if (FOREIGN_BELONGS_TO === $rel['type']) {
                    $rel['another_model'] = $this;
                } else {
                    $rel['another_model'] = clone $this;
                }
            } else {
                $alias = isset($rel['alias']) ? $rel['alias'] : '';
                $rel['another_model'] = $this->another_model($rel['model'], $alias);
                if (FOREIGN_MANY_TO_MANY === $rel['type']) {
                    $rel['middle_model'] = $this->another_model($rel['middle_model']);
                }
            }
            $this->foreign_defs[$name] = $rel;
        }
        return $this->foreign_defs;
    }

    /**
     * 从结果集中逐行提取外键
     */
    public function fetch_result()
    {
        //已处理过，或查询失败
        if (count($this->result_data) > 0) {
            return $this->result_data;
        } elseif (!$this->result) {
            return [];
        }
        //找出外键，设定好外链所在位置
        $pkey = self::get_model_pkey($this);
        $relations = $this->parse_relations();
        while ($row = $this->result->unbuffered_row('array')) {
            $id = $row[$pkey];
            $this->result_ids[] = $id;
            foreach ($relations as $name => $rel) {
                if (FOREIGN_BELONGS_TO === $rel['type']) {
                    $fkey = $rel['fkey'];
                    if (!isset($row[$fkey])) {
                        continue;
                    }
                    $fval = $row[$fkey];
                } else {
                    $fval = $row[$pkey];
                }
                if (isset($this->foreign_data[$name])) {
                    $this->foreign_data[$name][$fval] = [];
                    $row[$name] = &$this->foreign_data[$name][$fval];
                }
            }
            //设定好外链位置的一行
            $this->result_data[] = $row;
        }
        $this->result->free_result();
        if ($this->result_data) {
            $foreign_names = array_keys($this->foreign_data);
            foreach ($foreign_names as $name) {
                $this->fetch_foreign($name);
            }
        }
        return $this->result_data;
    }

    /**
     * 从关联表中查询BELONGS_TO数据
     */
    public static function fetch_belongs($model, $another, $name, &$frows = null)
    {
        if (!$model->foreign_data[$name]) {
            return []; //外键没有数据
        }
        $pkey = self::get_model_pkey($another);
        if (is_null($frows)) {
            //查询关联表数据
            $ids = array_keys($model->foreign_data[$name]);
            if (method_exists($another, 'get_in_array')) {
                $frows = $another->get_in_array($ids);
            } else {
                $frows = $another->some([$pkey => $ids]);
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
    public static function fetch_contains($model, $another, $name, $an_fkey = '')
    {
        $relations = $model->parse_relations();
        $fkey = $relations[$name]['fkey'];
        $has_one = (FOREIGN_HAS_ONE === $relations[$name]['type']);
        if (isset($relations[$name]['rev_name'])) {
            $rev_name = $relations[$name]['rev_name'];
        } else {
            $rev_name = self::get_foreign_name($fkey, $has_one);
        }
        if (method_exists($another, 'set_foreign_third_param')) {
            $another->set_foreign_third_param($rev_name, $model->result_data);
        }
        //查询关联表数据
        $ids = $model->result_ids;
        $frows = $another->some([$fkey => $ids]);
        $foreigns = &$model->foreign_data[$name];
        if ($an_fkey) {
            if (!property_exists($another, 'foreign_data')) {
                $another->foreign_data = [];
            }
            $another->foreign_data[$rev_name] = [];
        }
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
        $this->foreign_data = array_fill_keys($names, []);
        return $names;
    }

    /**
     * 从关联表中查询数据，填充到外链
     */
    public function fetch_foreign($name)
    {
        if (!$this->result_data) {
            return []; //本身没有数据
        }
        $relations = $this->parse_relations();
        if (!isset($relations[$name])) {
            return; //找不到外联关系
        }
        $rel = $relations[$name];
        if ($another = $rel['another_model']) {
            $method = $rel['fetch_method'];
            if (isset($rel['third_param'])) {
                return $this->$method($another, $name, $rel['third_param']);
            } else {
                return $this->$method($another, $name);
            }
        }
    }

    /**
     * 从关联表中查询BELONGS_TO数据
     */
    public function fetch_belongs_to($another, $name, &$frows = null)
    {
        return self::fetch_belongs($this, $another, $name, $frows);
    }

    /**
     * 从关联表中查询HAS_ONE/HAS_MANY数据
     */
    public function fetch_has_many($another, $name)
    {
        return self::fetch_contains($this, $another, $name);
    }

    /**
     * 从关联表中查询MANY_TO_MANY数据
     */
    public function fetch_many_to_many($another, $name)
    {
        $relations = $this->parse_relations();
        $fkey = $relations[$name]['fkey'];
        $an_fkey = $relations[$name]['another_fkey'];
        $middle_model = $relations[$name]['middle_model'];
        if (isset($relations[$name]['rev_name'])) {
            $rev_name = $relations[$name]['rev_name'];
        } else {
            $rev_name = self::get_foreign_name($fkey);
        }
        $frows = self::fetch_contains($this, $middle_model, $name, $an_fkey);
        return self::fetch_belongs($middle_model, $another, $rev_name);
    }
}
