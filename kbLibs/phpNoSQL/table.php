<?php
namespace phpNoSQL;
use phpNoSQL\cores;
use phpNoSQL\drivers;

require_once (__DIR__."/config.php");

class table {
    var $tableName = "";
    var $primaryKey = "";
    var $connection = "default";
    var $options = array();
    var $linking = array();
    var $current_data = array();


    /**
     * Connect to Database if not yet
     */
    public function getDriver() {
        $driver = cores\instances::$config[$this->connection]['driver'];
        $driver = "phpNoSQL\\drivers\\".$driver;
        return $driver;

        return new drivers\mysqlPdo();
    }

    public function db() {
        return $this->getDriver()::getDB($this->connection);
        return new \PDO();
    }

    public function getTableInfo() {
        return array(
            "tableName" => $this->tableName,
            "primaryKey"    => $this->getPrimaryKey(),
            "connection"    => $this->connection,
            "options"   => $this->options
        );
    }

    /**
     * table constructor.
     * @param null $tableName
     * @param null $primaryKey
     * @param null $connection
     * @param array $options
     */
    public function __construct($tableName = null, $primaryKey = null, $connection = null, $options = array())
    {
        if(!is_null($tableName)) {
            $this->tableName = $tableName;
        }
        if(!is_null($primaryKey)) {
            $this->primaryKey = $primaryKey;
        }
        if(!is_null($connection)) {
            $this->connection = $connection;
        }
        if(!empty($options)) {
            $this->options = $options;
        }
        $this->getDriver();
    }

    var $_limit = "";
    public function setLimit($limit) {
        $this->_limit = $limit;
        return $this;
    }

    var $_order_by = "";
    public function setOrderBy($order_by) {
        $this->_order_by = $order_by;
        return $this;
    }

    private function resetData() {
        $this->_limit = "";
        $this->_order_by = "";
        return $this;
    }

    /**
     * @param array $where
     * @return mixed
     */
    public function get($where = array()) {
        $order_by = $this->_order_by;
        $this->current_data = $this->resetData()->getDriver()::doSelect($this->getTableInfo(),$where, $order_by, 1);
        return $this->current_data;
    }

    /**
     * @param array $where
     * @param string $order_by
     * @param string $limit
     * @return mixed
     */
    public function getAll($where = array(), $order_by = "", $limit = "") {
        if(!empty($order_by)) {
            $this->setOrderBy($order_by);
        }
        if(!empty($limit)) {
            $this->setLimit($limit);
        }
        $order_by = $this->_order_by;
        $limit = $this->_limit;
        $this->current_data = $this->resetData()->getDriver()::doSelect($this->getTableInfo(), $where, $order_by, $limit);
        return $this->current_data;

    }

    /**
     * @param array $data
     * @param array $where
     * @return bool|int|mixed
     */
    public function set($data = array(), $where = array()) {
        $row = $this->get($where);
        if(isset($row[$this->getPrimaryKey()]) && !empty($row[$this->getPrimaryKey()])) {
            $this->update($data,$where, 1);
        } else {
        	print_r($data);
            $this->insert($data);
        }
        $this->current_data = $this->get($where);
        return $this->current_data;
    }

    public function insertIgnore(array $data) {
	    $row = $this->get($data);
	    if(isset($row[$this->getPrimaryKey()]) && !empty($row[$this->getPrimaryKey()])) {
		   // ignore
		    $this->current_data = $row;
	    } else {
		    $this->insert($data);
		    $this->current_data = $this->get($data);
	    }
	    return $this->current_data;
    }

    public function insert($data = array()) {
        $last_id = $this->resetData()->getDriver()::doInsert($this->getTableInfo(), $data);
        if(!empty($last_id)) {
        	$this->current_data = $this->get(array($this->getPrimaryKey() => $last_id));
        	return $this->current_data;
        }
        return $last_id;
    }

    public function getCurrentId() {
	    return isset($this->current_data[$this->getPrimaryKey()]) ? $this->current_data[$this->getPrimaryKey()] : null;
    }

    public function getLastInsertId() {
	    return $this->resetData()->getDriver()::getLastInsertId($this->getTableInfo());
    }

    public function query($sql, $data = array()) {
        return $this->getDriver()::doQuery($this->getTableInfo(),$sql, $data);
    }

    public function delete($where = array()) {
        return $this->getDriver()::doDelete($this->getTableInfo(), $where);
    }

    public function update($data = array(), $where = array(), $limit = null) {
        return $this->resetData()->getDriver()::doUpdate($this->getTableInfo(), $data, $where , $limit );
    }

    public function count($where = array()) {
        return $this->getDriver()::doCount($this->getTableInfo(),$where);
    }

    public function getPrimaryKey() {
        // may connect to table and get the key
        return $this->primaryKey;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function debug() {

    }

    public function getColumns() {
        return $this->getDriver()::getTableInfo($this->getTableInfo());

    }

    public function importRowData($data = array(), $columns = array(), $replace_by_data = array()) {
        if(empty($columns)) {
            $columns = $this->getColumns();
        }
        foreach($data as $column=>$value) {
            if(!isset($columns[$column])) {
                 $this->getDriver()::addColumn($this->getTableInfo(), $column, "VARCHAR(255)");
            }
        }
        if(empty($replace_by_data)) {
            $replace_by_data = $data;
        }
        $this->set($data,$replace_by_data);
    }
}