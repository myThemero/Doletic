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
			$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME),
			$this->kernel()->SettingValue(SettingsManager::KEY_DBUSER),
			$this->kernel()->SettingValue(SettingsManager::KEY_DBPWD));
		// register main doletic database 
		$this->RegisterDatabase($db);
	}

	public function InitAllConnections() {
		// connect all databases
		foreach ($this->databases as $dbname => $db) {
			$db->Connect();
		}
	}

	public function CloseAllConnections() {
		// disconnect all databases
		foreach ($this->databases as $dbname => $db) {
			$db->Disconnect();
		}
	}

	public function RegisterDatabase($db) {
		$ok = false;
		if(!array_key_exists($db->GetName(), $this->databases)) {	
			$this->databases[$db->GetName()] = $db;
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