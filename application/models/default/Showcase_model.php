<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'models/mixins/Showcase_mixin.php';


/**
 * Showcase_model
 */
class Showcase_model extends MY_Model
{
    use \Showcase_mixin;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_showcases';

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
              'html' => 'longtext',
              'textarea' => 'longtext',
              'markdown' => 'longtext',
              'geolocation' => 'longtext',
              'video' => 'longtext',
              'image' => 'longtext',
              'imagelist' => 'longtext',
              'file' => 'varchar',
              'filelist' => 'longtext',
              'checkbox' => 'tinyint',
              'datetime' => 'datetime',
              'date' => 'date',
              'integerfield' => 'int',
              'floatfield' => 'double',
              'selectfield' => 'longtext',
              'multiselect' => 'longtext',
              'selectentry' => 'longtext',
        ];
    }
}