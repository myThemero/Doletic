<?php

class DBTable {

	// -- consts
	// --- keys
	const KEY_NAME = "name";
	const KEY_DATATYPE = "datatype";
	const KEY_SIZE = "size";
	const KEY_NULL = "null";
	const KEY_DEFAULT = "default";
	const KEY_AUTO = "auto";
	const KEY_PRIMARY = "primary";
	// --- data types
	const DT_INT = "int";
	const DT_VARCHAR = "varchar";
	const DT_TEXT = "text";
	// -- attributes
	private $name;
	private $engine;
	private $columns;
	// -- functions

	public function __construct($name, $engine = "InnoDB") {
		$this->name = $name;
		$this->engine = $engine;
		$this->columns = array();
	}

	public function SetTableName($name) {
		$this->name = $name;
	}
	public function GetName() {
		return $this->name;
	}

	public function AddColumn($name, $dataType, $size = -1, $canBeNullFlag = true, $defaultValue = "", 
							  $autoIncFlag = false, $primaryKeyFlag = false) {
		array_push($this->columns, array(DBTable::KEY_NAME => $name,
										DBTable::KEY_DATATYPE => $dataType,
										DBTable::KEY_SIZE => $size,
										DBTable::KEY_NULL => $canBeNullFlag,
										DBTable::KEY_DEFAULT => $defaultValue,
										DBTable::KEY_AUTO => $autoIncFlag,
										DBTable::KEY_PRIMARY => $primaryKeyFlag));
	}

	public function GetCREATEQuery() {
		$primaryKeys = array();
		$query = "CREATE TABLE IF NOT EXISTS `".$this->name."` (";
		foreach ($this->columns as $column) {
			$query .= "`".$column[DBTable::KEY_NAME]."` ".$column[DBTable::KEY_DATATYPE];
			// column size
			if($column[DBTable::KEY_SIZE] > 0) {
				$query .= "(".$column[DBTable::KEY_SIZE].")";
			}
			// column can be null flag
			if($column[DBTable::KEY_NULL]) {
				$query .= " NULL";
			} else {
				$query .= " NOT NULL";
			}
			// column default (if content is not empty)
			if(strcmp($column[DBTable::KEY_DEFAULT],"")) {
				$query .= " DEFAULT ".$column[DBTable::KEY_DEFAULT];
			}
			// column auto
			if($column[DBTable::KEY_AUTO]) {
				$query .= " AUTO_INCREMENT";
			}
			// column primary
			if($column[DBTable::KEY_PRIMARY]) {
				array_push($primaryKeys, $column[DBTable::KEY_NAME]);
			}
			$query .= ",";
		}
		// table primary keys
		if(sizeof($primaryKeys) > 0) {
			$query .= " PRIMARY KEY (";
				foreach ($primaryKeys as $key) {
					$query .= "`".$key."`,";
				}
				$query = trim($query, ",").")";
		} else {
			$query = trim($query, ",");
		}
		// table engine
		$query .= ") ENGINE=".$this->engine." CHARSET=utf8;";
		// return query
		return $query;
	}

	public function GetDROPQuery() {
		return "DROP TABLE IF EXISTS `".$this->name."`;";
	}	

	public function GetSELECTQuery($select, $where) {
		return "SELECT ".$select." FROM `".$this->name."` WHERE ".$where.";";
	}

	public function GetINSERTQuery() {
		$query = "INSERT INTO `".$this->name."` VALUES(";
		foreach ($this->columns as $column) {
			$query .=  ":".$column[DBTable::KEY_NAME].",";
		}
		return trim($query, ", ").");";
	}

	public function GetUPDATEQuery($set,$where) {
		return "UPDATE `".$this->name."` SET ".$set." WHERE ".$where.";";
	}

	public function GetDELETEQuery($where) {
		return "DELETE FROM `".$this->name."` WHERE ".$where.";";
	}

}