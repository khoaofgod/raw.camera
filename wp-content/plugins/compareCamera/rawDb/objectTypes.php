<?php

namespace rawDb;
use phpNoSQL\table;

class objectTypes extends table  { 


CONST LISTCAMERA = 1;
CONST LISTLENS = 2;
CONST RESOURCES = 3;

var $tableName = "object_types";
	var $primaryKey = "object_type_id";
	var $connection = "camera";


}