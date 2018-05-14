<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 成绩
 */
class Score_model extends MY_Model
{
    protected $_db_key = 'test';
    protected $_db_key_ro = 'test';
    protected $_table_name = 'scores';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'subject_id' => 'int',
            'student_id' => 'int',
            'term' => 'varchar',
            'score' => 'smallint',
            'is_removed' => 'tinyint',
        ];
    }
}
