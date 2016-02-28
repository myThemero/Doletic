<?php

require_once "interfaces/AbstractManager.php";

/**
* 	This manager takes care of Doletic settings
*/
class SettingsManager extends AbstractManager {

	// -- consts
	// --- files
	const CONFIGURATION_FILE = "settings.ini";
	// --- settings.ini keys
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

	// --- database keys
	// ---- mail related keys
	const DBKEY_MAIL_SMTP = "mail_smtp";
	const DBKEY_MAIL_USER = "mail_user";
	const DBKEY_MAIL_PASS  = "mail_pwd";
	// ---- JE related keys
	const DBKEY_JE_NAME 				= "je_name";
	const DBKEY_JE_WEBSITE_URL			= "je_website_url";
	const DBKEY_JE_LOGO_URL				= "je_logo_url";
	const DBKEY_JE_SCHOOL_NAME			= "je_school_name";
	const DBKEY_JE_DOMAIN 				= "je_domain";

	// -- database keys
	const DB_DEFAULT_SETTINGS = array(
		SettingsManager::DBKEY_MAIL_SMTP 			=> 'NULL',
		SettingsManager::DBKEY_MAIL_USER 			=> 'NULL',
		SettingsManager::DBKEY_MAIL_PASS 			=> 'NULL',
		SettingsManager::DBKEY_JE_NAME 				=> 'ETIC INSA Technologies',
		SettingsManager::DBKEY_JE_WEBSITE_URL 		=> 'http://www.etic-insa.com',
		SettingsManager::DBKEY_JE_LOGO_URL 			=> 'http://www.etic-insa.com/assets/logo-etic.png',
		SettingsManager::DBKEY_JE_SCHOOL_NAME 		=> 'INSA Lyon',
		SettingsManager::DBKEY_JE_DOMAIN 			=> 'etic-insa.com'
	);

	// -- attributes
	private $ini_settings = null;

	// -- functions
	/**
	 *
	 */
	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->ini_settings = array();
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
			$this->ini_settings = parse_ini_file(SettingsManager::CONFIGURATION_FILE);
		} else {
			die("Configuration file is missing or can't be parsed !");
		}
	}
	/**
	 *
	 */
	public function RegisterSetting($key, $value) {
		$ok = false;
		if(!array_key_exists($key, $this->ini_settings)) {
			$this->ini_settings[$key] = $value;
			$ok = true;
		}
		return $ok;
	}
	/**
	 *
	 */
	public function OverrideSetting($key, $value) {
		$this->ini_settings[$key] = $value;
	}
	/**
	 *
	 */
	public function UnregisterSetting($key) {
		$this->ini_settings[$key] = null;
	}
	/**
	 *
	 */
	public function GetSettingValue($key) {
		$value = null;
		if(array_key_exists($key, $this->ini_settings)) {
			$value = $this->ini_settings[$key];
		} else {
			$object = parent::kernel()->GetDBObject(SettingDBObject::OBJ_NAME);
			if(isset($object)) {
				$value = $object->GetServices(parent::kernel()->GetCurrentUser())
						->GetResponseData(SettingServices::GET_SETTING_BY_KEY, array(
							SettingServices::PARAM_KEY => $key));
			}
		}
		return $value; 
	}
	/**
	 *
	 */
	public function GetAllSettings() {
		return $this->ini_settings;
	}
}
