<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'models/mixins/User_mixin.php';


/**
 * User_model
 */
class User_model extends MY_Model
{
    use \User_mixin;

    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_users';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
              'id' => 'int',
              'username' => 'varchar',
              'password' => 'varchar',
              'email' => 'varchar',
              'lastseen' => 'datetime',
              'lastip' => 'varchar',
              'displayname' => 'varchar',
              'stack' => 'longtext',
              'enabled' => 'tinyint',
              'shadowpassword' => 'varchar',
              'shadowtoken' => 'varchar',
              'shadowvalidity' => 'datetime',
              'failedlogins' => 'int',
              'throttleduntil' => 'datetime',
              'roles' => 'longtext',
        ];
    }
}