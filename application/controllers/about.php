<?php

class About extends MY_Controller {

    public function Index() {
        $pageData['page'] = 'about';
        $pageData['metaTitle'] = 'About Us';
        $this->load->view('template', $pageData);
    }
}