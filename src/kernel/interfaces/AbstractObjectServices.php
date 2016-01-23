<?php

class AbstractObjectServices {

	// -- attributes 
	private $db_object;
	private $db_connection;

	// -- functions

	public function GetResponseData($action, $params) {
		die("This function must be overrided by child.");
	}

# PROTECTED & PRIVATE ###################################################

	protected function __construct(&$dbObject, &$dbConnection) {
		$this->db_object = $dbObject;
		$this->db_connection = $dbConnection;
	}

	protected function getDBConnection() {
		return $this->db_connection;
	}

	protected function getDBObject() {
		return $this->db_object;
	}

}