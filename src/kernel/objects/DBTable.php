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
	const DT_DATE = "date";
	const DT_DATETIME = "datetime";
	const DT_BOOLEAN = "boolean";
	// --- query consts
	const SELECT_ALL = "*";
	const EVERYWHERE = "everywhere";
	const FULL_INSERT = "full_insert";
	const ALL_BUT_ID = "all_but_id";
	const ID_ONLY = "id_only";
	const ORDER_DESC = "DESC";
	const ORDER_ASC = "ASC";
	// -- attributes
	private $name = null;
	private $engine = null;
	private $columns = null;
	// -- functions

	/**
	 *
	 */
	public function __construct($name, $engine = "InnoDB") {
		$this->name = $name;
		$this->engine = $engine;
		$this->columns = array();
	}
	/**
	 *
	 */
	public function GetName() {
		return $this->name;
	}
	/**
	 *
	 */
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
	/**
	 *	Returns SQL CREATE query for this table
	 */
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
	/**
	 *	Returns SQL DROP query for this table
	 */
	public function GetDROPQuery() {
		return "DROP TABLE IF EXISTS `".$this->name."`;";
	}	
	/**
	 *	Returns SQL SELECT query for this table
	 */
	public function GetSELECTQuery($select = array(DBTable::SELECT_ALL), 
								   $where = array(DBTable::EVERYWHERE),
								   $orderby = array(), // supposed to be a array(column => [ORDER_DESC|ORDER_ASC],...)
								   $limit = -1,
								   $or = false) {
		$link = " AND ";
		if($or) {
			$link = " OR ";
		}
		$query = "SELECT ";
		// check if select all
		if(in_array(DBTable::SELECT_ALL, $select)) {
			$query .= "*";
		} else {
			foreach ($select as $column) {
				$query .= "`".$column."`,";
			}
			$query = trim($query, " ,");
		}
		$query.= " FROM `".$this->name."`";
		// check if not everywhere
		if(!in_array(DBTable::EVERYWHERE, $where)) {
			$query .= " WHERE ";
			foreach ($where as $column) {
				$query .= "`".$column."`=:".$column.$link;
			}
			$query = substr($query, 0, strlen($query)-strlen($link));
		}
		// check if order by
		if(sizeof($orderby) > 0) {
			$query .= " ORDER BY ";
			foreach ($orderby as $column => $order) {
				$query .= "`".$column."` ".$order.",";
			}
			$query = trim($query, " ,");
		}
		// check if limit
		if($limit > 0) {
			$query .= " LIMIT ".$limit;
		}
		$query .= ";";
		// return query
		return $query;
	}
	/**
	 *	Returns SQL INSERT query for this table
	 */
	public function GetINSERTQuery($columns = array(DBTable::FULL_INSERT)) {
		$query = "INSERT INTO `".$this->name."`";
		if(in_array(DBTable::FULL_INSERT, $columns)) {
			$query .= " VALUES(";
			foreach ($this->columns as $column) {
				$query .=  ":".$column[DBTable::KEY_NAME].",";
			}
		} else {
			$query .= "(";
			foreach ($columns as $column) {
				$query .=  "`".$column."`,";
			}

			$query = trim($query, " ,").") VALUES(";
			foreach ($columns as $column) {
				$query .=  ":".$column.",";
			}
		}
		return trim($query, ", ").");";
	}
	/**
	 *	Returns SQL UPDATE query for this table
	 */
	public function GetUPDATEQuery($set = array(DBTable::ALL_BUT_ID), $where = array(DBTable::ID_ONLY), $or = false) {
		// link between attributes for clause where
		$link = " AND ";
		if($or) {
			$link = " OR ";
		}
		// build query
		$query = "UPDATE `".$this->name."` SET ";
		// check if set argument is all but id
		if(in_array(DBTable::ALL_BUT_ID, $set)) {
			foreach ($this->columns as $column) {
				$query .=  "`".$column[DBTable::KEY_NAME]."`=:".$column[DBTable::KEY_NAME].",";
			}
		} else {
			foreach ($set as $column) {
				$query .=  "`".$column."`=:".$column.",";
			}
		}
		$query = trim($query, " ,") . " WHERE ";
		// check if where argument is all but id
		if(in_array(DBTable::ID_ONLY, $where)) {
			$query .= "`id`=:id";
		} else {
			foreach ($where as $column) {
				$query .=  "`".$column."`=:".$column.$link;
			}
			$query = substr($query, 0, strlen($query)-strlen($link));
		}
		return $query.";";
	}
	/**
	 *	Returns SQL DELETE query for this table
	 */
	public function GetDELETEQuery($where = array(DBTable::ID_ONLY), $or = false) {
		// link between attributes for clause where
		$link = " AND ";
		if($or) {
			$link = " OR ";
		}
		// build query
		$query = "DELETE FROM `".$this->name."` WHERE ";
		if(in_array(DBTable::ID_ONLY, $where)) {
			$query .= "`id`=:id";
		} else {
			foreach ($where as $column) {
				$query .=  "`".$column."`=:".$column.$link;
			}
			$query = substr($query, 0, strlen($query)-strlen($link));
		}
		return $query.";";
	}
	/**
	 *	Returns a SQL to copy a full row from between two tables sharing the same schema
	 */
	public function GetARCHIVEQuery($archiveTableName) {
		return "INSERT INTO `".$archiveTableName."` SELECT * FROM `".$this->name."` WHERE `id`=:id;";
	}
}