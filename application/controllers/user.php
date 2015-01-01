<?php

class User extends MY_Controller {

    public function Profile() {
        if(!($this->session->userdata('loggedIn'))) {
            redirect(base_url().'user/login/', 'refresh');
        }
        $this->load->model('User_model');
        $this->load->model('Summoner_model');

        $pageData['summoners'] = $this->User_model->getUserSummoners($this->session->userdata('uid'));

        $pageData['page'] = 'user/profile';
        $pageData['uid'] = $this->session->userdata('uid');
        $pageData['jsFiles'] = array(
            'user/profile.js'
        );
        $pageData['metaTitle'] = $this->session->userdata('username');
        $this->load->view('template', $pageData);
    }

    public function login() {
        if($this->session->userdata('loggedIn')) {
            redirect(base_url().'user/'.$this->session->userdata('username').'/', 'refresh');
        }

        if($this->input->post('login')) {
            $this->load->model('User_model');
            $user = $this->User_model->getUser(array(
                'username' => $this->input->post('username', true)
            ));
            if($user !== false) {
                $pageData['username'] = $user->username;
                if($user->password == md5($this->input->post('pass', true))) {
                    if($user->active) {
                        $data = array(
                            'loggedIn' => 1,
                            'uid' => $user->uid,
                            'username' => $user->username
                        );
                        $this->session->set_userdata($data);
                        if($this->input->post('saveUser')) {
                            setcookie("savedUser", $user->uid, time()+604800, '/');
                        }
                        redirect(base_url().'user/profile/', 'refresh');
                    } else {
                        $pageData['jsFiles'] = array(
                            'user/login.js'
                        );
                        $alerts[] = array(
                            'status' => 'danger',
                            'message' => 'Your account is not active yet.
                            In order to activate it please follow the verification link in the verification email we sent to you. <br />
                            If you have not received the verification email, click this button
                            <button type="button" class="btn btn-primary btn-sm" id="resend-verification">
                                Resend verification email
                            </button>'
                        );
                    }
                } else {
                    $alerts[] = array(
                        'status' => 'danger',
                        'message' => 'Wrong password.'
                    );
                }
            } else {
                $alerts[] = array(
                    'status' => 'danger',
                    'message' => 'There is no user with such username.'
                );
            }
        }

        if(!empty($alerts) && count($alerts)) {
            $this->session->set_userdata('alerts', $alerts);
        }

        $pageData['page'] = 'user/login';
        $pageData['metaTitle'] = 'Login';
        $this->load->view('template', $pageData);
    }

    public function logout() {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('loggedIn');
        unset($_COOKIE["savedUser"]);
        setcookie("savedUser", '', time() - 3600, '/');
        redirect(base_url(), 'refresh');
    }

    public function reg() {
        if($this->input->post('reg')) {

            $this->load->library('email');

            $this->load->model('User_model');
            $this->load->helper('string');
            $verificationKey = random_string('alnum', 15);
            $data = array(
                'username' => $this->input->post('username', true),
                'password' => md5($this->input->post('pass', true)),
                'email' => $this->input->post('email', true),
                'activation_key' => $verificationKey
            );

            $this->User_model->addUser($data);

            $mailConfig = array(
                'mailtype' => 'html'
            );

            $code = sha1($data['username'].$data['activation_key']);

            $message  = <<<EOT
<h2> Hello {$data['username']}</h2>
<p> Thank you for registering at MyLoLChampions!</p>
<p>In order to start using your account, please confirm your email address by clicking the confirmation link below:</p>
<p>
    <a href="http://www.mylolchampions.com/user/verify/?c={$code}" title="Verify your email">
        http://www.mylolchampions.com/user/verify/?c={$code}
    </a>
</p>
<p>If you did not initiate this request, please disregard and/or delete this e-mail.</p>
<br />
<p>
Cheers, <br />
GhMaster, founder of MyLoLChampions
</p>
EOT;

            $this->email->initialize($mailConfig);

            $this->email->from('noreply@mylolchampions.com', 'MyLoLChampions.com');
            $this->email->to($data['email']);

            $this->email->subject('MyLoLChampions.com - Email verification');
            $this->email->message($message);

            $this->email->send();

            $alerts[] = array(
                'status' => 'success',
                'message' => 'Congratulations, you have successfully registered.'
            );

            $alerts[] = array(
                'status' => 'warning',
                'message' => 'We have sent you an email with verification link. Please make sure to verify your email before you attempt to login.'
            );

            $this->session->set_userdata('alerts', $alerts);

            redirect(base_url('user/login'), 'refresh');
        }

        $pageData['jsFiles'] = array(
            'user/reg.js'
        );
        $pageData['page'] = 'user/registration';
        $pageData['metaTitle'] = 'Registration';
        $this->load->view('template', $pageData);
    }

    public function verify() {
        $this->load->model('User_model');
        $hashCode = $this->input->get('c', true);

        if($hashCode && $this->User_model->verifyUser($hashCode)) {
            $alerts[] = array(
                'status' => 'success',
                'message' => 'Your email has been successfully verified.'
            );
            $this->session->set_userdata('alerts', $alerts);
            redirect(base_url('user/login'), 'refresh');
        } else {
            $pageData['page'] = 'errors/page_missing';
            $this->load->view('template', $pageData);
        }
    }

    public function resend_verification() {
        $this->load->model('User_model');
        $this->load->library('email');

        $username = $this->input->post('username', true);
        if(!$username) {
            echo 0; die;
        }

        $user = $this->User_model->getUser(array(
            'username' => $username
        ));

        if($user) {
            $mailConfig = array(
                'mailtype' => 'html'
            );

            $code = sha1($user->username.$user->activationKey);

            $message  = <<<EOT
<h2> Hello {$user->username}</h2>
<p>In order to start using your account, please confirm your email address by clicking the confirmation link below:</p>
<p>
    <a href="http://www.mylolchampions.com/user/verify/?c={$code}" title="Verify your email">
        http://www.mylolchampions.com/user/verify/?c={$code}
    </a>
</p>
<p>If you did not initiate this request, please disregard and/or delete this e-mail.</p>
<br />
<p>
Cheers, <br />
GhMaster, founder of MyLoLChampions
</p>
EOT;

            $this->email->initialize($mailConfig);

            $this->email->from('noreply@mylolchampions.com', 'MyLoLChampions.com');
            $this->email->to($user->email);

            $this->email->subject('MyLoLChampions.com - Email verification');
            $this->email->message($message);

            $this->email->send();

            echo 1; die;
        } else {
            echo 0; die;
        }
    }

    public function add_summoner() {
        $name = $this->input->post('name', true);
        $region = $this->input->post('region', true);
        $uid = $this->session->userdata('uid');
        if(empty($name) || empty($region) || empty($uid)) {
            redirect(base_url(), 'refresh');
        } else {
            $this->load->model('Summoner_model');
            $summoner = $this->Summoner_model->getData($region, $name);
            // Grab summoner icon if not exists on our server
            if(!file_exists('./img/summoner_icons/'. $summoner->profileIconId .'.jpg')) {
                $temp_headers = get_headers('http://lkimg.zamimg.com/shared/riot/images/profile_icons/profileIcon' . $summoner->profileIconId . '.jpg');
                if(strpos($temp_headers[0], '200')) {
                    $img_content = file_get_contents('http://lkimg.zamimg.com/shared/riot/images/profile_icons/profileIcon' . $summoner->profileIconId . '.jpg');
                    file_put_contents('./img/profile_icons/'. $summoner->profileIconId . '.jpg', $img_content);
                } else {
                    $summoner->profileIconId = 0;
                }
            }

            $summoner->verificationKey = random_string('alnum', 15);

            $data = array(
                'uid' => $uid,
                'sid' => $summoner->sid,
                'region' => $region,
                'verification_key' => $summoner->verificationKey
            );
            $this->load->model('User_model');
            $this->User_model->addUserSummoner($data);

            echo json_encode($summoner); exit();
        }
    }

    public function verify_summoner() {
        $code = $this->input->post('code', true);
        $sid = $this->input->post('sid', true);
        $region = $this->input->post('region', true);
        $masteries = $this->lolservice->getSummonerMasteries($region, $sid);
        foreach($masteries as $mastery) {
            if($mastery->name == $code) {
                $this->load->model('User_model');
                $this->User_model->activateUserSummoner($this->session->userdata('uid'), $sid, $region);
                echo 1; exit();
            }
        }
        echo 0; exit();
    }

    public function unlink_summoner() {
        $data['uid'] = $this->session->userdata('uid');
        $data['sid'] = $this->input->post('sid', true);
        $data['region'] = $this->input->post('region', true);
        if(!empty($data['sid']) && !empty($data['region']) && !empty($data['uid'])) {
            $this->load->model('User_model');
            $this->User_model->unlinkUserSummoner($data);
        }
    }

    public function check_existence() {
        $this->load->model('User_model');
        $username = $code = $this->input->post('username', true);
        echo $this->User_model->checkExistence($username);
    }

    public function bulk_verify_summoner() {
        $this->load->model('User_model');
        $userSummoners = $this->User_model->bulkVerifySummoners();
	    echo 'Done';
    }
}