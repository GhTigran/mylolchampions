<?php

class Groups_model extends CI_Model {

    public function getChampions($data) {
        $select = 'ch.*';
        $joins = array();
        $where = array();

        if(!empty($data['champ_name'])) {
            $where[] = 'ch.name LIKE "%'.$this->db->escape_like_str($data['champ_name']).'%"';
        }

        if(!empty($data['champ_role'])) {
            $joins[] = 'LEFT JOIN `champion_roles` cr ON cr.`chid` = ch.`chid`';
            $where[] = 'cr.role = "'.$this->db->escape_str($data['champ_role']).'"';
        }

        if(!empty($data['champ_group'])) {
            $where[] = 'FIND_IN_SET(ch.chid, (SELECT `champions` FROM `champion_groups` WHERE `cgid` = '.(int)$data['champ_group'].'))';
        }

        /*
        if(!empty($data['tags'])) {
            $joins[] = 'LEFT JOIN `champion_tags` ct ON ch.`chid` = st.chid)';
            $data['tags'] = str_replace(', ', ',', $data['tags']);
            $tags = explode(',', $data['tags']);
            foreach($tags as $tag) {
                $where[] = 'ct.`tag` LIKE "%'.$tag.'%"';
            }
        }
        */
        if(!empty($data['sid']) && !empty($data['region'])) {
            $select .= ', ss.*';
            $joins[] = 'LEFT JOIN `summoner_stats` ss ON (ss.`region` = "'.$this->db->escape_str($data['region']).'" AND ss.`sid` = '.(int)$data['sid'].' AND ch.`chid` = ss.`chid`)';
        }

        $order = '';
        if(isset($data['order'])) {
            $order = ' ORDER BY ';

            switch($data['order']) {
                case 'games':
                    $order .= 'ss.`games` DESC';
                    break;
                case 'wins':
                    $order .= 'ss.`wins` DESC';
                    break;
                case 'random':
                    $order .= 'RAND()';
                    break;
                case 'name':
                default:
                    $order .= 'ch.`name` ASC';
            }
        }

        $limit = '';
        if(!empty($data['limit'])) {
            $limit = ' LIMIT ' . (int)$data['limit'];
        }

        $joins = "\n\t".implode("\n\t", $joins)."\n";
        $where = (count($where)?'WHERE '.implode(' AND ', $where):'');
        $query = 'SELECT ' . $select . ' FROM `champions` ch ' . $joins . $where . $order . $limit;
	
	$this->db->query('SET SQL_BIG_SELECTS=1');
	
        $query = $this->db->query($query);
        if($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getGroups($uid, $sid, $region) {
        if(!$uid) {
            $uid = 0;
        }
        $query = 'SELECT cg.* FROM `champion_groups` cg
        LEFT JOIN `user_summoners` us ON cg.`uid` = us.`uid` AND us.active = 1
        WHERE
            (cg.`access` = "public" OR (cg.`access` = "private" AND cg.`uid`='.$uid.'))
            AND
            ((us.`sid`='.(int)$sid.' AND us.`region` = "'.$this->db->escape_str($region).'") OR us.`sid` = 0)';
        $query = $this->db->query($query);
        if($query->num_rows()) {
            return $query->result();
        } else {
            return 0;
        }
    }

    public function saveGroup($data) {
        if($data['uid']) {
            if(empty($data['cgid'])) {
                $this->db->insert('champion_groups', $data);
            } else {
                $this->db->update('champion_groups', $data, array('uid' => $data['uid'], 'cgid' => $data['cgid']));
            }
            return ($data['cgid']?$data['cgid']:$this->db->insert_id());
        }
    }

    public function deleteGroup($data) {
        if($data['uid'] && $data['group_id']) {
            $query = 'DELETE FROM `champion_groups`
                WHERE `cgid` = '.$data['group_id'] .' AND `uid` = ' . $data['uid'];
            $this->db->simple_query($query);
        }
    }
}