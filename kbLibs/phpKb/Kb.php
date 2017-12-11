<?php
namespace phpKb;

use phpNoSQL\table;

class Kb {

    static function get($name, $default = "") {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] :
            (isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $default));
    }
    static function getCache($name) {

    }

    static function setCache($name, $value , $time = 3600) {

    }

    /**
     * @param array $data
     * @return string
     */
    static function encodeToken(array $data) {
        return base64_encode(json_encode($data));
    }

    /**
     * @param $token_string
     * @return mixed
     */
    static function decodeToken($token_string) {
        return json_decode(base64_decode($token_string),true);
    }

    /**
     * @param $data
     * @return string
     */
    static function md5Token($data) {
        return md5(json_encode($data));
    }

    /**
     * @param $md5_token
     * @param $data
     * @return bool
     */
    static function compareMd5Token($md5_token, $data) {
        return $md5_token === self::md5Token($data);
    }

	static function buildForm($data = array(), $options = array()) {
		$action = isset($options['action']) ? $options['action'] : (isset($_GET['action'])) ? $_GET['action'] : "";
		$confirm = isset($options['confirm']) ?  "confirm('Are you sure?')" : "true";
		$html = "<form action='{$action}' method=post enctype='multipart/form-data' onsubmit=\"return {$confirm};\" ><table >";
		foreach($data as $input) {
			$html .= "<div >
                       {$input}
                </div> ";
		}
		$html .= "</table><br> <input type='submit' value='Submit'></form>";
		echo $html;
	}

	static function buildInput($id, $label, $type, $default = "") {
		$html = "<tr><td>{$label}:</td><td>";
		$r = self::get($id,$default);
		switch ($type) {
			case "select":
				$default = str_replace("'{$r}'", "'{$r}' selected",$default);
				$html .= "<select  name={$id} id={$id} >{$default}</select>";
				break;
			case "file" :
				$html .=  " <input type=file name={$id} id={$id} > ";
				break;
			case "textarea":
				$html .=  " <textarea style='width: 700px;height:300px;' name={$id} id={$id} >{$default}</textarea> ";
				break;
			case "text":
			default:
				$html .=  " <input type=text name={$id} id={$id} value='{$r}' > ";
				break;
		}
		$html .= "</td></tr>";
		return $html;
	}


	static function buildSelectOption($data , $default = "") {
		$html = "";
		foreach($data as $key=>$value) {
			$selected = $default == $key ? "selected" : "";
			$html .= "<option value='{$key}' {$selected} > {$value} </option>";
		}
		return $html;
	}

	static function buildSelectOptionFromDAO(table $class, $extra = array()) {
		$dao = $this->_getDAO($dao_name);
		$sort_column = $this->getNameSortColumn($dao->table);
		$order_by = $sort_column != "" ? "ORDER BY {$sort_column} ASC" : "";
		$sql = "SELECT * FROM {$dao->table} {$order_by} ";
		$data = $dao->execute($sql)->getRows();
		$res = array();
		$tmp_pk = strtolower($dao->pk);
		foreach($data as $row) {
			$enabled = true;
			$tmp_value = null;
			foreach($row as $col=>$value) {
				// has name
				if(strpos($col,"_id") !== false && empty($tmp_pk)) {
					$tmp_pk = $col;
				}
				if(strpos($col,"first_name") !== false) {
					$tmp_value = $value;
				}
				if(strpos($col,"last_name") !== false) {
					$tmp_value .= ' '. $value;
				}

				if(strpos($col,"name") !== false && empty($tmp_value)) {
					$tmp_value = $value;
				}

				if(strpos($col, "enable") !== false) {
					$enabled = $value;
				}
			}
			if($enabled === true || $enabled === "t" || $enabled == 1) {
				$res[$row[$tmp_pk]] = $tmp_value;
			}
		}
		return $this->buildSelectOption((array("" => "---- ") + $extra + $res));
	}

	static function buildJSTable(table $class, $data, $options = array()) {
		$primary_key = strtolower($class->getPrimaryKey());
		$table = $class->tableName;
		$header = "";
		$tbody = "";
		$tmp = 0;
		$ids = 0;
		$special_update = "";
		$cols=array();

		foreach ($data as $row) {
			$tmp++;
			$color = $tmp % 2 == 0 ? "green" : "pink";
			$tbody .= "<tr class='bh_{$color}'>";
			$row_id = "";
			$c=0;
			foreach($row as $col=>$value) {
				$c++;
				if($tmp == 1) {
					$header .= "<th data-name='{$col}'>{$col}</th>";
				}
				$ids++;

				$edit = $col!=$primary_key ? true : false;
				if($col == $primary_key) {
					$row_id = $value;
					$special_update = $table.$primary_key.$row_id;
				}
				$width = "";
				if(strlen($value) < 10) {
					$width = strlen($value)*5 + 20;
					$width = "width:{$width}px;";
					if(strlen($value) < 1) {
						$width = "";
					}
				}
				$special_id = $table.$primary_key.$row_id.$col;
				if(!in_array($col,$cols)) {
					$cols[] = $col;
				}

				$tbody .= "<td data-primary-value='{$row_id}' data-table='{$table}' data-primary-key='{$primary_key}' data-name='{$col}' style='{$width}'>                                
                              ";
				$tbody .= " {$value}  ";
				$tbody .= "
                           </td>";
			}
			$tbody .="
                <td  data-primary-value='{$row_id}' data-primary-key='{$primary_key}'  data-table='{$table}' >                    
                    ";
			if($row_id!="" && !isset($options['viewOnly'])) {

			}

			$tbody .= "
                </td>
            </tr>";
		}
		$data_sql_id = "sql".rand(1000,9999).rand(1000,9999);

		$table = "<table class=table width='100%'><thead>{$header}<th></th></thead><tbody>{$tbody}</tbody></table>";
		echo $table;
	}

	public static function replaceTemplate($html, $tags_data) {
    	$keys = array();
    	$data = array();
    	foreach($tags_data as $key=>$value) {
		    $keys[] = $key;
		    $data[] = $value;
	    }
	    return str_replace($keys, $data, $html);
	}


}