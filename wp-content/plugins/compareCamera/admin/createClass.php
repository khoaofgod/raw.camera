<?php

namespace rawDb;

use phpKb\Kb;
use phpNoSQL\table;
use rawDb;


require_once ( __DIR__ . "/_header.php");
require_once (__DIR__."/../rawDb/remote.php");
$table_name = Kb::get("table_name","");
$action = Kb::get("action","");

Kb::buildForm(array(
	Kb::buildInput("table_name","Enter Table Name","text", $table_name),
	Kb::buildInput("action","Select Action","select", Kb::buildSelectOption(array(
		"buildClass"    => "Build Class",
		"buildConst"   => "Build Const"
	), $action)),
));

class convertX {
	static function convertTableNameToClassName($table_name) {
		$t = explode("_", $table_name);
		$class_name = "";
		foreach($t as $i=>$name) {
			if($i > 0) {
				$name = ucwords(trim($name));
			}
			$class_name .= $name;
		}

		return $class_name;
	}

	static function getTableColumns($table_name) {
		$info = new errors();
		$info->tableName = $table_name;
		return $info->getColumns();
	}
}



if($table_name!="" && $action!="") {

	$class_name = convertX::convertTableNameToClassName($table_name);
	$columns = convertX::getTableColumns($table_name);
	$primary_key = "";
	$constant_name = "";
	$k = 0;
	foreach($columns as $column=>$column_data) {
		$k++;
		if($k == 1) {
			$primary_key = $column;
		}
		if($k == 2) {
			$constant_name = $column;
		}
	}
	$original_name = __DIR__."/../rawDb/{$class_name}.php";
	$default_file = file_get_contents(__DIR__."/../rawDb/default.php");
	switch($action) {
		case "buildClass":
			if($primary_key == "" || file_exists($default_file)) {
				echo " NO PRIMARY / No Default File";
				break;
			}
			if(file_exists($original_name)) {
				echo " FILE EXISTING ALREADY ";
				break;
			}


			$f = fopen($original_name, "w+");
			$new_file = str_replace(array(
				"myNameSpace" ,
				"defaultClassName",
				"table_name",
				"primary_key",
			), array("rawDb",$class_name,$table_name,$primary_key) ,$default_file);
			fwrite($f, $new_file);
			fclose($f);
			break;
		case "buildConst":
			if($constant_name == "" || !is_readable($original_name)) {
				echo "ERROR ";
				break;
			}

			$current_file = file_get_contents($original_name);
			$tmp = explode("{", $current_file, 2);
			$first_part = $tmp[0]." { \n";
			$tmp = explode('$tableName', $tmp[1],2);
			$second_part = 'var $tableName'.$tmp[1];

			// build const
			$class_name = 'rawDb\\'.$class_name;

			$class = new $class_name();
			$rows = $class->getAll();
			$const = "";
			foreach($rows as $row) {
				$const.= "\nCONST ".strtoupper($row[$constant_name])." = ".$row[$primary_key].";";
			}

			$new_data = $first_part."\n".$const."\n\n".$second_part;
			$f = fopen($original_name,"w+");
			fwrite($f, $new_data);
			fclose($f);

			echo "<br><br><a href='?page=".$_REQUEST['page']."&secret_filedownload_kb2=".urlencode($original_name)."'>Download File</a>";


			break;
	}

}

require_once (__DIR__."/_bottom.php");
