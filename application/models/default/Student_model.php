<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 学员
 */
class Student_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default';
    protected $_table_name = 't_students';

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

A PHP Error was encountered

Severity:    Notice
Message:     Undefined variable: _changed_field
Filename:    /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
Line Number: 22

Backtrace:
	File: /home/ryan/projects/hack-igniter/application/controllers/gen/views/model/template.php
	Line: 22
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


    protected  = 'changed_at';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'school_id' => 'int',
            'name' => 'varchar',
            'gender' => 'enum',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }
}