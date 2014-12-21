<?php

class Errors extends MY_Controller {
    public function page_missing() {
        $pageData['page'] = 'errors/page_missing';
        $this->load->view('template', $pageData);
    }
}