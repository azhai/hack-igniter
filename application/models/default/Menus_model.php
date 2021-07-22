<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 菜单
 */
class Menus_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_menus';

A PHP Error was encountered

Severity:    Notice
Message:     Undefined variable: _created_field
Filename:    /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
Line Number: 19

Backtrace:
File: /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
Line: 19
    function: _error_handler

	File: /home/ryan/projects/hack-igniter/application/libraries/MY_Templater.php
	Line: 214
	function: include

	File: /home/ryan/projects/hack-igniter/application/core/MY_Controller.php
	Line: 145
	function: render

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 141
	function: render_html

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 117
	function: write_model

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 80
	function: write_db_models

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 51
	function: create_models

	File: /home/ryan/projects/hack-igniter/application/helpers/my_helper.php
	Line: 87
	function: index

	File: /home/ryan/projects/hack-igniter/application/core/MY_Controller.php
	Line: 39
	function: exec_method_array

	File: /home/ryan/projects/hack-igniter/index.php
	Line: 95
	function: require_once


    protected  = 'created_at';

A PHP Error was encountered

Severity:    Notice
Message:     Undefined variable: _changed_field
Filename:    /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
Line Number: 22

Backtrace:
	File: /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
	Line: 22
	function: _error_handler

	File: /home/ryan/projects/hack-igniter/application/libraries/MY_Templater.php
	Line: 214
	function: include

	File: /home/ryan/projects/hack-igniter/application/core/MY_Controller.php
	Line: 145
	function: render

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 141
	function: render_html

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 117
	function: write_model

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 80
	function: write_db_models

	File: /home/ryan/projects/hack-igniter/application/controllers/gen/Model_page.php
	Line: 51
	function: create_models

	File: /home/ryan/projects/hack-igniter/application/helpers/my_helper.php
	Line: 87
	function: index

	File: /home/ryan/projects/hack-igniter/application/core/MY_Controller.php
	Line: 39
	function: exec_method_array

	File: /home/ryan/projects/hack-igniter/index.php
	Line: 95
	function: require_once


    protected  = 'changed_at';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'parent_id' => 'int',
            'title' => 'varchar',
            'url' => 'varchar',
            'icon' => 'varchar',
            'corner' => 'varchar',
            'is_virtual' => 'tinyint',
            'seqno' => 'tinyint',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }
}