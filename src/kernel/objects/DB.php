<?php

require_once "managers/DBManager.php";

/**
 *
 */
class DB {

	// -- attributes
	private $dbmanager = null;
	private $dns = null;
	private $name = null;
	private $username = null;
	private $passwd = null;
	private $pdo = null;
	private $connected = null;

	// -- functions

	public function __construct(&$dbmanager, $dbengine, $dbhost, $dbname, $username, $passwd) {
		$this->dbmanager = $dbmanager;
		$this->name = $dbname;
		$this->dns = $dbengine.':dbname='.$dbname.";host=".$dbhost;
		$this->user = $username;
		$this->pass = $passwd;
		$this->connected = false;
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
	public function IsConnected() {
		return $this->connected;
	}
	/**
	 *
	 */
	public function Connect() {
		try {
			$this->pdo = new PDO($this->dns, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For dev only
			$this->connected = true;
		} catch(PDOException $e) {
			die($e);
		}	
	}
	/**
	 *
	 */
	public function Disconnect() {
		$this->pdo = null; // close PDO connection
		$this->connected = false;
	}
	/**
	 *
	 */
	public function ExecuteQuery($sql) {
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("ExecuteQuery:sql: " . $sql);
		} else {
			return $this->pdo->exec($sql);
		}
	}

	/**
	 *
	 */
	public function PrepareExecuteQuery($sql, $sql_params) {		
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("PrepareExecuteQuery:sql: " . $sql);
			var_dump("PrepareExecuteQuery:sql_params: ");
			var_dump($sql_params);
			return true;
		} else {
			$pdos = $this->pdo->prepare($sql);
			return $pdos->execute($sql_params);	
		}
	}
	/**
	 *
	 */
	public function ResultFromQuery($sql, $sql_params) {
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("PrepareExecuteQuery:sql: " . $sql);
			var_dump("PrepareExecuteQuery:sql_params: ");
			var_dump($sql_params);
		} else {
			$pdos = $this->pdo->prepare($sql);
			$pdos->execute($sql_params);
			return $pdos;
		}
	}

	/**
	 *
	 */
	public function RawQuery($sql) {
		if($this->dbmanager->DebuggingModeEnabled()) {
			var_dump("RawQuery:sql: " . $sql);
		} else {
			return $this->pdo->query($sql);
		}
	}
}