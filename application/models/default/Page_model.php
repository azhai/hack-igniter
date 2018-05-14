<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Page_model
 */
class Page_model extends MY_Model
{
    use \Mylib\ORM\MY_Foreign;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_pages';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'slug' => 'varchar',
            'datecreated' => 'datetime',
            'datechanged' => 'datetime',
            'datepublish' => 'datetime',
            'datedepublish' => 'datetime',
            'ownerid' => 'int',
            'status' => 'varchar',
            'templatefields' => 'longtext',
            'title' => 'varchar',
            'image' => 'longtext',
            'teaser' => 'longtext',
            'body' => 'longtext',
            'template' => 'varchar',
        ];
    }

    public function get_relations()
    {
        return [
            'owner' => [
                'model' => 'default/user_model',
                'fkey' => 'ownerid',
            ],
        ];
    }
}
