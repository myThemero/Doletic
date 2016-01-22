<?php

require_once "managers/DBManager.php";

class DB {

	// -- attributes
	private $dbmanager;
	private $dns;
	private $username;
	private $passwd;
	private $pdo;
	private $connected;

	// -- functions

	public function __construct(&$dbmanager, $dbengine, $dbhost, $dbname) {
		$this->dbmanager = $dbmanager;
		$this->dns = $dbengine.':dbname='.$dbname.";host=".$dbhost;
		$this->connected = false;
	}

	public function Connect($username, $passwd) {
		$this->user = $username;
		$this->pass = $passwd;
		try {
			$this->pdo = new PDO($this->dns, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
			$this->connected = true;
		} catch(PDOException $e) {
			die($e);
		}	
	}

	public function CloseConnection() {
		$this->pdo = null;
		$this->connected = false;
	}

	public function IsConnected() {
		return $this->connected;
	}

	public function ExecuteQuery($sql) {
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("ExecuteQuery:sql: " . $sql);
		} else {
			return $this->pdo->exec($sql);
		}
	}

	public function PrepareExecuteQuery($sql, $sql_params) {		
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("PrepareExecuteQuery:sql: " . $sql);
			var_dump("PrepareExecuteQuery:sql_params: " . $sql_params);
		} else {
			$pdos = $this->pdo->prepare($sql);
			return $pdos->execute($sql_params);	
		}
	}

	public function ResultFromQuery($sql, $sql_params) {
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("PrepareExecuteQuery:sql: " . $sql);
			var_dump("PrepareExecuteQuery:sql_params: " . $sql_params);
		} else {
			$pdos = $this->pdo->prepare($sql);
			$pdos->execute($sql_params);
			return $pdos;
		}
	}
}