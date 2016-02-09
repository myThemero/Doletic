<?php

require_once "interfaces/AbstractManager.php";

/**
* 	This manager takes care of Doletic settings
*/
class SettingsManager extends AbstractManager {

	// -- consts
	// --- files
	const CONFIGURATION_FILE = "settings.ini";
	// --- keys
	// ---- kernel related keys
	const KEY_KERN_DEBUG = "kern_debug";
	const KEY_KERN_LOG = "kern_log";
	// ---- upload/download related keys
	const KEY_DOLETIC_DIR = "doletic_dir";
	// ---- database related keys
	const KEY_DBENGINE = "db_engine";
	const KEY_DBNAME = "db_name";
	const KEY_DBHOST = "db_host";
	const KEY_DBUSER = "db_user";
	const KEY_DBPWD = "db_pwd";
	const KEY_DBDEBUG = "db_debug";

	// -- attributes
	private $settings;

	// -- functions
	/**
	 *
	 */
	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->settings = array();
	}
	/**
	 *
	 */
	public function Init() {
		$this->Reload();
	}
	/**
	 *
	 */
	public function Reload() {
		if(file_exists(SettingsManager::CONFIGURATION_FILE)) {
			$this->settings = parse_ini_file(SettingsManager::CONFIGURATION_FILE);
		} else {
			die("Configuration file is missing or can't be parsed !");
		}
	}
	/**
	 *
	 */
	public function RegisterSetting($key, $value) {
		$ok = false;
		if($this->settings[$key] == null) {
			$this->setting[$key] = $value;
			$ok = true;
		}
		return $ok;
	}
	/**
	 *
	 */
	public function OverrideSetting($key, $value) {
		$this->setting[$key] = $value;
	}
	/**
	 *
	 */
	public function UnregisterSetting($key) {
		$this->settings[$key] = null;
	}
	/**
	 *
	 */
	public function GetSettingValue($key) {
		return $this->settings[$key];
	}
	/**
	 *
	 */
	public function GetAllSettings() {
		return $this->settings;
	}
}
