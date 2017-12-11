<?php

namespace rawDb;
use phpNoSQL\table;

class info extends table {

	var $tableName = "info";
	var $primaryKey = "info_id";
	var $connection = "camera";


	public function safeInfoName ($string) {
		return trim(strtoupper(str_replace(array(":"),"",$string)));
	}
}