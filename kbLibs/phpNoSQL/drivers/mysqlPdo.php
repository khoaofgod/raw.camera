<?php

namespace phpNoSQL\drivers;
use phpNoSQL\cores;

class mysqlPdo extends cores\drivers {

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
        cores\instances::$connections[$connection_name] = new \PDO('mysql:host='.$config['hostname'].
            ';port='.$config['port'].
            ';dbname='.$config['database'].
            ';charset=utf8mb4',
            $config['username'],
            $config['password']);
        cores\instances::$connections[$connection_name]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        cores\instances::$connections[$connection_name]->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        return cores\instances::$connections[$connection_name];
    }

    public static function doQuery($tableInfo, $sql, $data) {
        try {
            $stm = self::getDB($tableInfo['connection'])->prepare($sql);
            $stm->execute($data);
            return true;
        } catch (\PDOException $e) {
            // throw new \PDOException($e);
            return false;
        }
    }

    public static function doSelect($tableInfo, $where = array(), $order_by = "", $limit = "") {
    	if(in_array(strtoupper(trim($order_by)), array("ASC","DESC"))) {
		    $order_by = $tableInfo['primaryKey']. " {$order_by} ";
	    }
        $query = "SELECT * FROM {$tableInfo['tableName']} ";
        $exe = array();
        if(!empty($where)) {
            $whereClause = self::buildWhere($tableInfo, $where);
            $query .= " WHERE ".$whereClause['where'];
            $exe = array_merge($exe,$whereClause['exe']);
        }
        if(!empty($order_by)) {
            $query .= " ORDER BY ".self::buildOrderBy($tableInfo, $order_by);
        }
        if(!empty($limit)) {
            $query .= " LIMIT ".self::buildLimit($limit);
        }
        $fetchAll = (Int)$limit != 1;

        $stm = self::getDB($tableInfo['connection'])->prepare($query);
        $stm->execute($exe);
        return $fetchAll ? $stm->fetchAll(\PDO::FETCH_ASSOC) : $stm->fetch(\PDO::FETCH_ASSOC);
    }

    public static function doInsert($tableInfo, $data = array()) {
        $tableName = $tableInfo['tableName'];
        $primaryKey = $tableInfo['primaryKey'];
        $sql = "INSERT INTO `{$tableName}` ([columns]) VALUES ([values]);";
        $columns = "";
        $values = "";
        $insert_values = array();
        $primary_value = 0;
        foreach($data as $column=>$value) {
            $column = strtolower($column);
            $columns .= "{$column},";
            $values .= ":{$column} ,";
            if($primaryKey == $column) {
                $primary_value = $value;
            }
            $insert_values[":{$column}"] = $value;
        }
        $sql = str_replace(array("[columns]","[values]"),
            array(
                rtrim($columns,","),
                rtrim($values,",")
            ), $sql);

        try {
            $stm  = self::getDB($tableInfo['connection'])->prepare($sql);
            $stm->execute($insert_values);
            $r = self::getDB($tableInfo['connection'])->lastInsertId();
            if($r > 0 ) {
                return $r;
            }
            if($primary_value > 0 ) {
                return $primary_value;
            }
            return true;
        } catch (\PDOException $e) {
            self::log($tableInfo, $sql, $insert_values, print_r($e,true));
	        throw new \PDOException($e);
        }
        return false;
    }

    public static function getLastInsertId($tableInfo) {
	    return self::getDB($tableInfo['connection'])->lastInsertId();
    }

    public static function doUpdate( $tableInfo, $data = array(), $where = array() , $limit = "") {
        // print_r($data);
        $tableName = $tableInfo['tableName'];
        $limit = $limit!="" ? " LIMIT {$limit} " : "";

        $exe = array();
        $set = "";
        foreach($data as $column=>$value) {
            $column = strtolower(self::aliasPrimaryKey($column, $tableInfo));
            $exe_key = ":SET{$column}".rand(1,999);
            $set .= " {$column}={$exe_key} ,";
            $exe[$exe_key] = $value;

        }
        $query = "UPDATE {$tableName} SET [SET] ";
        if(empty($where)) {
            return false;
        }

        if(!empty($where)) {
            $whereClause = self::buildWhere($tableInfo, $where);
            $query .= " WHERE ".$whereClause['where'];
            $exe = array_merge($exe,$whereClause['exe']);
        }

        if(!empty($limit)) {
            $query .= " LIMIT ".self::buildLimit($limit);
        }


        $query = str_replace(array("[SET]"),
            array(
                rtrim($set,","),
            ), $query);

        try {
            $stm  = self::getDB($tableInfo['connection'])->prepare($query);
            $stm->execute($exe);
            return true;
        } catch (\PDOException $e) {
            self::log($tableInfo, $query, $exe, $e);
        }

        return false;
    }

    public static function doDelete($tableInfo, $where = array(), $limit = "") {
        if(empty($where)) {
            return false;
        }
        $exe = array();
        $query = "DELETE FROM {$tableInfo['tableName']} ";
        if(!empty($where)) {
            $whereClause = self::buildWhere($tableInfo, $where);
            $query .= " WHERE ".$whereClause['where'];
            $exe = array_merge($exe,$whereClause['exe']);
        }
        if(!empty($limit)) {
            $query .= " LIMIT ".self::buildLimit($limit);
        }

        try {
            $stm  = self::getDB($tableInfo['connection'])->prepare($query);
            $stm->execute($exe);
            return true;
        } catch (\PDOException $e) {
            self::log($tableInfo, $query, $exe, $e);
        }
        return false;
    }

    public static function doCount($tableInfo, $where = array()) {
        if(empty($where)) {
            return false;
        }
        $exe = array();
        $query = "SELECT COUNT({$tableInfo['primaryKey']}) as TOTAL FROM {$tableInfo['tableName']} ";
        if(!empty($where)) {
            $whereClause = self::buildWhere($tableInfo, $where);
            $query .= " WHERE ".$whereClause['where'];
            $exe = array_merge($exe,$whereClause['exe']);
        }

        try {
            $stm  = self::getDB($tableInfo['connection'])->prepare($query);
            $stm->execute($exe);
            $r = $stm->fetch(\PDO::FETCH_ASSOC);
            return $r['TOTAL'];
        } catch (\PDOException $e) {
            self::log($tableInfo, $query, $exe, $e);
        }
        return 0;
    }

    public static function addColumn($tableInfo, $column_name, $type) {
        $query = "ALTER TABLE {$tableInfo['tableName']} ADD COLUMN {$column_name} {$type};";
        $stm = self::getDB($tableInfo['connection'])->prepare($query);
        $stm->execute();
    }


    public static function getTableInfo($tableInfo) {
        $query = "DESCRIBE {$tableInfo['tableName']};";
        $stm = self::getDB($tableInfo['connection'])->prepare($query);
        $stm->execute();
        $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return self::aliasTableInfoColumns($data);

    }

}

