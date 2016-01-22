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

	/**
	 *	@brief Returns DBObject name
	 */
	public function GetName() {
		return $this->name;
	}

	/**
	 *	@brief Deletes all tables and re-creates them after 
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
	 *	@brief Creates missing tables for all objects in database
	 */
	public function UpdateDB($dbmanager, $dbname) {
		foreach ($this->tables as $tableName => $table) {
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

	/**
	 *	@brief Add a database table to object tables
	 */
	protected function addTable($table) {
		$this->tables[$table->GetName()] = $table; 
	}

}