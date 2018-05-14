<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Field_value_model
 */
class Field_value_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_field_value';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'contenttype' => 'varchar',
            'content_id' => 'int',
            'name' => 'varchar',
            'grouping' => 'int',
            'block' => 'varchar',
            'fieldname' => 'varchar',
            'fieldtype' => 'varchar',
            'value_string' => 'varchar',
            'value_text' => 'longtext',
            'value_integer' => 'int',
            'value_float' => 'double',
            'value_decimal' => 'decimal',
            'value_date' => 'date',
            'value_datetime' => 'datetime',
            'value_json_array' => 'longtext',
            'value_boolean' => 'tinyint',
        ];
    }
}
