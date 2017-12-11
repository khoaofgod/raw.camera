<?php


namespace rawDb;
use phpNoSQL\table;


class errors extends table {

	var $tableName = "errors";
	var $primaryKey = "error_id";
	var $connection = "camera";

	function markError(table $object,  $error_text, $col_value = "") {
		try {

			$data = array(
				"table_name"    => $object->tableName,
				"table_col"      => $object->getPrimaryKey(),
				"col_value"     => $col_value,
				"error_text"    => $error_text,
				"error_date"    => @date("Y-m-d H:i:s"),
				"resolved_flag" => false
			);

			$id = $this->insert($data);

		} catch (\PDOException $e) {
			print_r($e);
		}

	}


	function markResolve($error_id) {

	}

}
