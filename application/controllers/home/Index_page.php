<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once __DIR__ . '/Admin_page.php';


class Index_page extends Admin_page
{
    public function index()
    {
        $main_url = $this->base_url . '/home/admin/';
        return redirect($main_url);
    }

    public function login()
    {
        if ('post' === $this->request_method) {
            $this->load->model('default/admin_model');
            $this->load->model('default/role_model');
            $data = $this->input->post();
            $user = $this->admin_model->check_password($data['username'], $data['password']);
            if ($user) {
                $role = $this->role_model->get_array($user['role_id']);
                $has_role = $role && empty($role['is_removed']);
                $user['role_title'] = $has_role ? $role['title'] : '';
                $user['is_super'] = $has_role ? $role['is_super'] : 0;
                $this->session->set_userdata($user);
                return $this->index();
            }
        }
        return ['layout_class' => 'signin'];
    }


    public function logout()
    {
        $this->session->sess_destroy();
        return redirect($this->base_url . $this->login_url);
    }

    public function unlock()
    {
        $username = $this->session->userdata('username');
        if ('post' === $this->request_method) {
            $this->load->model('default/admin_model');
            $data = $this->input->post();
            $user = $this->admin_model->check_password($username, $data['password']);
            if ($user) {
                $this->session->set_userdata($user);
                return $this->index();
            }
        }
        return ['layout_class' => 'gray-bg', 'username' => $username];
    }
}
