<?php

class Contact extends MY_Controller {

    public function Index() {
        $send = $this->input->post('send');
        if($send) {
            $config = array(
                'mailtype' => 'html'
            );

            $message  = "<p> From: <b> {$this->input->post('email', true)} </b> \n</p>";
            $message .= "<p> Message: \n</p>";
            $message .= "<p> {$this->input->post('message', true)} \n</p>";

            $this->load->library('email');

            $this->email->initialize($config);

            $this->email->from('contacts@mylolchampions.com', 'MyLoLChampions contact form');
            $this->email->to('support@mylolchampions.com');

            $this->email->subject('Feedback from visitors');
            $this->email->message($message);

            $this->email->send();


            $alerts[] = [
                'status' => 'success',
                'message' => 'Thank you for feedback. We appreciate it a lot.'
            ];
        }

        if(!empty($alerts) && count($alerts)) {
            $this->session->set_userdata('alerts', $alerts);
        }

        $pageData['page'] = 'contact';
        $pageData['metaTitle'] = 'Contact Us';
        $pageData['jsFiles'] = [
            'jquery.validate.js'
        ];

        $this->load->view('template', $pageData);
    }
}