<?php

namespace phpNoSQL;
use phpNoSQL\cores;

cores\instances::$config = array(
    "default"   => array(
        "driver"    => "mysqlPdo",
        "hostname"  => "localhost",
        "username"  => "admin_rawdb",
        "password"  => "@123456",
        "database"  => "admin_rawdb",
        "port"      => 3366
    ),

    "sqlite"    => array(
        "driver"    => "sqlite",
        "database"  => __DIR__."/../database/rawDB"
    ),

	"camera" => array(
		"driver"    => "mysqlPdo",
		"hostname"  => "localhost",
		"username"  => "admin_camera",
		"password"  => "@XXX123456",
		"database"  => "admin_camera",
		"port"      => 3366
	)
);
