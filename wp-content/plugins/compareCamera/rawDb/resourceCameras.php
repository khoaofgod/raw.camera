<?php

namespace rawDb;
use phpKb;
use phpNoSQL;
use rawDb;

class resourceCameras extends rawDb\remote {

    var $tableName = "resources";
    var $primaryKey = "resource_id";
    var $connection = "camera";

    public function importRemote($q = "") {
        $keywords = empty($q) ? array("canon","nikon","sony","olympus","panasonic","samsung","leica","fujifilm","sigma") : array($q);
        foreach($keywords as $q) {
            $url = "http://api.imaging-resource.com/search/camera/?q=".urlencode($q);
            $Curl = new phpKb\Curl();
            $list = json_decode($Curl->get($url),true);
            if($this->remoteDataProblem($url, $list,"items")) {
                return false;
            }
            foreach($list['items'] as $item) {
                $this->importRowData($item, $this->getColumns(), array("model_name"    => $item['model_name']));
            }
        }
        return true;
    }

    public function findCamera($q) {
	    return $this->getAll(array("model_name like ? OR short_name like ?", array("%{$q}%","%{$q}%")),
		    "DESC",
		    100);
    }

	/**
	 * @param $resource_id
	 *
	 * @return array
	 * Import Groups / Info for Camera Resource
	 */
	public function getResourceInfo($resource_id) {
		$resource = $this->get($resource_id);
		if(!empty($resource)) {
			$review_url = "http://www.imaging-resource.com/".ltrim($resource['review_url'],"/");
			$specs_url = str_replace("A.HTM","DAT.HTM",$review_url);
			$cURL = new phpKb\Curl();
			$html = $cURL->safeHtml($cURL->get($specs_url));
			$dom = new phpKb\Dom($html);
			$table = $dom->getElement(".spec-table");
			$groupDAO = new groups();
			$infoDAO = new info();

			$infoTypesDAO = new infoTypes();
			$objectTypesDAO = new objectTypes();
			if(!empty($table)) {
				$group = "";
				$display_id = 1;
				foreach($table->find("tr") as $tr) {
					$class = $tr->class;
					if($class == 'section-start') {
						// found group
						$group_name = $tr->find("th",0)->plaintext;
						$display_id = 1;
						$group = $groupDAO->insertIgnore(array(
							"group_name"    => $group_name,
							"info_type_id"  => $infoTypesDAO::CAMERA
						));

					} elseif(!empty($group)) {
						// starting import value
						$info_obj = $tr->find("th",0);
						$info_value = $tr->find("td",0);
						if($info_obj && $info_value) {
							$display_id++;
							$infoValueDAO = new infoValues();

							$info_name = $infoDAO->safeInfoName($info_obj->plaintext);
							$info_value = $infoValueDAO->safeValue($info_value->innertext);

							$info = $infoDAO->set(array(
								"info_name" => $info_name,
								"display_order" => $display_id,
								"info_type_id"  => $infoTypesDAO::CAMERA
							), array(
								"info_name" => $info_name,
								"info_type_id"  => $infoTypesDAO::CAMERA
							));

							$value = $infoValueDAO->set(array(
								"object_id" => $resource_id,
								"object_type_id" => $objectTypesDAO::RESOURCES,
								"info_id" => $info['info_id'],
								"info_value" => $info_value,
								"waiting_confirm" => false,
							), array(
								"object_id" => $resource_id,
								"object_type_id" => $objectTypesDAO::RESOURCES,
								"info_id" => $info['info_id'],
							));

							$res = $value[$infoValueDAO->getPrimaryKey()] > 0 ? "GOOD" : "BAD";


							echo "{$info_name} => {$info['info_id']} => {$info_value} {$value['linking_id']} | {$resource_id} | {$res} <br>";

						} else {
							$this->markError("No info_obj No info_value", $resource_id);
						}

					}

				}
				echo $table;
			} else {
				$this->markError("Can not find Specs Table", $resource_id);
			}
		}

	    return array();
    }



}