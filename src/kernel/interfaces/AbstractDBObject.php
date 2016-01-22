<?php

require_once "objects/DBTable.php";
require_once "managers/DBManager.php";
require_once "managers/SettingsManager.php";

/**
* @brief
*/
class AbstractDBObject {

	// -- attributes
	private $name;
	private $services;
	private $tables;

	// -- functions

	public function __construct($name, $services) {
		$this->name = $name;
		$this->services = $services;
		$this->tables = array();
	}

	public function GetName() {
		return $this->name;
	}

	/**
	 *	@brief Hard reset of database, drop tables and creates it again
	 */
	public function ResetDB($dbmanager, $dbname) {
		foreach ($this->tables as $tableName => $table) {
			// Drop table if exists
			$dbmanager->GetOpenConnectionTo($dbname)->ExecuteQuery($table->GetDROPQuery());
			// Create table if not exists
			$dbmanager->GetOpenConnectionTo($dbname)->ExecuteQuery($table->GetCREATEQuery());
		}
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function Services() {
		return $this->services;
	}

# PROTECTED & PRIVATE #########################################################

	protected function addTable($table) {
		$this->tables[$table->GetName()] = $table; 
	}

}