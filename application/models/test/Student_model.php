<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 学员
 */
class Student_model extends MY_Model
{
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'test';
    protected $_db_key_ro = 'test_ro';
    protected $_table_name = 'students';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'school_id' => 'int',
            'name' => 'varchar',
            'gender' => 'enum',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function get_relations()
    {
        return [
            'school' => [
                'model' => 'test/school_model',
                'columns' => ['id', 'city', 'name', 'is_removed'],
            ],
            'subjects' => [
                'type' => FOREIGN_MANY_TO_MANY,
                'model' => 'test/subject_model',
                'rev_name' => 'students',
                'fkey' => 'student_id',
                'another_fkey' => 'subject_id',
                'middle_model' => 'test/score_model',
                'fetch_method' => 'fetch_last_foreign',
            ],
        ];
    }
}
