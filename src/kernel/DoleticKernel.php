<?php

require_once "managers/LogManager.php";
require_once "managers/CronManager.php";
require_once "managers/DBManager.php";
require_once "managers/ModuleManager.php";
require_once "managers/SettingsManager.php";
require_once "managers/AuthenticationManager.php";
require_once "managers/UIManager.php";
require_once "loaders/ModuleLoader.php";
require_once "loaders/DBObjectLoader.php";

/**
* 	@brief
*/
class DoleticKernel {

	// -- consts
	const INTERFACE_LOGIN = "login";
	const INTERFACE_LOGIN_FAILED = "login_failed";
	const INTERFACE_LOGOUT = "logout";
	const INTERFACE_404 = "404";
	const INTERFACE_HOME = "home";

	// -- attributes
	// --- managers
	private $log_mgr;
	private $cron_mgr;
	private $db_mgr;
	private $module_mgr;
	private $settings_mgr;
	private $authentication_mgr;
	private $ui_mgr;
	// --- loaders
	private $module_ldr;
	private $dbobject_ldr;
	// --- flags
	private $initialized;
	// --- internal
	private $special_uis;

	// -- functions

	public function __construct() {
		// -- create managers
		$this->log_mgr = new LogManager($this);
		$this->cron_mgr = new CronManager($this);
		$this->db_mgr = new DBManager($this);
	 	$this->module_mgr = new ModuleManager($this);
	 	$this->settings_mgr = new SettingsManager($this);
	 	$this->authentication_mgr = new AuthenticationManager($this);
	 	$this->ui_mgr = new UIManager($this);
	 	// -- create loggers
	 	$this->module_ldr = new ModuleLoader($this, $this->module_mgr);
	 	$this->dbobject_ldr = new DBObjectLoader($this, $this->db_mgr);
	 	// -- unset initialized flag
	 	$this->initialized = false;
	 	// -- special uis
	 	$this->special_uis = array(
	 		DoleticKernel::INTERFACE_LOGIN,
	 		DoleticKernel::INTERFACE_LOGIN_FAILED,
	 		DoleticKernel::INTERFACE_LOGOUT,
	 		DoleticKernel::INTERFACE_404,
	 		DoleticKernel::INTERFACE_HOME
	 		);
	}

	public function Init() {
		if(!$this->initialized) {
			// -- init managers
			$this->settings_mgr->Init();
			$this->info("Settings Manager initialized.");
			$this->log_mgr->Init();
			$this->info("Log Manager initialized.");
			$this->db_mgr->Init();
			$this->info("Database Manager initialized.");
			$this->cron_mgr->Init();
			$this->info("Cron Manager initialized.");
		 	$this->module_mgr->Init();
		 	$this->info("Module Manager initialized.");
		 	$this->authentication_mgr->Init();
		 	$this->info("Authentication Manager initialized.");
		 	$this->ui_mgr->Init();
		 	$this->info("User Interface Manager initialized.");
		 	// -- init loaders
		 	$this->module_ldr->Init();
		 	$this->info("Module Loader initialized.");
		 	$this->dbobject_ldr->Init();
		 	$this->info("Database Object Loader initialized.");
		 	// -- set initialized flag
		 	$this->initialized = true;	
		} else {
			$this->warn("Calling kernel initializer twice or more !");
		}
	}

	// --- log management --------------------------------------------------------------------

	public function Log($logger,$logMessage) {
		$this->log_mgr->Log($logger,$logMessage);
	}

	// --- settings management --------------------------------------------------------------------

	public function SettingValue($key) {
		return $this->settings_mgr->GetSettingValue($key);
	}

	// --- ui management --------------------------------------------------------------------

	public function GetInterface($ui) {
		$this->debug("Showing '" . $ui . "' interface.");
		$uiOut = null;
		// check if requested ui is a special ui
		if(in_array($ui, $this->special_uis)) {
			$uiOut = $this->ui_mgr->MakeUI($ui);
		} else {
			$uiOut = $this->ui_mgr->MakeUI($this->module_ldr->GetModule(explode(':', $ui)[0])->GetInterface($ui));
		}
		return $uiOut;
	}

	// --- cron management --------------------------------------------------------------------

	public function RunCron() {
		$this->cron_mgr->RunTasks();
	}

	// --- database management --------------------------------------------------------------------

	public function ResetDatabase() {
		$this->info("Reset database.");
		// -- build or rebuild database
		$this->dbobject_ldr->FullDBReset($this->db_mgr);
	}
	public function UpdateDatabase() {
		$this->info("Update database.");
		// -- update database
		$this->dbobject_ldr->FullDBUpdate($this->db_mgr);
	}
	public function ConnectDB() {
		$this->info("Connect DB.");
		$this->db_mgr->InitAllConnections();
	}
	public function DisconnectDB() {
		$this->info("Disconect DB.");
		$this->db_mgr->CloseAllConnections();
	}
	public function GetDBObject($objKey) {
		return $this->dbobject_ldr->GetDBObject($objKey);
	}

# PROTECTED & PRIVATE #################################################################################

	// --- logging functions

	private  function debug($msg) {
		if($this->SettingValue(SettingsManager::KEY_KERN_DEBUG)) {
			echo "[KERN_DEBUG]>>>> ".$msg."\n";	
		}
	}
	private function info($msg) {
		if($this->SettingValue(SettingsManager::KEY_KERN_LOG)) {
			echo "[KERN_INFO]>>>> ".$msg."\n";	
		}	
	}
	private function warn($msg) {
		if($this->SettingValue(SettingsManager::KEY_KERN_LOG)) {
			echo "[KERN_WARN]>>>> ".$msg."\n";	
		}	
	}
	private function erro($msg) {
		if($this->SettingValue(SettingsManager::KEY_KERN_LOG)) {
			echo "[KERN_ERRO]>>>> ".$msg."\n";	
		}	
	}
}
