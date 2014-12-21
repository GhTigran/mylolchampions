<?php

class MY_Controller extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        if(!empty($_COOKIE['savedUser']) && !($this->session->userdata('loggedIn'))) {
            $this->load->model('User_model');
            $user = $this->User_model->getUser([
                'id' => $this->input->cookie('savedUser')
            ]);
            if($user !== false) {
                $data = [
                    'logged_in' => 1,
                    'uid' => $user->uid,
                    'username' => $user->username
                ];
                $this->session->set_userdata($data);
                setcookie('saved_user', $user->uid, time()+604800, '/');
            }
        }

        $this->freeChampions = $this->Champions_model->getChampions(true);
    }
}