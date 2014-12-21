<?php

class About extends MY_Controller {

    public function Index() {
        $pageData['page'] = 'about';
        $pageData['meta_title'] = 'About Us';
        $this->load->view('template', $pageData);
    }
}