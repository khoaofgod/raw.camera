<?php

namespace phpNoSQL\cores;

class drivers {

    public static function buildWhere($tableInfo, $where = array()) {
        $res = array(
            "where" => "",
            "exe"   => array()
        );

        if(!is_array($where)) {
        	if(is_numeric($where)) {
        		// allow where as primary number
		        $where = array(
			        $tableInfo['primaryKey'] => $where
		        );
	        } else {
        		// alow where as text
		        $res = array(
			        "where" => $where,
			        "exe"   => array()
		        );
		        return $res;
	        }
        } elseif(count($where) == 2 && isset($where[0]) && isset($where[1])) {
        	// allow where as array("text", array_mapping())
	        $res = array(
		        "where" => $where[0],
		        "exe"   => $where[1]
	        );
	        return $res;
        }

        foreach($where as $key=>$value) {
            $key = self::aliasPrimaryKey($key, $tableInfo);
            $exe_name = ":{$key}".rand(1,999);
            $tmp = explode(":",$key);
            $linking = "AND ";
            $operator = "=";
            if(count($tmp) > 1) {
                $linking = strtoupper($tmp[0]);
                $key = $tmp[1];
                if(isset($tmp[2])) {
                    $operator = $tmp[2];
                }
            }
            if(in_array(strtoupper($operator),array("IN","NOT IN"))) {
                $res['where'] .= "{$linking} {$key}{$operator} ({$value}) ";
            } else {
                $res['where'] .= "{$linking} {$key}{$operator}{$exe_name} ";
                $res['exe'][$exe_name] = $value;
            }

        }
        $res['where'] = substr( $res['where'],3);
        return $res;
    }

    public static function buildLimit($limit = "") {
        return $limit;
    }

    public static function buildOrderBy($tableInfo, $order_by = array()) {
        if(!is_array($order_by)) {
            return $order_by;
        }
        $res = "";
        foreach($order_by as $key=>$sort) {
            $res .= ",{$key} {$sort}";
        }
        return ltrim($res,",");
    }


    /**
     * @param $name
     * @param $tableInfo
     * @return mixed
     */
    public static function aliasPrimaryKey($name, $tableInfo = array()) {
        $primaryKey = $tableInfo['primaryKey'];
        if(in_array(strtolower($name), array("_id","primarykey"))) {
            return $primaryKey;
        }
        return $name;
    }

    public static function log($tableInfo, $sql, $s1 = "", $s2 = "", $s3 ="" , $s4 = "") {
    	$file = __DIR__."/../_logs/".@date("Y-m-d").".log";
		$mode = file_exists($file) ? "w+" : "a";
		$f = fopen($file,$mode);
		fwrite($f, $sql);
		if(is_string($s1)) {
			fwrite($f,$s1);
		} else {
			fwrite($f, print_r($s1,true));
		}
	    if(is_string($s2)) {
		    fwrite($f,$s2);
	    } else {
		    fwrite($f, print_r($s2,true));
	    }

		fclose($f);
    }

    public static function aliasTableInfoColumns($data = array()) {
        $res = array();
        foreach($data as $r) {
            if(isset($r['Field'])) {
                $r['name'] = $r['Field'];
            }
            if(isset($r['Type'])) {
                $r['type'] = $r['Type'];
            }
            $res[$r['name']] = $r;
        }
        return $res;
    }
}