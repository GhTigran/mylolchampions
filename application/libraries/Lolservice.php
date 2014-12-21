<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lolservice {

    private $apiKey = 'e1d5865a-5748-40d3-8d02-57c75f83891a'; // Rate Limit: 3000 request(s) every 10 second(s), 180000 request(s) every 10 minute(s)
    private $apiUrl = 'http://prod.api.pvp.net/api/lol/';

    /**
     * Send request <br />
     * @param string $region <p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param string $apiVersion [optional] <p>
     * version of API to send request to. Ex.  'v1.1', 'v2.1'.
     * </p>
     * @param string $category <p>
     * General category of request. Possible values: 'champion', 'game', 'league', 'stats'.
     * </p>
     * @param bool $is_static [optional] <p>
     * Is data static or not. Possible values true, false
     * </p>
     * @param string $filter [optional] <p>
     * Filter results presented in this format: [Filter_name]/[Filter_value]. <br />
     * Example: 'by-summoner/28309376'
     * </p>
     * @param string $additional_filter [optional] <p>
     * Additional filter for request.
     * Possible values: 'recent', 'summary', 'ranked'
     * @param string $queryParam [optional] <p>
     * Optional query params like:  'freeToPlay=true'
     * </p>
     * @return mixed <p>
     * Response encoded in <i>json</i>
     * </p>
     */
    public function sendRequest($region, $apiVersion = 'v1.1', $category, $is_static = false, $filter = '', $additional_filter = '', $queryParam='') {

        $apiUrl = str_replace('prod', $region, $this->apiUrl);

        $requestQuery = implode('/', array(
            $apiUrl.($is_static?'static-data/':'').$region,
            $apiVersion,
            $category
        ));

        if($filter) {
            $requestQuery .= '/'.$filter;
        }
        if($additional_filter) {
            $requestQuery .= '/'.$additional_filter;
        }
        $requestQuery .= '?api_key='.$this->apiKey;
        if($queryParam) {
            $requestQuery .= '&'.$queryParam;
        }

        $response = file_get_contents($requestQuery);
        //@ToDo consider responses with errors like bad request or internal server error
        // Use $http_response_header global variable to get response status
        //@ToDo json_decode

        return json_decode($response);
    }

    /**
     * Get Champions List <br />
     * @param string $region [optional]<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * Consider to change it if you want to get active champions or free to play champions for specific region
     * </p>
     * @return array <p>
     * Array list of the champions
     * </p>
     */
    public function getChampions($region = 'na', $dataType='info') {
        $queryParam = 'locale=en_US&champData='.$dataType;
        $response = $this->sendRequest($region, 'v1.2', 'champion', true, '', '', $queryParam);

        return $response->data;
    }

    /**
     * Get Champions List <br />
     * @param string $region [optional]<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * Consider to change it if you want to get active champions or free to play champions for specific region
     * </p>
     * @return array <p>
     * Array list of the champions
     * </p>
     */
    public function getFreeChampions($region = 'na') {
        $queryParam = 'freeToPlay=true';
        $response = $this->sendRequest($region, 'v1.2', 'champion', false, '', '', $queryParam);
        return $response->champions;
    }

    /**
     * Get summoner recent games <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * Id of summoner
     * </p>
     * @return array <p>
     * List of the recent games this summoner has played
     * </p>
     */
    public function getRecentGames($region, $summonerId) {
        $response = $this->sendRequest($region, 'v1.3', 'game', false, 'by-summoner/'.$summonerId, 'recent');
        return $response->games;
    }

    /**
     * Get league data <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune', 'tr', 'br'.
     * </p>
     * @param int $summonerId <p>
     * Id of summoner
     * </p>
     * @return array <p>
     * League data
     * </p>
     * @ToDo test
     */
    public function getLeagueData($region, $summonerId) {
        $response = $this->sendRequest($region, 'v2.5', 'league', false, 'by-summoner/'.$summonerId);
        return $response;
    }

    /**
     * Get summoner summary <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * Id of summoner
     * </p>
     * @return array <p>
     * League data
     * </p>
     */
    public function getSummonerSummary($region, $summonerId) {
        $response = $this->sendRequest($region, 'v1.3', 'stats', false, 'by-summoner/'.$summonerId, 'summary');
        return $response->playerStatSummaries;
    }

    /**
     * Get summoner ranked stats <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * Id of summoner
     * </p>
     * @param string $season <p>
     * by default season 4
     * </p>
     * @return array <p>
     * Ranked stats
     * </p>
     */
    public function getSummonerRankedStats($region, $summonerId, $season = 'SEASON4') {
        $response = $this->sendRequest($region, 'v1.3', 'stats', false, 'by-summoner/'.$summonerId, 'ranked', 'season='.$season);
        return $response->champions;
    }

    /**
     * Get summoner masteries <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * Id of summoner
     * </p>
     * @return array <p>
     * League data
     * </p>
     */
    public function get_summoner_masteries($region, $summonerId) {
        $response = $this->sendRequest($region, 'v1.4', 'summoner', false, $summonerId, 'masteries');
        return $response->$summonerId->pages;
    }

    /**
     * Get summoner runes <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * Id of summoner
     * </p>
     * @return array <p>
     * League data
     * </p>
     */
    public function get_summoner_runes($region, $summonerId) {
        $response = $this->sendRequest($region, 'v1.4', 'summoner', false, $summonerId, 'runes');
        return $response->pages;
    }

    /**
     * Get summoner id <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param string $summoner_name <p>
     * summoner name
     * </p>
     * @return int <p>
     * Summoner id
     * </p>
     */
    public function get_summoner_id($region, $summoner_name) {
        $summoner_name = rawurlencode($summoner_name);
        $response = $this->sendRequest($region, 'v1.2', 'summoner', false, 'by-name/'.$summoner_name);
        return $response->id;
    }

    /**
     * Get summoner profile data by name <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param string $summoner_name <p>
     * summoner name
     * </p>
     * @return Object <p>
     * StdObject containing profile data
     * </p>
     */
    public function getSummonerByName($region, $summoner_name) {
        $summoner_name = rawurlencode($summoner_name);
        $response = $this->sendRequest($region, 'v1.4', 'summoner', false, 'by-name/'.$summoner_name);
        return $response;
    }

    /**
     * Get summoner profile data by id <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * summoner id
     * </p>
     * @return Object <p>
     * StdObject containing profile data
     * </p>
     */
    public function getSummonerById($region, $summonerId) {
        $response = $this->sendRequest($region, 'v1.4', 'summoner', false, $summonerId);
        return $response;
    }

    /**
     * Get summoner names <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param string $summonerIds <p>
     * comma-separated list of summoner ids (max. 40)
     * </p>
     * @return Array <p>
     * Array containing summoner ids and names
     * </p>
     */
    public function getSummonerNames($region, $summonerIds) {
        $response = $this->sendRequest($region, 'v1.4', 'summoner', false, $summonerIds, 'name');
        return $response->summoners;
    }

    /**
     * Get summoner teams <br />
     * @param string $region<p>
     * LoL Region. Possible values are: 'na', 'euw', 'eune'.
     * </p>
     * @param int $summonerId <p>
     * summoner id
     * </p>
     * @return Object <p>
     * list of teams
     * </p>
     */
    public function getSummonerTeams($region, $summonerId) {
        $response = $this->sendRequest($region, 'v2.2', 'team', false, 'by-summoner/'.$summonerId);
        return $response;
    }
}