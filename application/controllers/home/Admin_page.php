<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_page extends MY_Controller
{
    protected $login_url = '';
    protected $per_page = 10;   //每页显示多少条

    protected function get_globals()
    {
        $globals = parent::get_globals();
        $globals['layout_class'] = '';
        $globals['logout_url'] = str_replace('login', 'logout', $this->login_url);
        $globals['site_title'] = '测试网站';
        $globals['menus'] = [
            'menu_1' => '菜单1',
            'menu_2' => '菜单2',
            'menu_3' => '菜单3',
        ];
        return $globals;
    }

    protected function initialize()
    {
        parent::initialize();
        $this->load->library('session');
        $this->login_url = defined('SITE_LOGIN_URL') ? SITE_LOGIN_URL : '';
        $login_action = $this->get_page_url('login');
        $username = $this->is_authed();
        if (empty($username) && $login_action !== $this->login_url) {
            return redirect($this->base_url . $this->login_url);
        }
    }

    protected function is_authed()
    {
        if ($this->session->has_userdata('username')) {
            return $this->session->userdata('username');
        }
    }

    protected function filter_where(& $model)
    {
        $conds = [];
        return $conds;
    }

    protected function join_foreigns(array $result)
    {
        return $result;
    }

    protected function list_rows(& $model)
    {
        $spm = $this->input->post_get('spm');
        $page_no = $this->input->post_get('page_no');
        $page_no = ($page_no && $page_no > 0) ? intval($page_no) : 1;
        $offset = ($page_no - 1) * $this->per_page;
        $conds = $this->filter_where($model);
        $total_rows = $model->count('*', false);
        $conds['spm'] = $spm;
        $pager = array(
            'base_url' => $this->get_page_url('list', $conds),
            'total_rows' => $total_rows,
            'page_no' => $page_no,
            'page_max' => ceil($total_rows / $this->per_page),
            'per_page' => $this->per_page,
        );
        $model->order_by('id', 'DESC');
        $page_rows = $model->all($this->per_page, $offset);
        return [
            'spm' => $spm, 'conds' => $conds, 'pager' => $pager,
            'page_rows' => $this->join_foreigns($page_rows),
            'export_url' => $this->get_page_url('export'),
        ];
    }
}
