<?php

use \PDO;

class DB {

	// -- attributes
	private $dns;
	private $username;
	private $passwd;
	private $pdo;
	private $connected;

	// -- functions

	public function __construct($dbengine, $dbhost, $dbname) {
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

	public function PrepareExecuteQuery($sql, $sql_params) {		
		$pdos = $this->pdo->prepare($sql);
		return $pdos->execute($sql_params);
	}

	public function ResultFromQuery($sql, $sql_params) {
		$pdos = $this->pdo->prepare($sql);
		$pdos->execute($sql_params);
		return $pdos;
	}
}