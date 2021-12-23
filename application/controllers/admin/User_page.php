<?php

defined('BASEPATH') || exit('No direct script access allowed');

require_once dirname(__DIR__).'/Admin_page.php';

class User_page extends Admin_page
{
    public function index()
    {
        //http://www.trirand.com/jqgridwiki/doku.php?id=wiki:jqgriddocs
        $col_model = array(
            array(
                'title' => '序号',
                'name' => 'id',
                'index' => 'id',
                'editable' => false,
                'width' => 30,
                'sorttype' => 'int',
                'search' => true,
            ),
            array(
                'title' => '用户名',
                'name' => 'username',
                'index' => 'username',
                'editable' => false,
                'width' => 50,
                'sorttype' => 'string',
            ),
            array(
                'title' => '姓名',
                'name' => 'nickname',
                'index' => 'nickname',
                'editable' => true,
                'width' => 80,
            ),
            array(
                'title' => '性别',
                'name' => 'gender',
                'editable' => true,
                'width' => 30,
                'align' => 'center',
            ),
            array(
                'title' => '角色',
                'name' => 'role.title',
                'index' => 'role_id',
                'editable' => true,
                'width' => 50,
            ),
            array(
                'title' => '邮箱',
                'name' => 'email',
                'index' => 'email',
                'editable' => true,
                'width' => 150,
            ),
            array(
                'title' => '注册时间',
                'name' => 'created_at',
                'index' => 'created_at',
                'editable' => false,
                'width' => 100,
                'sorttype' => 'date',
                'sortable' => 'date',
            ),
        );
        $col_names = array_column($col_model, 'title');

        return array('col_names' => $col_names, 'col_model' => $col_model);
    }

    public function user_data()
    {
        $this->load->model('default/admin_model');
        $this->admin_model->with_foreign('role');
        $offset = $this->input->post('offset', 0);
        $columns = $this->admin_model->table_fields();
        unset($columns['password'], $columns['phone']);
        $this->admin_model->parse_select(array_keys($columns));
        $data = $this->admin_model->all(15, $offset);

        return $this->render_json($data);
    }
}
