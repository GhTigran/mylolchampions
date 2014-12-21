<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grabimages extends LoLService {

    private $tiers = array( 'bronze', 'silver', 'gold', 'platinum', 'diamond', 'challenger');

    /**
     * Grab badge images <br />
     * @param string $localPath <p>
     * Path of the directory where images should be saved.
     * </p>
     * @param bool $rewrite <p>
     * If set to true, existing images will be rewrited
     * </p>
     * @return int <p>
     * Returns number of images grabbed
     * </p>
     */
    public function grabBadges($localPath = './img/badges/', $rewrite = false) {
        $counter = 0;
        foreach($this->tiers as $tier) {
            for($rank = 1; $rank <=5; $rank++) {
                if(!$rewrite && file_exists($localPath . $tier . '_' . $rank . '.png')) {
                    continue;
                }
                $headers = get_headers('http://www.lolking.net/images/medals/' . $tier . '_' . $rank . '.png');
                if(strpos($headers[0], '200')) {
                    $imgContent = file_get_contents('http://www.lolking.net/images/medals/'. $tier . '_' . $rank . '.png');
                    file_put_contents($localPath . $tier . '_' . $rank . '.png', $imgContent);
                } else {
                    break;
                }
                $counter++;
            }
        }
        return $counter;
    }

    /**
     * Grab champion icons <br />
     * @param string $localPath <p>
     * Path of the directory where images should be saved.
     * </p>
     * @param bool $rewrite <p>
     * If set to true, existing images will be rewrited
     * </p>
     * @param int $patch <p>
     * Which patch to align with
     * </p>
     * @return int <p>
     * Returns number of images grabbed
     * </p>
     */
    public function grabChampionIcons($localPath = './img/champion_icons/', $rewrite = false, $patch) {
        $counter = 0;
        $champions = $this->getChampions();
        foreach($champions as $champion) {
            ini_set('max_execution_time', 60);
            if(!$rewrite && file_exists($localPath . clearedName($champion->name) . '.png')) {
                continue;
            }
            $headers = get_headers('http://ddragon.leagueoflegends.com/cdn/'.$patch.'/img/champion/'. clearedName($champion->name) . '.png');
            if(strpos($headers[0], '200')) {
                $imgContent = file_get_contents('http://ddragon.leagueoflegends.com/cdn/'.$patch.'/img/champion/'. clearedName($champion->name) . '.png');
                file_put_contents($localPath . clearedName($champion->name) . '.png', $imgContent);
            } else {
                echo 'Icon for ' . $champion->name . ' not found <br />';
                continue;
            }
            $counter++;
        }
        return $counter;
    }

    /**
     * Grab champion splash arts <br />
     * @param string $localPath <p>
     * Path of the directory where images should be saved.
     * </p>
     * @param bool $rewrite <p>
     * If set to true, existing images will be rewrited
     * </p>
     * @return int <p>
     * Returns number of images grabbed
     * </p>
     */
    public function grabSplashArts($localPath = './img/splash_arts/', $rewrite = false) {
        $counter = 0;
        $champions = $this->getChampions();
        foreach($champions as $champion) {
            ini_set('max_execution_time', 60);
            if(!$rewrite && file_exists($localPath . strtolower($champion->name) . '.png')) {
                continue;
            }
            $headers = get_headers('http://ddragon.leagueoflegends.com/cdn/img/champion/splash/' . $champion->name . '_0.jpg');
            if(strpos($headers[0], '200')) {
                $imgContent = file_get_contents('http://ddragon.leagueoflegends.com/cdn/img/champion/splash/' . $champion->name . '_0.jpg');
                file_put_contents($localPath. strtolower($champion->name) . '.png', $imgContent);
            } else {
                continue;
            }
            $counter++;
        }
        return $counter;
    }

    /**
     * Grab champion skins' splash arts <br />
     * @param string $localPath <p>
     * Path of the directory where images should be saved.
     * </p>
     * @param bool $rewrite <p>
     * If set to true, existing images will be rewrited
     * </p>
     * @return int <p>
     * Returns number of images grabbed
     * </p>
     */
    public function grabSkinSplashArts($localPath = './img/skin_splash_arts/', $rewrite = false) {
        $counter = 0;
        $champions = $this->getChampions();
        foreach($champions as $champion) {
            ini_set('max_execution_time', 60);
            for($i = 0; $i < 20; $i++)
            {
                if(!$rewrite && file_exists($localPath . strtolower($champion->name) . '/'.$i.'.jpg')) {
                    continue;
                }
                $headers = get_headers('http://ddragon.leagueoflegends.com/cdn/img/champion/splash/' . $champion->name . '_'.$i.'.jpg');
                if(strpos($headers[0], '200')) {
                    if(!is_dir($localPath . strtolower($champion->name))) {
                        mkdir($localPath . strtolower($champion->name));
                    }
                    $imgContent = file_get_contents('http://ddragon.leagueoflegends.com/cdn/img/champion/splash/' . $champion->name . '_'.$i.'.jpg');
                    file_put_contents($localPath . strtolower($champion->name) . '/'.$i.'.jpg', $imgContent);
                } else {
                    break;
                }
                $counter++;
            }
        }
        return $counter;
    }
}