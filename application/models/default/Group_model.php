<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 用户组
 */
class Group_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_group';

A PHP Error was encountered

Severity:    Notice
Message:     Undefined variable: _created_field
Filename:    /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
Line Number: 19

Backtrace:
	File: /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
	Line: 19
	Function: _error_handler

	File: /home/ryan/projects/hack-igniter/application/libraries/MY_Templater.php
	Line: 214
	Function: include

	File: /home/ryan/projects/hack-igniter/application/core/MY_Controller.php
	Line: 145
	Function: render

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 141
	Function: render_html

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 117
	Function: write_model

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 80
	Function: write_db_models

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 51
	Function: create_models

	File: /home/ryan/projects/hack-igniter/application/helpers/my_helper.php
	Line: 87
	Function: index

	File: /home/ryan/projects/hack-igniter/application/core/MY_Controller.php
	Line: 39
	Function: exec_method_array

	File: /home/ryan/projects/hack-igniter/index.php
	Line: 95
	Function: require_once


    protected  = 'created_at';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'parent_id' => 'int',
            'gid' => 'char',
            'title' => 'varchar',
            'sort_no' => 'smallint',
            'enable' => 'tinyint',
            'remark' => 'text',
            'created_at' => 'timestamp',
        ];
    }
}