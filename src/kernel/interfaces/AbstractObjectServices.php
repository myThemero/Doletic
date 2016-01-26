<?php

abstract class AbstractObjectServices {

	// -- attributes 
	private $db_object;
	private $db_connection;

	// -- functions

	abstract public function GetResponseData($action, $params);

	abstract public function ResetStaticData();

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