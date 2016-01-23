<?php

require_once "objects/DBTable.php";
require_once "managers/DBManager.php";
require_once "managers/SettingsManager.php";

/**
* @brief
*/
class AbstractDBObject {

	// -- attributes
	private $db_connection;
	private $name;
	private $tables;

	// -- functions

	/**
	 *	@brief Returns DBObject name
	 */
	public function GetName() {
		return $this->name;
	}

	public function GetTable($tableName) {
		return $this->tables[$tableName];
	}

	/**
	 *	@brief Deletes all tables and re-creates them after 
	 */
	public function ResetDB() {
		foreach ($this->tables as $tableName => $table) {
			// Drop table if exists
			$this->db_connection->ExecuteQuery($table->GetDROPQuery());
			// Create table if not exists
			$this->db_connection->ExecuteQuery($table->GetCREATEQuery());
		}
	}

	/**
	 *	@brief Creates missing tables for all objects in database
	 */
	public function UpdateDB() {
		foreach ($this->tables as $tableName => $table) {
			// Create table if not exists
			$this->db_connection->ExecuteQuery($table->GetCREATEQuery());
		}
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices() {
		die("This function must be overrided in child.");
	}

# PROTECTED & PRIVATE #########################################################

	protected function __construct(&$dbConnection, $name) {
		$this->db_connection = $dbConnection;
		$this->name = $name;
		$this->tables = array();
	}

	protected function getDBConnection() {
		return $this->db_connection;
	}

	/**
	 *	@brief Add a database table to object tables
	 */
	protected function addTable($table) {
		$this->tables[$table->GetName()] = $table; 
	}

}