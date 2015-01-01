<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tigran
 * Date: 12/15/13
 */

class Summoner_model extends CI_Model {

    const SUMMONER_DATA_UPDATE_FREQUENCE = 600; // 10 minutes
    const SUMMONER_LEAGUE_DATA_UPDATE_FREQUENCE = 1800; // 30 minutes
    const STATS_UPDATE_FREQUENCE = 1800; // 30 minutes

    /**
     * Get summoner data by name <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param string $sName <p>
     * summoner name
     * </p>
     * @return Object <p>
     * StdObject containing summoner data
     * </p>
     */
    public function getData($region, $sName) {
        $sName = str_replace(' ', '', strtolower(rawurldecode($sName)));
        $logData = $this->Log_model->getLog("summoners", $region, $sName);

        if($logData === false || (time() - $logData->update_time >= self::SUMMONER_DATA_UPDATE_FREQUENCE)) {
            $summoner = $this->lolservice->getSummonerByName($region, $sName)->$sName;
            if($summoner) {
                unset($summoner->revisionDate, $summoner->revisionDateStr);

                $summoner->region = $region;
                $this->saveData($summoner);
                $summoner->sid = $summoner->id;
                unset($summoner->id);
                return $summoner;
            } else {
                return false;
            }
        }

        $query = 'SELECT * FROM `summoners` WHERE `name` = "' . $this->db->escape_str($sName) . '" AND region="' . $this->db->escape_str($region) . '"';
        $query = $this->db->query($query);
        $return = $query->row();
        return $return;
    }

    private function saveData($summoner) {
        $query = $this->db->query('SELECT * FROM `summoners` WHERE `sid` = ' . $summoner->id .' AND `region`="'.$summoner->region.'"');
        if($query->num_rows()) {
            $query = 'UPDATE `summoners` SET
                `sid` = ' . $summoner->id . ',
                `name` = "' . $summoner->name . '",
                `region` = "' . $summoner->region . '",
                `profileIconId` = ' . $summoner->profileIconId . ',
                `summonerLevel` = ' . $summoner->summonerLevel . '
            where `sid` = ' . $summoner->id . ' AND `region` = "' . $summoner->region . '"';
        } else {
            $fields = '`sid`, `name`, `region`, `profileIconId`, `summonerLevel`';
            $values = $summoner->id . ', "'.$summoner->name.'", "'.$summoner->region.'", '.$summoner->profileIconId.', '.$summoner->summonerLevel;
            $query = 'INSERT INTO `summoners`  (' . $fields . ') VALUES (' . $values . ')';
        }
        $this->db->query($query);
        $this->Log_model->updateLog('summoners', time(), $summoner->region, $summoner->name);
    }

    public function getLeagueData($region, $sid) {
        $logData = $this->Log_model->getLog("league_data", $region, $sid);
        $leagueData = new stdClass();

        if($logData === false || (time() - $logData->update_time >= self::SUMMONER_LEAGUE_DATA_UPDATE_FREQUENCE)) {
            $tempLeagueDatas = $this->lolservice->getLeagueData($region, $sid);
            foreach($tempLeagueDatas as $tempLeagueData) {
                if($tempLeagueData[0]->queue === 'RANKED_SOLO_5x5') {
                    foreach($tempLeagueData[0]->entries as $entry) {
                        if($entry->playerOrTeamId == $sid) {
                            $playerLeagueData = $entry;
                            $playerLeagueData->tier = $tempLeagueData[0]->tier;
                        }
                    }
                }
            }
            if(!empty($playerLeagueData)) {
                $leagueData->tier = ucwords(strtolower($playerLeagueData->tier));
                $leagueData->rank = $playerLeagueData->division;
                $leagueData->leaguePoints = $playerLeagueData->leaguePoints;
                $leagueData->wins = $playerLeagueData->wins;
                //ToDo Grab losses as well, if they provide it
                //$leagueData->losses = $entry->losses;
                $leagueData->isHotStreak = ($playerLeagueData->isHotStreak?1:0);
                $leagueData->isVeteran = ($playerLeagueData->isVeteran?1:0);
                $leagueData->isFreshBlood = ($playerLeagueData->isFreshBlood?1:0);
                $leagueData->isInactive = ($playerLeagueData->isInactive?1:0);
                $this->saveLeagueData($leagueData, $region, $sid);
                return $leagueData;
            } else {
                return false;
            }
        }

        $query = 'SELECT * FROM `league_data` WHERE `sid` = "' . $sid . '" AND region="' . $region . '"';
        $query = $this->db->query($query);
        $return = $query->row();
        return $return;
    }

    public function saveLeagueData($leagueData, $region, $sid) {
        $query = $this->db->query('SELECT * FROM `league_data` WHERE `sid` = ' . $sid .' AND `region`="'.$region.'"');
        if($query->num_rows()) {
            $query = 'UPDATE `league_data` SET
                `tier` = "' . $leagueData->tier . '",
                `rank` = "' . $leagueData->rank . '",
                `leaguePoints` = ' . $leagueData->leaguePoints . ',
                `wins` = ' . $leagueData->wins . ',
                `isHotStreak` = ' . $leagueData->isHotStreak . ',
                `isVeteran` = ' . $leagueData->isVeteran . ',
                `isFreshBlood` = ' . $leagueData->isFreshBlood . ',
                `isInactive` = ' . $leagueData->isInactive . '
            where `sid` = ' . $sid . ' AND `region` = "' . $region . '"';
        } else {
            $fields = '`sid`, `region`, `tier`, `rank`, `leaguePoints`, `wins`, `isHotStreak`, `isVeteran`, `isFreshBlood`, `isInactive`';
            $values = $sid.', "'.$region.'", "'.$leagueData->tier.'", '.'"'.$leagueData->rank.'", '.$leagueData->leaguePoints.', '.
                $leagueData->wins.', '.$leagueData->isHotStreak.', '.$leagueData->isVeteran.', '.
                $leagueData->isFreshBlood.', '.$leagueData->isInactive;
            $query = 'INSERT INTO `league_data`  (' . $fields . ') VALUES (' . $values . ')';
        }
        $this->db->query($query);
        $this->Log_model->updateLog('league_data', time(), $region, $sid);
    }

    /**
     * Get summoner ranked stats <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param string $sid <p>
     * summoner id
     * </p>
     * @return Object <p>
     * StdObject containing summoner ranked stats
     * </p>
     */
    public function getRankedStats($region, $sid) {
        $logData = $this->Log_model->getLog("summoner_stats", $region, $sid);

        if($logData === false || (time() - $logData->update_time >= self::STATS_UPDATE_FREQUENCE)) {
            $rankedStats = array();
            $tempStats = $this->lolservice->getSummonerRankedStats($region, $sid);
            foreach($tempStats as $champion) {
                if(empty($champion->id)) {
                    continue; // Overall stats not needed
                }
                $chid = strtolower($champion->id);
                $rankedStats[$chid] = new stdClass();
                $rankedStats[$chid]->games = $champion->stats->totalSessionsPlayed;
                $rankedStats[$chid]->wins = $champion->stats->totalSessionsWon;
                $rankedStats[$chid]->losses = $champion->stats->totalSessionsLost;
                $rankedStats[$chid]->kills = $champion->stats->totalChampionKills;
                $rankedStats[$chid]->assists = $champion->stats->totalAssists;
                $rankedStats[$chid]->deaths = $champion->stats->totalDeathsPerSession; //ToDo fix this: $stat->stats->totalDeaths;
                $rankedStats[$chid]->double_kills = $champion->stats->totalDoubleKills;
                $rankedStats[$chid]->triple_kills = $champion->stats->totalTripleKills;
                $rankedStats[$chid]->quadra_kills = $champion->stats->totalQuadraKills;
                $rankedStats[$chid]->penta_kills = $champion->stats->totalPentaKills;
                $rankedStats[$chid]->gold_earned = $champion->stats->totalGoldEarned;
                $rankedStats[$chid]->cs = $champion->stats->totalMinionKills; // + $champion->stats->totalNeutralMinionsKilled ?
            }
            $this->saveRankedStats($region, $sid, $rankedStats);
        }
        $query = $this->db->query('SELECT * FROM `summoner_stats` WHERE `region` = "' . $region . '" AND `sid` = ' . $sid);
        $rankedStats = $query->result();
        $return = array();
        foreach($rankedStats as $stats) {
            $return[$stats->chid] = $stats;
        }
        return $return;
    }

    private function saveRankedStats($region, $sid, $rankedStats) {
        $query = $this->db->query('SELECT `chid` FROM `summoner_stats` WHERE `region` = "' . $region . '" AND `sid` = ' . $sid);
        $chids = array();
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row) {
                $chids[] = $row->chid;
            }
        }
        foreach($rankedStats as $chid => $champStats) {
            if(in_array($chid, $chids)) {
                $query = $this->db->query('UPDATE `summoner_stats` SET '.
                    '`games` = '.$champStats->games.', '.
                    '`wins` = '.$champStats->wins.', '.
                    '`win_per` = '. ($champStats->wins / $champStats->games * 100) .', '.
                    '`losses` = '.$champStats->losses.', '.
                    '`kills` = '.$champStats->kills.', '.
                    '`deaths` = '.$champStats->deaths.', '.
                    '`assists` = '.$champStats->assists.', '.
                    '`double_kills` = '.$champStats->double_kills.', '.
                    '`triple_kills` = '.$champStats->triple_kills.', '.
                    '`quadra_kills` = '.$champStats->quadra_kills.', '.
                    '`penta_kills` = '.$champStats->penta_kills.', '.
                    '`gold_earned` = '.$champStats->gold_earned.', '.
                    '`cs` = '.$champStats->cs.
                    ' WHERE chid = '.$chid.' AND sid = '.$sid.' AND region = "'.$region.'"');
                $this->db->simple_query($query);
            } else {
                $query = $this->db->query('INSERT INTO `summoner_stats`(`region`, `sid`, `chid`, `games`, `wins`, `losses`, `win_per`, `kills`, `deaths`, `assists`,
                `double_kills`, `triple_kills`, `quadra_kills`, `penta_kills`, `gold_earned`, `cs`) VALUES('.
                    '"'.$region.'", '.
                    $sid.', '.
                    $chid.', '.
                    $champStats->games.', '.
                    $champStats->wins.', '.
                    $champStats->losses.', '.
                    ($champStats->wins / $champStats->games * 100).', '.
                    $champStats->kills.', '.
                    $champStats->deaths.', '.
                    $champStats->assists.', '.
                    $champStats->double_kills.', '.
                    $champStats->triple_kills.', '.
                    $champStats->quadra_kills.', '.
                    $champStats->penta_kills.', '.
                    $champStats->gold_earned.', '.
                    $champStats->cs.')');
                $this->db->simple_query($query);
            }
        }

        $this->Log_model->updateLog('summoner_stats', time(), $region, $sid);
    }

}