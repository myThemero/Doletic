<?php

require_once "objects/DBTable.php";
require_once "managers/DBManager.php";
require_once "managers/SettingsManager.php";

/**
* @brief
*/
abstract class AbstractDBService {

	// -- attributes
	private $modules = null;
	private $db_connection = null;
	private $name = null;
	private $db_tables = null;

	// -- functions

	/**
	 *	@brief Returns DBService name
	 */
	public function GetName() {
		return $this->name;
	}

	public function GetModules() {
		return $this->modules;
	}

	public function GetTable($tableName) {
		return $this->tables[$tableName];
	}

	public function GetAllTables() {
		return $this->tables;
	}

	abstract public function GetResponseData($action, $params);

	public function SetDBConnection($dbConnection) {
		$this->db_connection = $dbConnection;
	}

# PROTECTED & PRIVATE #########################################################

	protected function __construct($modules, $name) {
		$this->modules = $modules;
		$this->name = $name;
		$this->db_tables = array();
		foreach($this->modules as $module) {
			foreach($module->GetDBObjects() as $dbo) {
				foreach($dbo->GetAllTables() as $table) {
					$db_tables[$table->GetName()] = $table;
				}
			}
		}
	}

	protected function getCurrentUser() {
		return $this->current_user;
	}

	protected function getDBConnection() {
		return $this->db_connection;
	}

}