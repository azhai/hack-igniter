<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 管理后台基础控制器
 */
class Admin_page extends MY_Controller
{
    protected $login_url = '';
    protected $per_page = 10;   //每页显示多少条

    protected function get_globals()
    {
        $globals = parent::get_globals();
        $globals['layout_class'] = 'fixed-sidebar full-height-layout gray-bg';
        $globals['logout_url'] = str_replace('login', 'logout', $this->login_url);
        $globals['site_title'] = '测试网站';
        $globals['user'] = $this->session->userdata();
        $globals['menus'] = $globals['leaves'] = [];
        if ($role_id = $this->session->userdata('role_id')) {
            $is_super = $this->session->userdata('is_super');
            $menus = $this->get_menus($role_id, $is_super);
            @list($globals['menus'], $globals['leaves']) = $menus;
        }
        return $globals;
    }

    protected function initialize()
    {
        parent::initialize();
        $this->load->helper('admin_ui');
        $this->load->library('session');
        $this->login_url = defined('SITE_LOGIN_URL') ? SITE_LOGIN_URL : '';
        $login_action = $this->get_page_url('login', [], true);
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

    protected function get_menus($role_id, $is_revoked = 0)
    {
        $this->load->model('default/menu_model');
        $this->load->model('default/privilege_model');
        $this->load->model('default/role_privilege_model');
        $rows = $this->role_privilege_model->get_role_privs($role_id, $is_revoked);
        $menu_ids = array_column($rows, 'menu_id');
        $method = $is_revoked ? 'get_remain_menus' : 'get_grant_menus';
        list($menus, $branch_ids) = $this->menu_model->$method($menu_ids);
        $where = ['parent_id' => $branch_ids, 'is_removed' => 0];
        $rows = $this->menu_model->parse_where($where)->all();
        $leaves = array_fill_keys($branch_ids, []);
        foreach ($rows as $row) {
            if ($branch_id = $row['parent_id']) {
                $leaves[$branch_id][] = to_menu_link($row);
            }
        }
        return [$menus, $leaves];
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
