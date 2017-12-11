<?php

namespace rawDb;
use phpKb;
use phpNoSQL;
use rawDb;

class dxoCameras extends rawDb\remote {

    var $tableName = "listcamera";
    var $primaryKey = "camera_id";
    var $connection = "camera";


    public function importRemote() {
        $url = "https://www.dxomark.com/daklens/ajax/listcamera";
        $Curl = new phpKb\Curl();
        $list = json_decode($Curl->get($url),true);
        if($this->remoteDataProblem($url, $list)) {
            return false;
        }
        foreach($list as $camera) {
            $this->importRowData($camera, $this->getColumns(), array("model" => $camera['model']));
        }
        return true;
    }
}