<?php

class champions extends MY_Controller {
    public function Groups() {
        parent::__construct();
        $this->load->model('Champions_model');
    }
    public function Update() {
        $this->Champions_model->updateChampionsInfo();
        echo 'Done'; exit;
    }

    public function Updatefree() {
        $region = $this->uri->segment(3);
        if(!$region) {
            $region = 'na';
        }
        $this->Champions_model->updateFreeToPlay($region);
        echo 'Done'; exit;
    }

    public function Updateicons() {
        $patch = $this->uri->segment(3);
        if(!$patch) {
            $patch = '4.6.3';
        }
        $this->load->library('Grabimages');
        $this->grabimages->grabChampionIcons('./img/champion_icons/', true, $patch);
        echo 'Done'; exit;
    }
}