<?php

class User_model extends CI_Model {

    public function getUser($filter) {
        $where = [];
        if(!empty($filter['id'])) {
            $where[] = '`uid` = '.$filter['id'];
        }
        if(!empty($filter['username'])) {
            $where[] = '`username` = '.$this->db->escape($filter['username']);
        }
        if(!empty($filter['active'])) {
            $where[] = '`active` = '.$filter['active'];
        }

        $where = (count($where)?(' WHERE '.implode(' AND ', $where)):'');

        $query = 'SELECT * FROM `users`'.$where;
        $query = $this->db->query($query);
        if($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function addUser($data) {
        $query = 'INSERT INTO `users`(`'.implode('`, `', array_keys($data)).'`) VALUES("'.
            implode('", "', $data).'");';
        $this->db->query($query);
        return $this->db->insert_id();
    }

    public function check_existence($username) {
        $query = 'SELECT `username` FROM `users` u WHERE u.username = '.$this->db->escape($username);
        $query = $this->db->query($query);
        return $query->num_rows();
    }

    public function verifyUser($hashCode) {
        $query = 'SELECT * FROM `users` WHERE SHA1(CONCAT(`username`, `activation_key`)) = '.$this->db->escape($hashCode);
        $query = $this->db->query($query);
        if($query->num_rows()) {
            $query = 'UPDATE `users` SET `active` = 1 WHERE SHA1(CONCAT(`username`, `activation_key`)) = '.$this->db->escape($hashCode);
            $this->db->simple_query($query);
            return true;
        }
    }

    public function addUser_summoner($data) {
        $this->db->insert('user_summoners', $data);
        return $this->db->insert_id();
    }

    public function getUserSummoners($uid) {
        if(!$uid) {
            return false;
        }
        $query = 'SELECT u.*, s.`name`, s.`profileIconId` FROM `user_summoners` u LEFT JOIN `summoners` s ON (u.`sid` = s.`sid` AND u.`region` = s.`region`) WHERE `uid` = '.$uid;
        $query = $this->db->query($query);
        if($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function activateUserSummoner($uid, $sid, $region) {
        $query = 'UPDATE `user_summoners` SET `active` = 1 WHERE `uid`='.$uid.' AND `sid`='.$sid.' AND`region`="'.$region.'"';
        $this->db->simple_query($query);
    }

    public function unlinkUserSummoner($data) {
        $this->db->delete('user_summoners', $data);
    }

    public function bulkVerifySummoners() {

        $this->load->library('email');

        $limit = 126;
        $offset = 0;
        $query = $this->db->get_where('user_summoners', array('active' => 0), $limit, $offset);
        $result = $query->result();

        $mail_config = array(
            'mailtype' => 'html'
        );

        foreach($result as $row) {
            echo 'Verifying uid: '.$row->uid.', sid: '.$row->sid.', region: '.$row->region.', code:'.$row->verification_key;
            set_time_limit(300);
            $masteries = $this->lolservice->get_summoner_masteries($row->region, $row->sid);
            $user = $this->getUser(array('id' => $row->uid));
            $messageS  = <<<EOT
<h2> Hello dear {$user->username},</h2>
<p> You have recently added summoner to your account on MyLoLChampions.com </p>
<p> Unfortunately there was an issue with mastery pages data update, but now it's working properly and your summoner has been verified</p>
<br />
<p>
Cheers, <br />
Tigran, founder of MyLoLChampions
</p>
EOT;
            $messageF  = <<<EOT
<h2> Hello dear {$user->username},</h2>
<p> You have recently added summoner to your account on MyLoLChampions.com </p>
<p> Unfortunately there was an issue with mastery pages data update, but now it's working properly, so you should be able to verify your summoner.</p>
<p> If you face any difficulties with verification, please reply to this email and I will try to help you with it.</p>
<br />
<p>
Cheers, <br />
Tigran, founder of MyLoLChampions
</p>
EOT;
            foreach($masteries as $mastery) {
                $verificationSuccessful = 0;
                if($mastery->name == $row->verification_key) {
                    $verificationSuccessful = 1;
                    echo ' <b>succeed</b><br />';
                    $this->activateUserSummoner($row->uid, $row->sid, $row->region);
                    $this->email->initialize($mail_config);

                    $this->email->from('support@mylolchampions.com', 'MyLoLChampions.com');
                    $this->email->bcc('info@mylolchampions.com');
                    $this->email->to($user->email);

                    $this->email->subject('MyLoLChampions.com - Summoner verification');
                    $this->email->message($messageS);

                    $sent = $this->email->send();
                    if($sent) {
                        echo 'Mail has been sent.<br />';
                    }
                }
            }
            if(!$verificationSuccessful) {
                echo ' <b>failed</b><br />';
            }
        }
    }
}