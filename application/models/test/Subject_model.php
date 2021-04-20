<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 科目
 */
class Subject_model extends MY_Model
{
    use \Mylib\ORM\MY_Cacheable;
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_subjects';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

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
            'parent_id' => 'int',
            'name' => 'varchar',
            'max_score' => 'smallint',
            'pass_score' => 'smallint',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function get_relations()
    {
        return [
            'parent' => [
                'model' => FOREIGN_SELF_MODEL,
                'columns' => ['id', 'parent_id', 'name',
                        'max_score', 'pass_score', 'is_removed']
            ],
            'children' => [
                'type' => FOREIGN_HAS_MANY,
                'model' => FOREIGN_SELF_MODEL,
                'rev_name' => 'parent',
                'fkey' => 'parent_id',
                'columns' => ['id', 'parent_id', 'name',
                        'max_score', 'pass_score', 'is_removed']
            ],
            'students' => [
                'type' => FOREIGN_MANY_TO_MANY,
                'model' => 'test/student_model',
                'rev_name' => 'subjects',
                'fkey' => 'subject_id',
                'another_fkey' => 'student_id',
                'middle_model' => 'test/score_model',
            ],
        ];
    }

    public function cache_fields()
    {
        return [
            'id' => 'int',
            'parent_id' => 'int',
            'name' => 'varchar',
            'max_score' => 'smallint',
            'pass_score' => 'smallint',
            'is_removed' => 'tinyint',
        ];
    }

    public function cache_type()
    {
        return 'hash';
    }

    public function cache_key($condition)
    {
        return 'subject:' . $condition['id'];
    }
}
