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
	const KEY_INDEX = "index";
	// --- data types
	const DT_INT = "int";
	const DT_VARCHAR = "varchar";
	const DT_TEXT = "text";
	const DT_DATE = "date";
	const DT_DATETIME = "datetime";
	const DT_BOOLEAN = "boolean";
	// --- actions
	const DT_CASCADE = "CASCADE";
	const DT_NO_ACTION = "NO_ACTION";
	const DT_RESTRICT = "RESTRICT";
	const DT_SET_NULL = "SET_NULL";
	// --- JOIN
	const DT_LEFT = "LEFT";
	const DT_RIGHT = "RIGHT";
	const DT_CROSS = "CROSS";
	const DT_INNER = "INNER";
	const DT_OUTER = "OUTER";
	const DT_NATURAL = "NATURAL";
	// --- UNION
	const DT_ALL = "ALL";
	// --- COUNT
	const DT_COUNT = "count";
	const DT_COUNT_COLUMN = "COUNT(*)";
	// --- query consts
	const SELECT_ALL = "*";
	const EVERYWHERE = "everywhere";
	const FULL_INSERT = "full_insert";
	const ALL_BUT_ID = "all_but_id";
	const ID_ONLY = "id_only";
	const ORDER_DESC = "DESC";
	const ORDER_ASC = "ASC";
	const OP_EQUALS = "=";
	const OP_GREATER = ">";
	const OP_GREATER_EQ = ">=";
	const OP_LESS = "<";
	const OP_LESS_EQ = "<=";
	// --- Default column names
	const COL_DISABLE_TSMP = "disable_timestamp";
	const COL_RESTORE_TSMP = "restore_timestamp";
	// -- attributes
	private $name = null;
	private $engine = null;
	private $columns = null;
	private $foreign = null;
	private $unique = null;
	// -- functions

	/**
	 *
	 */
	public function __construct($name, $engine = "InnoDB") {
		$this->name = $name;
		$this->engine = $engine;
		$this->columns = array();
		$this->foreign = array();
		$this->unique = array();
	}
	/**
	 *
	 */
	public function GetName() {
		return $this->name;
	}
	/**
	 *	Add a column to SQL table
	 */
	public function AddColumn($name, $dataType, $size = -1, $canBeNullFlag = true, $defaultValue = "", 
							  $autoIncFlag = false, $primaryKeyFlag = false, $index = false) {
		array_push($this->columns, array(DBTable::KEY_NAME => $name,
										DBTable::KEY_DATATYPE => $dataType,
										DBTable::KEY_SIZE => $size,
										DBTable::KEY_NULL => $canBeNullFlag,
										DBTable::KEY_DEFAULT => $defaultValue,
										DBTable::KEY_AUTO => $autoIncFlag,
										DBTable::KEY_PRIMARY => $primaryKeyFlag,
										DBTable::KEY_INDEX => $index));
		return $this;
	}
	/**
	 *	Add a foreign key to SQL table
	 */
	public function AddForeignKey($fkName, $tableColumnName, $refTableName, $refTableColumnName, $onDelete = DBTable::DT_RESTRICT, $onUpdate = DBTable::DT_RESTRICT) {
		array_push($this->foreign, array($fkName, $tableColumnName, $refTableName, $refTableColumnName, $onDelete, $onUpdate));
		return $this;
	}
	/**
	 *
	 */
	public function AddUniqueColumns($uniqueColumns) {
		array_push($this->unique, $uniqueColumns);
		return $this;
	}
	/**
	 *	Returns SQL CREATE query for this table
	 */
	public function GetCREATEQuery() {
		$primaryKeys = array();
		$index = array();
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
			// column index
			if($column[DBTable::KEY_INDEX]) {
				array_push($index, $column[DBTable::KEY_NAME]);
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
		// table primary keys
		if(sizeof($index) > 0) {
				foreach ($index as $key) {
					$query .= ", INDEX `". $key ."_FI` (";
					$query .= "`".$key."`),";
				}
				$query = trim($query, ",");
		} else {
			$query = trim($query, ",");
		}
		// add foreign keys
		if(sizeof($this->foreign) > 0) {
			foreach ($this->foreign as $foreignKeyRecord) {
				$query .= ", CONSTRAINT " . $foreignKeyRecord[0] ." FOREIGN KEY (`".$foreignKeyRecord[1]."`) REFERENCES `".$foreignKeyRecord[2]."`(`".$foreignKeyRecord[3]."`) ON DELETE " . $foreignKeyRecord[4] . " ON UPDATE ".$foreignKeyRecord[5];
			}
		}
		// add unique columns
		if(sizeof($this->unique) > 0) {
			foreach($this->unique as $columns) {
				$query .= ", UNIQUE(";
				foreach($columns as $col) {
					$query .= $col . ",";
				}
				$query = rtrim($query, ",");
				$query .= ")";
			}
		}
		// table engine
		$query .= ") ENGINE=".$this->engine." CHARSET=utf8;";
		echo "\n TABLE : " . $this->name . "\n";
		// return query
		return $query;
	}
	/**
	 *	Returns SQL DROP query for this table
	 */
	public function GetDROPQuery($ignoreForeign = false) {
		if($ignoreForeign) {
			return "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `".$this->name."`; SET FOREIGN_KEY_CHECKS = 1;";
		}
		return "DROP TABLE IF EXISTS `".$this->name."`;";
	}
	/**
	 *	Returns SQL SELECT query for this table
	 */
	public function GetSELECTQuery($select = array(DBTable::SELECT_ALL), 
								   $where = array(DBTable::EVERYWHERE),
								   $operator = array(), // supposed to be a array(column => [OP_EQUALS|OP_GREATER|OP_LESS|...], ...)
								   $orderby = array(), // supposed to be a array(column => [ORDER_DESC|ORDER_ASC],...)
								   $limit = -1,
								   $or = false,
								   $count = false,
								   $groupBy = null) {
		$link = " AND ";
		if($or) {
			$link = " OR ";
		}
		$query = "SELECT ";
		if($count) {
			if(isset($groupBy)) {
				$query .= $groupBy.", ";
			}
			$query .= "COUNT(";
		}
		// check if select all
		if(in_array(DBTable::SELECT_ALL, $select)) {
			$query .= "*";
		} else {
			foreach ($select as $column) {
				$query .= "`".$column."`,";
			}
			$query = trim($query, " ,");
		}
		if($count) {
			$query .= ") AS count";
		}
		$query.= " FROM `".$this->name."`";
		// check if not everywhere
		if(!in_array(DBTable::EVERYWHERE, $where)) {
			$query .= " WHERE ";
			foreach ($where as $column) {
				$col_operator = DBTable::OP_EQUALS;
				if(array_key_exists($column, $operator)) {
					$col_operator = $operator[$column];
				}
				$query .= "`".$column."`".$col_operator.":".$column.$link;
			}
			$query = substr($query, 0, strlen($query)-strlen($link));
		}
		// check if group by
		if(isset($groupBy)) {
			$query .= " GROUP BY `".$groupBy . "`";
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
	/**
	 *	Returns a SQL to copy a full row from between two tables sharing the same schema
	 */
	public function GetDISABLEQuery($disableTableName, 
									$autoRestore = null, 
									$disableColumnName = DBTable::COL_DISABLE_TSMP, 
									$restoreColumnName = DBTable::COL_RESTORE_TSMP) {
		return "INSERT INTO `".$disableTableName."` SELECT *, '" . date(DateTime::ISO8601) . "' AS " . $disableColumnName . 
				 ", '".$autoRestore."' AS '" . $restoreColumnName . "' FROM `".$this->name."` WHERE `id`=:id;";
	}

	public function GetRESTOREQuery($disableTableName) {
		$query = "INSERT INTO `".$this->name."` SELECT ";
		foreach($this->columns as $column) {
			$query .= $column[DBTable::KEY_NAME] . ",";
		}
		$query = substr($query, 0, strlen($query)-1);
		$query .= " FROM `" . $disableTableName . "` WHERE id =:id;";
		return $query;
	}

	public function GetLastOfEachQuery($key, $orderBy, $where = array(DBTable::EVERYWHERE), $operator = array(), $or = false) {
		$link = " AND ";
		if($or) {
			$link = " OR ";
		}
		$query = "SELECT m1.*
			FROM `" . $this->name . "` m1 
			LEFT JOIN `" . $this->name . "` m2
			ON (m1." . $key . " = m2." . $key . " AND m1." . $orderBy . " < m2." . $orderBy . ")";
		$query .= " WHERE ";
		if(!in_array(DBTable::EVERYWHERE, $where) && !empty($where)) {
			foreach ($where as $column) {
				$col_operator = DBTable::OP_EQUALS;
				if(array_key_exists($column, $operator)) {
					$col_operator = $operator[$column];
				}
				$query .= "m1.`".$column."`".$col_operator.":".$column.$link;
			}
		}
		$query .= "m2.id IS NULL";
		return $query;
	}

	static function GetJOINQuery($query1, $query2, $joinOn = array(), $type = DBTable::DT_INNER, $leftRight = "", $count=false, $groupBy = null , $joinOperator = array(), $orderby = array()) {
		if(empty($joinOn)) {
			$type = DBTable::DT_NATURAL;
		}
		$query = "SELECT ";
		if($count) {
			if(isset($groupBy)) {
				$query .= $groupBy.", ";
			}
			$query .= "COUNT(*) AS count";
		} else {
			$query .= "*";
		}
		$query .= " FROM (" . trim($query1, ';').") AS a " . $leftRight . " " . $type . " JOIN (".trim($query2, ';').") AS b";
		if(!empty($joinOn)) {
			$query .= " ON ";
			$allEquals = empty($joinOperator);
			for($i = 0; $i<count($joinOn); $i += 2) {
				if($i > 0) {
					$query .= " AND ";
				}
				$query .= "a." . $joinOn[$i];
				if($allEquals) {
					$query .= "=";
				} else {
					$query .= $joinOperator[($i/2)];
				}
				$query .= "b." . $joinOn[($i+1)] . " ";
			}
		}
		// check if order by
		if(sizeof($orderby) > 0) {
			$query .= " ORDER BY ";
			foreach ($orderby as $column => $order) {
				$query .= "`".$column."` ".$order.",";
			}
			$query = trim($query, " ,");
		}
		if(isset($groupBy)) {
			$query .= " GROUP BY `".$groupBy . "`";
		} 
		$query .= ";";
		return $query;
	}

	static function GetUNIONQuery($query1, $query2, $all = true) {
		$query = $query1;
		if(strpos($query1, "SELECT") == 0 && strpos($query2, "SELECT") == 0) {
			$query = "(" . trim($query1, ';') . ") UNION ";
			if($all) {
				$query .= DBTable::ALL . " ";
			}
				$query .= "(" . trim($query2, ';') . ");";
		}
		return $query;
	}

}