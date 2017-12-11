<?php

namespace rawDb;
use phpKb;
use phpNoSQL;

class remote extends phpNoSQL\table {

    public function remoteDataProblem($url, $json, $name = "") {
        $code = 0;
        if(!is_array($json)) {
            $code = 1;
        }
        elseif($name !="" && !isset($json[$name])) {
            $code = 2;
        } elseif($name == "" && count($json) <= 1) {
            $code = 3;
        }
        if($code > 0) {
            // seend email here
            mail("khoaofgod@gmail.com","Remote DATA Problem ".$code,$url);
            return true;
        }
        return false;
    }

    public function remoteImportAll() {
        $list_server = array("dxoCameras","dxoLens","resourceCameras");
        foreach($list_server as $class) {

            $class = "rawDb\\".$class;

            echo "Starting ".$class." ... ";
            $server = new $class();
            $res = $server->importRemote();
            if($res) {
                echo " OK <br>";
            } else {
                echo " FAILED <br>";
            }
        }
    }

    public function markError($error_value, $error_id = "") {
    	$error = new errors();
	    $error->markError($this,$error_value , $error_id);

    }


}