<?php

namespace phpNoSQL\cores;

class instances {
    // [connection_name] = connection
    public static $connections = array();
    // [conection_name][table_name] = [info]
    public static $tableInfo = array();
    // config
    public static $config = array();

}