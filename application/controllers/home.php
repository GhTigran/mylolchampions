<?php

class Home extends MY_Controller {

    public function Index() {
        $pageData['page'] = 'home';
        $pageData['metaTitle'] = 'Lol Champions Management Tool';
        $pageData['metaDescription'] = 'MyLolChampions is a free online champions managmement tool based on popular game League of Legends. Main goal of this site is to help players manage their champions.';
        $this->load->view('template', $pageData);
    }
}