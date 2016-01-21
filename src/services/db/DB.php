<?php

use \PDO;

class DB {

	// -- attributes
	private $dns;
	private $username;
	private $passwd;
	private $pdo;

	// -- functions

	public function __construct($dbengine, $dbhost, $dbname) {
		$this->dns = $dbengine.':dbname='.$dbname.";host=".$dbhost;
	}

	public function Connect($username, $passwd) {
		$this->user = $username;
		$this->pass = $passwd;
		try {
			$this->pdo = new PDO($this->dns, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
		} catch(PDOException $e) {
			die($e);
		}
		
	}

	public function CloseConnection() {
		$this->pdo = null;
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