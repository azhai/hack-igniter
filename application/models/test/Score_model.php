<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 成绩.
 */
class Score_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_scores';

    public function table_indexes($another = false)
    {
        return array('id');
    }

    public function table_fields()
    {
        return array(
            'id' => 'int',
            'subject_id' => 'int',
            'student_id' => 'int',
            'term' => 'varchar',
            'score' => 'smallint',
            'is_removed' => 'tinyint',
        );
    }
}
