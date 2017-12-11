<?php

namespace rawDb;
use phpKb;
use phpNoSQL;
use rawDb;

class dxoLens extends rawDb\remote {

    var $tableName = "listlens";
    var $primaryKey = "lens_id";
    var $connection = "camera";


    public function importRemote() {
        $url = "https://www.dxomark.com/daklens/ajax/jsonpreview";
        $Curl = new phpKb\Curl();
        $list = json_decode($Curl->get($url),true);
        if($this->remoteDataProblem($url, $list,"data")) {
            return false;
        }
        foreach($list['data'] as $lens) {
            $this->importRowData($lens, $this->getColumns(), array("name" => $lens['name']));
        }
        return true;
    }
}