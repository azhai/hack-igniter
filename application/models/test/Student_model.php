<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 学员.
 */
class Student_model extends MY_Model
{
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_students';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function table_indexes($another = false)
    {
        return array('id');
    }

    public function table_fields()
    {
        return array(
            'id' => 'int',
            'school_id' => 'int',
            'name' => 'varchar',
            'gender' => 'enum',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        );
    }

    public function get_relations()
    {
        return array(
            'school' => array(
                'model' => 'test/school_model',
                'columns' => array('id', 'city', 'name', 'is_removed'),
            ),
            'subjects' => array(
                'type' => FOREIGN_MANY_TO_MANY,
                'model' => 'test/subject_model',
                'rev_name' => 'students',
                'fkey' => 'student_id',
                'another_fkey' => 'subject_id',
                'middle_model' => 'test/score_model',
                'fetch_method' => 'fetch_last_foreign',
            ),
        );
    }
}
