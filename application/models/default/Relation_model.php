<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Relation_model
 */
class Relation_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_relations';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
              'id' => 'int',
              'from_contenttype' => 'varchar',
              'from_id' => 'int',
              'to_contenttype' => 'varchar',
              'to_id' => 'int',
        ];
    }
}