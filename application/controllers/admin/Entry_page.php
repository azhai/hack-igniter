<?php
defined('BASEPATH') || exit('No direct script access allowed');
require_once dirname(__DIR__) . '/Admin_page.php';

class Entry_page extends Admin_page
{
    public static $city_options = [
        '粤C' => ['color' => '#7fa800', 'name' => '珠海市'],
        '粤B' => ['color' => '#0569e2', 'name' => '深圳市'],
        '粤A' => ['color' => '#ca2300', 'name' => '广州市'],
    ];

    protected function initialize()
    {
        parent::initialize();
        $this->load->helper('fmt');
        $this->load->model('test/school_model');
        //$this->school_model->with_foreign('city');
    }

    protected function filter_where(&$model)
    {
        $conds = [];
        $keyword = $this->input->post_get('keyword');
        if ($conds['keyword'] = trim($keyword)) {
            $model->like('name', $conds['keyword']);
        }
        $prefix = $this->input->post_get('prefix');
        if ($conds['prefix'] = trim($prefix)) {
            $model->where('prefix', $conds['prefix']);
        }
        return $conds;
    }

    protected function join_foreigns(array $result)
    {
        foreach ($result as &$row) {
            $option = self::$city_options[$row['prefix']];
            $row['city_name'] = $option['name'];
            $row['city_color'] = $row['is_removed'] ? '#ddd' : $option['color'];
        }
        return $result;
    }

    public function index()
    {
        $this->school_model->order_by('id', 'DESC');
        $result = $this->list_rows($this->school_model, ['id' => 'DESC']);
        $result['city_options'] = self::$city_options;
        $result['layout'] = $this->input->is_ajax_request() ? 'bare' : 'base';
        $result['next_url'] = $this->get_page_url('', $result['conds'], true);
        $result['edit_url'] = $this->get_page_url('edit', [], true);
        $result['remove_url'] = $this->get_page_url('remove', [], true);

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
        $this->school_model->where('id', $id);
        $result = $this->list_rows($this->school_model);
        $result['the_row'] = $result['page_rows'] ? $result['page_rows'][0] : [];
        $result['city_options'] = self::$city_options;
        $result['layout'] = $this->input->is_ajax_request() ? 'bare' : 'base';
        $result['save_url'] = $this->get_page_url('save', [], true);
        $result['next_url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        return $result;
    }

    public function save()
    {
        $id = $this->input->post_get('id');
        $changes = $this->input->post();
        if ($id && $changes) {
            unset($changes['id'], $changes['next_url']);
            $this->school_model->update($changes, ['id' => $id]);
            $next_url = $this->input->post_get('next_url');
        } else {
            $next_url = $this->get_page_url('edit', [], true);
        }
        return redirect($next_url);
    }

    public function remove()
    {
        $id = $this->input->post_get('id');
        $recycle = $this->input->post_get('recycle', 0);
        if ($ids = explode(',', trim($id, ', '))) {
            $method = $recycle ? 'undelete' : 'delete';
            $this->school_model->$method(['id' => $ids], count($ids));
            $next_url = $this->input->post_get('next_url');
        } else {
            $next_url = $this->get_page_url('index', [], true);
        }
        return redirect($next_url);
    }
}
