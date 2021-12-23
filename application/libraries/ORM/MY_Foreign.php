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

$loader = load_class('Loader', 'core');
$loader->helper('inflector');

\defined('FOREIGN_BELONGS_TO') || \define('FOREIGN_BELONGS_TO', 0);
\defined('FOREIGN_HAS_ONE') || \define('FOREIGN_HAS_ONE', 1);
\defined('FOREIGN_HAS_MANY') || \define('FOREIGN_HAS_MANY', 2);
\defined('FOREIGN_MANY_TO_MANY') || \define('FOREIGN_MANY_TO_MANY', 3);
\defined('FOREIGN_SELF_MODEL') || \define('FOREIGN_SELF_MODEL', '**SELF**');

/**
 * 扩展Model，定义四种外键关联.
 */
trait MY_Foreign
{
    public $foreign_defs;    //关联定义
    public $foreign_names = array();     //关联名
    public $foreign_data = array();      //关联表数据
    public $result_ids = array();        //当前表ID
    public $result_data = array();       //当前表数据
    public $except_empty = true;    //不查询为0的外键

    public function __clone()
    {
        $fields = $this->table_fields();
        foreach ($fields as $field) {
            unset($this[$field]);
        }
        $this->foreign_names = array();
        $this->foreign_data = array();
        $this->result_ids = array();
        $this->result_data = array();
    }

    /**
     * 根据外键猜测名称.
     *
     * @param mixed $fkey
     * @param mixed $many_to_many
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
     * 决定要查询的字段.
     *
     * @param mixed $model
     */
    public static function get_foreign_columns($model, array $rel = array())
    {
        if (isset($rel['columns'])) {
            return $rel['columns'];
        }
        if ($model->is_open_mixin('cacheable')) {
            return array_keys($model->cache_fields());
        }

        return '*';
    }

    /**
     * 设置fetch()第三个参数.
     *
     * @param mixed $name
     * @param mixed $third_param
     */
    public function set_foreign_third_param($name, &$third_param)
    {
        if ($rel = $this->parse_relation($name)) {
            $this->foreign_defs[$name]['third_param'] = &$third_param;

            return $this->foreign_defs[$name];
        }
    }

    /**
     * 设置要读取的关联数据.
     *
     * @param mixed $name
     */
    public function with_foreign($name = '*')
    {
        if ('*' === $name) {
            $relations = $this->get_relations();
            $names = array_keys($relations);
        } elseif (\is_array($name)) {
            $names = array_values($name);
        } else {
            $names = array_filter(\func_get_args());
        }
        if ($this->foreign_names = $names) {
            $this->foreign_data = array_fill_keys($names, array());
            $this->_mixin_switches['foreign'] = true;
        }

        return $this;
    }

    /**
     * 从关联表中查询数据，填充到外链.
     *
     * @param mixed $name
     */
    public function fetch_foreign($name, array $rel = array())
    {
        if (! $this->result_data) {
            return array(); //本身没有数据
        }
        if (! $rel) {
            $rel = $this->parse_relation($name, $rel);
        }
        if ($rel && $another = $rel['another_model']) {
            $method = $rel['fetch_method'];

            return $this->{$method}($another, $name, $rel);
        }
    }

    /**
     * 初始化关系.
     *
     * @param mixed $name
     */
    public function parse_relation($name, array $rel = array())
    {
        if (null === $this->foreign_defs) {
            //使用ArrayObject而不是array，克隆时保持对原始对象foreign_defs属性的引用
            $this->foreign_defs = new \ArrayObject();
        }
        if (! $rel && isset($this->foreign_defs[$name])) {
            return $this->foreign_defs[$name];
        }
        if (! $rel) {
            $relations = $this->get_relations();
            if (! isset($relations[$name])) {
                return array();
            }
            $rel = $relations[$name];
        }
        $rel = $this->fill_relation($rel);
        if (FOREIGN_BELONGS_TO === $rel['type']) {
            if (! isset($rel['fkey'])) {
                $rel['fkey'] = $name.'_id';
            }
        }
        $this->foreign_defs[$name] = $rel;

        return $rel;
    }

    /**
     * 补充关系字段.
     */
    public function fill_relation(array $rel)
    {
        if (! isset($rel['type']) || ! $rel['type']) {
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
     * 从结果集中逐行提取外键.
     */
    public function fetch_result()
    {
        //已处理过，或查询失败
        if (! $this->result) {
            return array();
        }
        $this->result_ids = array();
        $this->result_data = array();
        //找出外键，设定好外链所在位置
        $pkey = $this->primary_key();
        $relations = array();
        while ($row = $this->result->unbuffered_row('array')) {
            $id = $row[$pkey];
            $this->result_ids[] = $id;
            foreach ($this->foreign_names as $name) {
                $relations[$name] = $this->parse_relation($name);
                if (FOREIGN_BELONGS_TO === $relations[$name]['type']) {
                    $fkey = $relations[$name]['fkey'];
                    if (! $fkey || ! isset($row[$fkey])) {
                        continue;
                    }
                    if ($this->except_empty && ! $row[$fkey]) {
                        continue;
                    }
                    $fval = $row[$fkey];
                } else {
                    $fval = $row[$pkey];
                }
                $this->foreign_data[$name][$fval] = array();
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
     * 从关联表中查询BELONGS_TO数据.
     *
     * @param mixed      $model
     * @param mixed      $another
     * @param mixed      $name
     * @param null|mixed $frows
     * @param mixed      $columns
     */
    public static function fetch_belongs(
        $model,
        $another,
        $name,
        $frows = null,
        $columns = '*'
    ) {
        if (! $model->foreign_data[$name]) {
            return array(); //外键没有数据
        }
        $pkey = $another->primary_key();
        if (null === $frows) {
            //查询关联表数据
            $ids = array_keys($model->foreign_data[$name]);
            if ($another->is_open_mixin('cacheable')) {
                $frows = $another->get_in_array($ids);
            } else {
                $another->parse_where(array($pkey => $ids));
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
     * 从关联表中查询HAS_ONE/HAS_MANY数据.
     *
     * @param mixed $model
     * @param mixed $another
     * @param mixed $name
     * @param mixed $columns
     */
    public static function fetch_contains(
        $model,
        $another,
        $name,
        array $rel = array(),
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
        $another->parse_where(array($fkey => $ids));
        $frows = $another->all(null, 0, $columns);
        $foreigns = &$model->foreign_data[$name];
        $an_fkey = isset($rel['another_fkey']) ? $rel['another_fkey'] : null;
        if ($an_fkey) {
            if (! property_exists($another, 'foreign_data')) {
                $another->foreign_data = array();
            }
            $another->foreign_data[$rev_name] = array();
        }
        $has_one = (FOREIGN_HAS_ONE === $rel['type']);
        foreach ($frows as $row) {
            if (isset($row[$fkey])) {
                $fval = $row[$fkey];
                if ($has_one) {
                    $foreigns[$fval] = $row;
                } elseif ($an_fkey && isset($row[$an_fkey])) {
                    $an_fval = $row[$an_fkey];
                    $another->foreign_data[$rev_name][$an_fval] = array('middle' => $row);
                    $foreigns[$fval][] = &$another->foreign_data[$rev_name][$an_fval];
                } else {
                    $foreigns[$fval][] = $row;
                }
            }
        }

        return $frows;
    }

    /**
     * 从关联表中查询BELONGS_TO数据.
     *
     * @param mixed $another
     * @param mixed $name
     */
    public function fetch_belongs_to($another, $name, array $rel = array())
    {
        $frows = isset($rel['third_param']) ? $rel['third_param'] : null;
        $columns = self::get_foreign_columns($another, $rel);

        return self::fetch_belongs($this, $another, $name, $frows, $columns);
    }

    /**
     * 从关联表中查询HAS_ONE/HAS_MANY数据.
     *
     * @param mixed $another
     * @param mixed $name
     */
    public function fetch_has_many($another, $name, array $rel = array())
    {
        $columns = self::get_foreign_columns($another, $rel);

        return self::fetch_contains($this, $another, $name, $rel, $columns);
    }

    /**
     * 从关联表中查询HAS_MANY中最后一行.
     *
     * @param mixed $another
     * @param mixed $name
     */
    public function fetch_last_foreign($another, $name, array $rel = array())
    {
        $fkey = $rel['fkey'];
        $another->group_order_by($fkey, '', 'DESC')->order_by($fkey);
        $columns = self::get_foreign_columns($another, $rel);
        // $result = yield self::fetch_contains($this, $another, $name, $rel, $columns);
        return self::fetch_contains($this, $another, $name, $rel, $columns);
    }

    /**
     * 从关联表中查询MANY_TO_MANY数据.
     *
     * @param mixed $another
     * @param mixed $name
     */
    public function fetch_many_to_many($another, $name, array $rel = array())
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
