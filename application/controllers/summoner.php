<?php

class Summoner extends MY_Controller {
    public function Index() {
        $this->load->model('Summoner_model');
        $this->load->model('Groups_model');

        $this->config->load('site_configs');

        $pageData = array();

        $uid = $this->session->userdata('uid');

        $region = strtolower($this->uri->segment(2));
        $sName = $this->uri->segment(3);
        $summoner = $this->Summoner_model->getData($region, $sName);
        if($summoner) {
            if($summoner->summonerLevel == 30) {
                //Get league data
                $summoner->leagueData = $this->Summoner_model->getLeagueData($region, $summoner->sid);
                //Get ranked statistics
                $rankedStats = $this->Summoner_model->getRankedStats($region, $summoner->sid);
            }
            // Grab summoner icon if not exists on our server
            $localIcon = $this->config->item('profile_icons_path') .
                $summoner->profileIconId . $this->config->item('icon_img_ext');
            $externalIcon = $this->config->item('external_profile_icon_path') .
                $summoner->profileIconId . $this->config->item('icon_img_ext');
            if(!file_exists($localIcon)) {
                $temp_headers = get_headers($externalIcon);
                if(strpos($temp_headers[0], '200')) {
                    $imgContent = file_get_contents($externalIcon);
                    file_put_contents($localIcon, $imgContent);
                } else {
                    $summoner->profileIconId = 0;
                }
            }

            $ownsSummoner = false;
            if($this->session->userdata('logged_in')) {
                $this->load->model('User_model');
                $userSummoners = $this->User_model->getUserSummoners($uid);
                foreach($userSummoners as $userSummoner) {
                    if($userSummoner->sid == $summoner->sid && $userSummoner->region == $region && $userSummoner->active) {
                        $ownsSummoner = true;
                        break;
                    }
                }
            }

            $pageData['region'] = $region;
            $pageData['championGroups'] = $this->Groups_model->getGroups($uid, $summoner->sid, $region);
            $pageData['ownsSummoner'] = $ownsSummoner;
            $pageData['summoner'] = $summoner;

            if(!empty($rankedStats)) {
                $pageData['rankedStats'] = $rankedStats;
            }
        }

        $pageData['cssFiles'] = array(
            'tablesorter/style.css'
        );

        $pageData['jsFiles'] = array(
            'jquery.tablesorter.min.js',
            'summoner.js'
        );

        $pageData['arabicNumbers'] = array(
            'I' => 1,
            'II' => 2,
            'III' => 3,
            'IV' => 4,
            'V' => 5
        );

        $pageData['champRankTypes'] = array(
            'defense',
            'attack',
            'magic',
            'difficulty'
        );
        $pageData['page'] = 'summoner';
        $pageData['metaTitle'] = $sName . ' - ' . strtoupper($region);
        $pageData['champions'] = $this->Champions_model->getChampions();
        $this->load->view('template', $pageData);
    }

    public function Get() {
        $name = $this->input->post('name', true);
        $region = $this->input->post('region', true);
        if(!$name || !$region) {
            redirect(base_url(), 'refresh');
        } else {
            
            $this->load->model('Summoner_model');
            $summoner = $this->Summoner_model->getData($region, $name);

            // Grab summoner icon if not exists on our server
            $localIcon = $this->config->item('profile_icons_path') .
                $summoner->profileIconId . $this->config->item('icon_img_ext');
            $externalIcon = $this->config->item('external_profile_icon_path') .
                $summoner->profileIconId . $this->config->item('icon_img_ext');
            if(!file_exists($localIcon)) {
                $temp_headers = get_headers($externalIcon);
                if(strpos($temp_headers[0], '200')) {
                    $imgContent = file_get_contents($externalIcon);
                    file_put_contents($localIcon, $imgContent);
                } else {
                    $summoner->profileIconId = 0;
                }
            }
            echo json_encode($summoner);
        }
    }
}