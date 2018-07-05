<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 管理后台基础控制器
 */
class Admin_page extends MY_Controller
{
    protected $per_page = 10;   //每页显示多少条

    protected function get_globals()
    {
        $globals = parent::get_globals();
        $globals['layout_class'] = 'fixed-sidebar full-height-layout gray-bg';
        $globals['site_title'] = '测试网站';
        $globals['user'] = $globals['menus'] = $globals['leaves'] = [];
        if ($this->is_authed()) {
            $globals['user'] = $this->session->userdata();
            $role_id = $this->session->userdata('role_id');
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
        $login_url = defined('SITE_LOGIN_URL') ? SITE_LOGIN_URL : '';
        $logout_url = str_replace('login', 'logout', $login_url);
        $login_action = $this->get_page_url('login', [], true);
        $username = $this->is_authed();
        if (empty($username) && $login_action !== $login_url) {
            return redirect($this->base_url . $logout_url);
        }
    }

    public function goto_action($action = 'index')
    {
        $page_url = $this->get_page_url($action, [], true);
        return redirect($this->base_url . $page_url);
    }

    public function is_authed()
    {
        if ($this->session->has_userdata('username')) {
            return $this->session->userdata('username');
        }
    }

    public function has_perm($menu_id, $operation = 'all')
    {
        if ($this->is_authed()) {
            $role_id = $this->session->userdata('role_id');
            $is_super = $this->session->userdata('is_super');
            return $this->role_has_privilege($role_id, $is_super, $menu_id, $operation);
        }
    }

    protected function get_menus($role_id, $is_revoked = 0)
    {
        $this->load->model('default/menu_model');
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

    protected function role_has_privilege($role_id, $is_revoked = 0, $menu_id = 0, $operation = 'all')
    {
        $this->load->model('default/menu_model');
        $this->load->model('default/privilege_model');
        $this->load->model('default/role_privilege_model');

        $rows = $this->menu_model->get_menu_rows([$menu_id, ]);
        $menu_ids = array_column($rows, 'id');
        array_unshift($menu_ids, 0);
        $priv_ids = [];
        $where = ['menu_id' => $menu_ids, 'operation' => $operation, 'is_removed' => 0];
        $row = $this->privilege_model->order_by('menu_id', 'DESC')->one($where);
        if ($row && $row['id']) {
            $node = $this->privilege_model->get_node_by_id($row['id']);
            $priv_ids = $node->get_self_parent_ids();
        }

        $rows = [];
        if ($menu_ids && $priv_ids) {
            $where = [
                'role_id' => $role_id, 'is_revoked' => $is_revoked,
                'menu_id' => $menu_ids, 'privilege_id' => $priv_ids,
            ];
            $rows = $this->role_privilege_model->parse_where($where)->all();
        }
        $has_priv = (count($rows) > 0) ? 1 : 0;
        return $is_revoked ^ $has_priv;
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