<?php

require_once __DIR__."/kbLibs/libs.php";
use phpKb\Curl;


class listcamera extends phpNoSQL\table {
    var $tableName = "listcamera";
    var $primaryKey = "camera_id";
    var $connection = "sqlite";

    public function getCameraData() {
        $url = "https://www.dxomark.com/daklens/ajax/listcamera";
        $Curl = new phpKb\Curl();
        $list = json_decode($Curl->get($url),true);

        foreach($list as $camera) {
           $this->importRowData($camera, $this->getColumns(), array("model" => $camera['model']));
        }
    }
}

class listlens extends phpNoSQL\table {
    var $tableName = "listlens";
    var $primaryKey = "lens_id";
    var $connection = "sqlite";

    public function getLensData() {
        $url = "https://www.dxomark.com/daklens/ajax/jsonpreview";
        $Curl = new phpKb\Curl();
        $list = json_decode($Curl->get($url),true);

        foreach($list['data'] as $lens) {
            $this->importRowData($lens, $this->getColumns(), array("name" => $lens['name']));
        }
    }
}

$listcamera = new listcamera();
// $listcamera->getCameraData();

$listlens = new listlens();
$listlens->getLensData();


