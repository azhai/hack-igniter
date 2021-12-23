<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 学校
 */
class School_model extends MY_Model
{
    use \Mylib\ORM\MY_Cache_hash;
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_schools';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'test');
    }

    public function table_indexes($another = false)
    {
        return array('id');
    }

    public function table_fields()
    {
        return array(
            'id' => 'int',
            'city' => 'varchar',
            'name' => 'varchar',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        );
    }

    public function get_relations()
    {
        return array(
            'students' => array(
                'type' => FOREIGN_HAS_MANY,
                'model' => 'test/student_model',
                'fkey' => 'school_id',
                'columns' => array('id', 'name', 'gender',
                    'school_id', 'is_removed', ),
            ),
        );
    }

    public function cache_fields()
    {
        return array(
            'id' => 'int',
            'city' => 'varchar',
            'name' => 'varchar',
            'is_removed' => 'tinyint',
        );
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        $ids = $condition['id'];
        if (! is_array($ids)) {
            return 'school:'.$ids;
        }

        return array_map(function ($x) {
            return 'school:'.$x;
        }, $ids);
    }
}
