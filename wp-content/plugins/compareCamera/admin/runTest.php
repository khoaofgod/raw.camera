<?php

namespace rawDb;

use phpKb\Kb;

require_once ( __DIR__ . "/_header.php");
require_once (__DIR__."/../rawDb/remote.php");
$camera_name = Kb::get("camera_name","");
$resource_id = Kb::get("resource_id","");
Kb::buildForm(array(
	Kb::buildInput("camera_name","Enter Camera Name","text", $camera_name),
	Kb::buildInput("resource_id","Enter Resource ID","text", $resource_id)
));
$resourceCamera = new resourceCameras();

if($resource_id!="") {
	$resourceCamera->getResourceInfo($resource_id);
}

if($camera_name!="") {

	$rows = $resourceCamera->findCamera($camera_name);
	Kb::buildJSTable($resourceCamera, $rows);
}

require_once (__DIR__."/_bottom.php");
