<?php


namespace rawDb;
use phpNoSQL\table;


class infoTypes extends table         { 


CONST CAMERA = 1;
CONST LENS = 2;

var $tableName = "info_types";
	var $primaryKey = "info_type_id";
	var $connection = "camera";



}