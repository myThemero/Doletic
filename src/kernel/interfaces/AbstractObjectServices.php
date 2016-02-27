<?php


abstract class AbstractObjectServices {

	// -- attributes
	private $current_user = null;
	private $db_object = null;
	private $db_connection = null;

	// -- functions

	abstract public function GetResponseData($action, $params);

	abstract public function ResetStaticData();

# PROTECTED & PRIVATE ###################################################

	protected function __construct(&$currentUser, &$dbObject, &$dbConnection) {
		$this->current_user = $currentUser;
		$this->db_object = $dbObject;
		$this->db_connection = $dbConnection;
	}

	protected function getCurrentUser() {
		return $this->current_user;
	}

	protected function getDBConnection() {
		return $this->db_connection;
	}

	protected function getDBObject() {
		return $this->db_object;
	}
}