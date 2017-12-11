<?php


namespace rawDb;
use phpNoSQL\table;


class groups extends table {

	var $tableName = "groups";
	var $primaryKey = "group_id";
	var $connection = "camera";



}