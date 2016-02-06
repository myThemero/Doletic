<?php

require_once "objects/DBTable.php";
require_once "managers/DBManager.php";
require_once "managers/SettingsManager.php";

/**
* @brief
*/
abstract class AbstractDBObject {

	// -- attributes
	private $module;
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
	public function ClearDB() {
		foreach ($this->tables as $tableName => $table) {
			// drop table if exists
			$this->db_connection->ExecuteQuery($table->GetDROPQuery());
		}
	}
	/**
	 *	@brief Deletes all tables and re-creates them after 
	 */
	public function ResetDB() {
		// drop all tables
		$this->ClearDB();
		// re-create or create tables
		foreach ($this->tables as $tableName => $table) {
			// create table if not exists
			$this->db_connection->ExecuteQuery($table->GetCREATEQuery());
		}
		$this->ResetStaticData();
	}
	/**
	 *	@brief Creates missing tables for all objects in database
	 */
	public function UpdateDB() {
		foreach ($this->tables as $tableName => $table) {
			// create table if not exists
			$this->db_connection->ExecuteQuery($table->GetCREATEQuery());
		}
	}

	public function GetModule() {
		return $this->module;
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	abstract public function GetServices($currentUser);
	/**
	 *	@brief Drops and recreate static data tables related to this object
	 *
	 *	---------!---------!---------!---------!---------!---------!---------!---------!---------
	 *  !  							DATABASE CONSISTENCY WARNING 							    !
	 *  !  																					    !
	 *  !  Please respect the following points :   												!
	 *	!  - When adding static data to existing data => always add at the end of the list.     !
	 *  !  - Never remove data (or ensure that no database element use one as a foreign key).   !
	 *	---------!---------!---------!---------!---------!---------!---------!---------!---------
	 */
	abstract public function ResetStaticData();

	public function SetDBConnection($dbConnection) {
		$this->db_connection = $dbConnection;
	}

# PROTECTED & PRIVATE #########################################################

	protected function __construct($module, $name) {
		$this->module = $module;
		$this->db_connection = null;
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