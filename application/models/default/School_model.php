<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 学校
 */
class School_model extends MY_Model
{
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_schools';

    public function __construct()
    {
        parent::__construct();
        $this->add_cache('redis', 'test');
    }

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'city' => 'varchar',
            'name' => 'varchar',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function get_relations()
    {
        return [
            'students' => [
                'type' => FOREIGN_HAS_MANY,
                'model' => 'test/student_model',
                'fkey' => 'school_id',
                'columns' => ['id', 'name', 'gender',
                        'school_id', 'is_removed'],
            ],
        ];
    }

    public function cache_fields()
    {
        return [
            'id' => 'int',
            'city' => 'varchar',
            'name' => 'varchar',
            'is_removed' => 'tinyint',
        ];
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'school:' . $condition['id'];
    }
}
