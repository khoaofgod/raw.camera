<?php

namespace rawDb;
use phpNoSQL\table;

class infoValues extends table {

	var $tableName = "info_values";
	var $primaryKey = "linking_id";
	var $connection = "camera";


	public function safeValue($string) {
		return trim(str_replace(array("&nbsp;"),"",$string));
	}

}