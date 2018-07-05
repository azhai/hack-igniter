<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once dirname(__DIR__) . '/Admin_page.php';

class Entry_page extends Admin_page
{
    public static $gender_options = [
        'F' => ['color' => '#0569e2', 'title' => '女'],
        'M' => ['color' => '#7fa800', 'title' => '男'],
        'X' => ['color' => '#ca2300', 'title' => '未知'],
    ];

    protected function initialize()
    {
        parent::initialize();
        $this->load->helper('format');
        $this->load->model('default/admin_model');
        $this->admin_model->with_foreign('role');
    }

    protected function filter_where(&$model)
    {
        $conds = [];
        $keyword = $this->input->post_get('keyword');
        if ($conds['keyword'] = trim($keyword)) {
            $model->like('title', $conds['keyword']);
        }
        $gender = $this->input->post_get('gender');
        if ($conds['gender'] = trim($gender)) {
            $model->where('gender', $conds['gender']);
        }
        return $conds;
    }

    protected function join_foreigns(array $result)
    {
        foreach ($result as &$row) {
            unset($row['password']);
            $option = self::$gender_options[$row['gender']];
            $row['gender_title'] = $option['title'];
            $row['gender_color'] = $option['color'];
        }
        return $result;
    }

    public function index()
    {
        $this->admin_model->order_by('id', 'DESC');
        $result = $this->list_rows($this->admin_model);
        $result['gender_options'] = self::$gender_options;
        $result['layout'] = $this->input->is_ajax_request() ? 'bare' : 'base';
        $result['edit_url'] = $this->get_page_url('edit');

        // $this->load->model('default/account_model');
        // $this->account_model->increase_by_id(1, 3);

        // $this->load->model('default/role_privilege_model');
        // $this->role_privilege_model->group_order_by('role_id', 'menu_id', 'ASC');
        // $rows = $this->role_privilege_model->all();
        // var_dump($rows); exit;

        return $result;
    }

    public function edit()
    {
        $id = $this->input->post_get('id');
        $this->admin_model->where('id', $id);
        $result = $this->list_rows($this->admin_model);
        $result['the_row'] = $result['page_rows'] ? $result['page_rows'][0] : [];
        $result['gender_options'] = self::$gender_options;
        $result['layout'] = $this->input->is_ajax_request() ? 'bare' : 'base';
        $result['next_url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        return $result;
    }

    public function save()
    {
        $id = $this->input->post_get('id');
        $next_url = $this->input->post_get('next_url');
        $changes = $this->input->post();
        if ($id && $changes) {
            unset($changes['id'], $changes['spm'], $changes['next_url']);
            $this->admin_model->update($changes, ['id' => $id]);
            return redirect($next_url);
        } else {
            return $this->edit();
        }
    }
}