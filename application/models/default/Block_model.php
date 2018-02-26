<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'models/mixins/Block_mixin.php';


/**
 * Block_model
 */
class Block_model extends MY_Model
{
    use \Block_mixin;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_blocks';

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
              'content' => 'longtext',
              'contentlink' => 'varchar',
              'image' => 'longtext',
        ];
    }
}