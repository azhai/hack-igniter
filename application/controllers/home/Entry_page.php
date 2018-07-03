<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once __DIR__ . '/Admin_page.php';

class Entry_page extends Admin_page
{
    public static $enum_status = [
        'draft' => ['color' => '#0569e2', 'title' => '草稿'],
        'published' => ['color' => '#7fa800', 'title' => '发布'],
        'held' => ['color' => '#ca2300', 'title' => '保留'],
    ];

    protected function initialize()
    {
        parent::initialize();
        $this->load->helper('format');
        $this->load->model('default/entry_model');
        $this->entry_model->with_foreign('owner');
    }

    protected function filter_where(&$model)
    {
        $conds = [];
        $keyword = $this->input->post_get('keyword');
        if ($conds['keyword'] = trim($keyword)) {
            $model->like('title', $conds['keyword']);
        }
        $status = $this->input->post_get('status');
        if ($conds['status'] = trim($status)) {
            $model->where('status', $conds['status']);
        }
        return $conds;
    }

    protected function join_foreigns(array $result)
    {
        foreach ($result as &$row) {
            if (is_string($row['image'])) {
                $row['image'] = json_decode($row['image'], true);
            } else {
                $row['image'] = [];
            }
            if (!isset($row['image']['file'])) {
                $row['image']['file'] = '';
            }
            $enum = self::$enum_status[$row['status']];
            $row['status_title'] = $enum['title'];
            $row['status_color'] = $enum['color'];
        }
        return $result;
    }

    public function index()
    {
        $this->entry_model->order_by('id', 'DESC');
        $result = $this->list_rows($this->entry_model);
        $result['enum_status'] = self::$enum_status;
        $result['layout'] = $this->input->is_ajax_request() ? 'bare' : 'base';
        $result['edit_url'] = $this->get_page_url('edit');

        // $this->load->model('default/account_model');
        // $this->account_model->increase_by_id(1, 3);

        // $this->load->model('default/privilege_model');
        // $node = $this->privilege_model->get_node_by_id(9);
        // var_dump($node); exit;

        $this->load->model('default/role_privilege_model');
        $this->role_privilege_model->group_order_by('role_id', 'menu_id', 'ASC');
        $rows = $this->role_privilege_model->all();
        var_dump($rows); exit;

        return $result;
    }

    public function edit()
    {
        $id = $this->input->post_get('id');
        $this->entry_model->where('id', $id);
        $result = $this->list_rows($this->entry_model);
        $result['the_row'] = $result['page_rows'] ? $result['page_rows'][0] : [];
        $result['enum_status'] = self::$enum_status;
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
            $this->entry_model->update($changes, ['id' => $id]);
            return redirect($next_url);
        } else {
            return $this->edit();
        }
    }
}
