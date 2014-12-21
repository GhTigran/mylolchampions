<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tigran
 * Date: 12/15/13
 */

class Log_model extends CI_Model {

    public function getLog($table, $region, $key = 0) {

        $query = 'SELECT * FROM `update_log` WHERE `table`="' . $table . '" AND `update_key` = "' . $key . '" AND region = "' . $region . '"';
        $query = $this->db->query($query);

        if($query->num_rows() > 0) {
            $logData = $query->row();
            return $logData;
        } else {
            return false;
        }
    }

    public function updateLog($table, $time, $region, $key = 0) {
        $logData = $this->getLog($table, $region, $key);
        if($logData === false) {
            $query = 'INSERT INTO `update_log` (`table`, `update_key`, `region`, `update_time`) VALUES' ."\n".
                '("' . $table . '", "' . $key . '", "' . $region . '", ' . $time.')';
        } else {
            $query = 'UPDATE `update_log` SET `update_time` = ' . $time . ' WHERE `table` = "' . $table . '" AND `update_key` = "' . $key.'" AND region = "' . $region . "\"\n";
        }
        $this->db->query($query);
    }
}