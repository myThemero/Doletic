<?php

class DBTable {

	// -- consts
	const KEY_NAME = "name";
	const KEY_DATATYPE = "datatype";
	const KEY_SIZE = "size";
	const KEY_NULL = "null";
	const KEY_DEFAULT = "default";
	const KEY_AUTO = "auto";
	const KEY_PRIMARY = "primary";
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

	public function AddColumn($name, $dataType, $size = -1, $canBeNullFlag = true, $defaultValue = "", 
							  $autoIncFlag = false, $primaryKeyFlag = false) {
		array_push($this->column, array(KEY_NAME => $name,
										KEY_DATATYPE => $dataType,
										KEY_SIZE => $size,
										KEY_NULL => $canBeNullFlag,
										KEY_DEFAULT => $defaultValue,
										KEY_AUTO => $autoIncFlag,
										KEY_PRIMARY => $primaryKeyFlag));
	}

	public function GetCREATEQuery() {
		$primaryKeys = array();
		$query = "CREATE TABLE IF NOT EXISTS ".$this->name."(";
		foreach ($this->columns as $column) {
			$query .= $column[KEY_NAME]." ".$column[KEY_DATATYPE];
			// column size
			if($column[KEY_SIZE] > 0) {
				$query .= "[".$column[KEY_SIZE]."]";
			}
			// column can be null flag
			if($column[KEY_NULL]) {
				$query .= " NULL";
			} else {
				$query .= " NOT NULL";
			}
			// column default
			if(sizeof($column[KEY_DEFAULT]) > 0) {
				$query .= " DEFAULT ".$column[KEY_DEFAULT];
			}
			// column auto
			if($column[KEY_AUTO]) {
				$query .= " AUTO_INCREMENT";
			}
			// column primary
			if($column[KEY_PRIMARY]) {
				array_push($primaryKeys, $column[KEY_NAME]);
			}
			$query .= ","
		}
		// table primary keys
		if(sizeof($primaryKeys) > 0) {
			$query .= " PRIMARY KEY (";
				foreach ($primaryKeys as $key) {
					$query .= $key.",";
				}
				$query = trim($query, ",").")";
		} else {
			$query = trim($query, ",");
		}
		// table engine
		$query .= ") ENGINE=".$this->engine;
		// return query
		return $query;
	}

	public function GetDROPQuery() {
		return "DROP TABLE IF EXISTS ".$this->name;
	}	

	public function GetSELECTQuery($select, $where) {
		return "SELECT ".$select." FROM ".$this->name." WHERE ".$where;
	}

	public function GetINSERTQuery() {
		$query = "INSERT INTO ".$this->name." VALUES(";
		foreach ($this->columns as $column) {
			$query .=  ":".$column[KEY_NAME].",";
		}
		return trim($query, ", ").")";
	}

	public function GetUPDATEQuery($set,$where) {
		return "UPDATE ".$this->name." SET ".$set." WHERE ".$where;
	}

	public function GetDELETEQuery($where) {
		return "DELETE FROM ".$this->name." WHERE ".$where;
	}

}