<?php

class Champions_model extends CI_Model {

    const FREE_CHAMPS_UPDATE_DAY = 3; // Wednesday

    /**
     * Update Champions Data <br />
     * @return array <p>
     * Int result status
     * </p>
     */
    public function updateChampionsInfo() {
        $championsData = $this->lolservice->getChampions();
        $this->db->query('TRUNCATE TABLE `champions`');
        $query = 'INSERT INTO `champions` (`chid`, `name`, `attack`, `defense`, `magic`, `difficulty`) VALUES' ."\n";
        $records = array();
        foreach($championsData as $champion) {
            $records[] = '('. $champion->id .', "'. $champion->name .'", '
                . $champion->info->attack .', '. $champion->info->defense .', '. $champion->info->magic . ','
                . $champion->info->difficulty .')';
        }
        $query .= implode(",\n", $records) . ';';
        $this->db->simple_query($query);

        $this->Log_model->updateLog('champions', time(), 'NA');

        return 1;
    }

    public function updateFreeToPlay($region) {
        $this->db->update('champions', array('freeToPlay' => 0));
        $championsData = $this->lolservice->getFreeChampions($region);
        $free_champion_ids = array();
        foreach($championsData as $champion) {
            $free_champion_ids[] = $champion->id;
        }
        $this->db->query('update `champions` ch set `freeToPlay`=1 where ch.`chid` in ('.implode(',', $free_champion_ids).')');

        return 1;
    }

    public function getChampions($freeToPlay = false) {

        $query = 'SELECT * FROM champions' . (($freeToPlay) ? ' WHERE `freeToPlay` = 1' : ' ORDER BY `name`');
        $champs_query = $this->db->query($query);
        foreach($champs_query->result() as $row) {
            $championsData[$row->chid] = $row;
        }
        return (!empty($championsData)) ? $championsData : array();
    }
}