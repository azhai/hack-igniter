<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Taxonomy_model
 */
class Taxonomy_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_taxonomy';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'content_id' => 'int',
            'contenttype' => 'varchar',
            'taxonomytype' => 'varchar',
            'slug' => 'varchar',
            'name' => 'varchar',
            'sortorder' => 'int',
        ];
    }
}
