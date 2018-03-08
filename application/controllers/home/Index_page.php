<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once __DIR__ . '/Admin_page.php';


class Index_page extends Admin_page
{

    public function index()
    {
        $main_url = $this->base_url . '/home/entry/';
        return redirect($main_url);
    }

    public function login()
    {
        if ('post' === $this->request_method) {
            $this->load->model('default/user_model');
            $data = $this->input->post();
            $user = $this->user_model->check_password($data['username'], $data['password']);
            if ($user) {
                $this->session->set_userdata($user);
                return $this->index();
            }
        }
        return ['layout_class' => 'login'];
    }


    public function logout()
    {
        $this->session->sess_destroy();
        return redirect($this->base_url . $this->login_url);
    }
}
