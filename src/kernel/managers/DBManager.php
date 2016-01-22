<?php

require_once "managers/AbstractManager.php";
require_once "objects/DB.php";

/**
* 	@brief
*/
class DBManager extends AbstractManager {

	// -- attributes
	private $databases;

	// -- function

	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->databases = array();
	}

	public function Init() {
		// create main doletic database
		$db = new DB(
			$this,
			$this->kernel()->SettingValue(SettingsManager::KEY_DBENGINE), 
			$this->kernel()->SettingValue(SettingsManager::KEY_DBHOST), 
			$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME));
		// open connection
		$db->Connect(
			$this->kernel()->SettingValue(SettingsManager::KEY_DBUSER),
			$this->kernel()->SettingValue(SettingsManager::KEY_DBPWD));
		// register main doletic database 
		$this->RegisterDatabase(
			$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME), 
			$db);
	}

	public function RegisterDatabase($name, $db) {
		$ok = false;
		if(!array_key_exists($name, $this->databases)) {	
			$this->databases[$name] = $db;
			$ok = true;
		}
		return $ok;
	}

	public function GetOpenConnectionTo($name) {
		return $this->databases[$name];
	}

	public function DebuggingModeEnabled() {
		return $this->kernel()->SettingValue(SettingsManager::KEY_DBDEBUG);
	}

}