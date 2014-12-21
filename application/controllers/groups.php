<?php

class Groups extends MY_Controller {

    public function Groups() {
        parent::__construct();
        $this->load->model('Groups_model');
    }

    public function Search() {
        $data = [
            'champ_name' => $this->input->get('champ_name', true),
            'champ_group' => $this->input->get('champ_group', true),
            'champ_role' => $this->input->get('champ_role', true),
            //'tags' => $this->input->get('champ_tags', true),
            'order' => $this->input->get('champ_order', true),
            'sid' => $this->input->get('sid', true),
            'region' => $this->input->get('region', true),
            'limit' => $this->input->get('champ_limit', true)
        ];

        $pageData['group_code'] = 'search';
        $pageData['champions'] = $this->Groups_model->getChampions($data);
        $this->load->view('group_content', $pageData);
    }

    public function Show() {
        $data['groupId'] = $this->input->post('group_id', true);
        $data['sid'] = $this->input->get('sid', true);
        $data['region'] = $this->input->get('region', true);

        $pageData['basicInfo'] = array_shift($this->Groups_model->getGroups($data['groupId'], $data['sid'], $data['region']));
        $pageData['champions'] = $this->Groups_model->getChampions($data);
        $this->load->view('group_content', $pageData);
    }

    public function Get_group_data() {
        $groupId = $this->input->post('group_id', true);
        $groups = $this->Groups_model->getGroups($groupId);
        echo json_encode($groups);
    }

    public function Save() {
        $championIds = $this->input->post('group_champions', true);
        if($championIds && count($championIds)) {
            $championIds = implode(',', array_keys($championIds));
        } else {
            $championIds = '';
        }

        $data = [
            'cgid' => $this->input->post('group_id'),
            'uid' => $this->session->userdata('uid'),
            'title' => $this->input->post('group_name', true),
            'champions' => $championIds,
            'access' => $this->input->post('group_access', true)
        ];

        if(empty($data['group_id'])) {
            $alerts[] = array(
                'status' => 'success',
                'message' => 'Groups <b>' . $data['title'] . '</b> has been created.'
            );
        } else {
            $alerts[] = array(
                'status' => 'success',
                'message' => 'Changes on groups <b>' . $data['title'] . '</b> have been saved.'
            );
        }

        $this->session->set_userdata('alerts', $alerts);

        $groupId = $this->Groups_model->saveGroup($data);
        echo $groupId;
    }

    public function Delete() {
        $data = array(
            'uid' => $this->session->userdata('uid'),
            'group_id' => $this->input->post('group_id')
        );
        $this->Groups_model->deleteGroup($data);

        $alerts[] = [
            'status' => 'success',
            'message' => 'Group has been deleted.'
        ];

        $this->session->set_userdata('alerts', $alerts);
    }
}