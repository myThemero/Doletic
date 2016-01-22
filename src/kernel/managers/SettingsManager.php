<?php

require_once "managers/AbstractManager.php";

/**
* 	@brief
*/
class SettingsManager extends AbstractManager {

	// -- consts
	// --- files
	const CONFIGURATION_FILE = "settings.ini";
	// --- keys
	// ---- database related keys
	const KEY_DBENGINE = "dbengine";
	const KEY_DBNAME = "dbname";
	const KEY_DBHOST = "dbhost";
	const KEY_DBUSER = "dbuser";
	const KEY_DBPWD = "dbpwd";
	const KEY_DBDEBUG = "dbdebug";

	// -- attributes
	private $settings;

	// -- functions

	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->settings = array();
	}

	public function Init() {
		if(file_exists(SettingsManager::CONFIGURATION_FILE)) {
			$this->settings = parse_ini_file(SettingsManager::CONFIGURATION_FILE);
		} else {
			die("Configuration file is missing or can't be parsed !");
		}
	}

	public function RegisterSetting($key, $value) {
		$ok = false;
		if($this->settings[$key] == null) {
			$this->setting[$key] = $value;
			$ok = true;
		}
		return $ok;
	}

	public function UnregisterSetting($key) {
		$this->settings[$key] = null;
	}

	public function GetSettingValue($key) {
		return $this->settings[$key];
	}
}