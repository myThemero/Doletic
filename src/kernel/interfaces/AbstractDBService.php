<?php

require_once "objects/DBTable.php";
require_once "managers/DBManager.php";
require_once "managers/SettingsManager.php";

/**
* @brief
*/
abstract class AbstractDBService {

	// -- attributes
	private $module = null;
	private $db_connection = null;
	private $name = null;
	private $current_user = null;

	// -- functions

	/**
	 *	@brief Returns DBObject name
	 */
	public function GetName() {
		return $this->name;
	}

	public function GetModule() {
		return $this->module;
	}

	abstract public function GetResponseData($action, $params);

	/**
	 *	@brief Returns all services associated with this object
	 */
	abstract public function GetServices($currentUser);

	public function SetDBConnection($dbConnection) {
		$this->db_connection = $dbConnection;
	}

# PROTECTED & PRIVATE #########################################################

	protected function __construct($module, $name, &$currentUser, &$db_connection) {
		$this->current_user = $currentUser;
		$this->module = $module;
		$this->db_connection = $db_connection;
		$this->name = $name;
	}

	protected function getCurrentUser() {
		return $this->current_user;
	}

	protected function getDBConnection() {
		return $this->db_connection;
	}

}