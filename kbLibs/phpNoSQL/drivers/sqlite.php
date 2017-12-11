<?php

namespace phpNoSQL\drivers;

use phpNoSQL\cores;
use phpNoSQL\drivers;

class sqlite extends drivers\mysqlPdo {


    /**
     * @param $connection_name
     * @return mixed
     */
    public static function getDB($connection_name) {
        if(isset(cores\instances::$connections[$connection_name]) && !empty(cores\instances::$connections[$connection_name])) {
            return cores\instances::$connections[$connection_name];
        } else {
            return self::setDB($connection_name);
        }
        return new \PDO();
    }

    /**
     * @param $connection_name
     * @return mixed
     */
    public static function setDB($connection_name) {
        $config = cores\instances::$config[$connection_name];

        cores\instances::$connections[$connection_name] = new \PDO('sqlite:'.$config['database']);
        cores\instances::$connections[$connection_name]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        cores\instances::$connections[$connection_name]->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        return cores\instances::$connections[$connection_name];
    }

    public static function getTableInfo($tableInfo) {
        $query = "PRAGMA table_info({$tableInfo['tableName']});";
        $stm = self::getDB($tableInfo['connection'])->prepare($query);
        $stm->execute();
        $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return self::aliasTableInfoColumns($data);
    }
}