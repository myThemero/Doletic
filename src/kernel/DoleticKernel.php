<?php

require_once "managers/LogManager.php";
require_once "managers/CronManager.php";
require_once "managers/DBManager.php";
require_once "managers/ModuleManager.php";
require_once "managers/SettingsManager.php";
require_once "managers/AuthenticationManager.php";
require_once "loaders/ModuleLoader.php";
require_once "loaders/DBObjectLoader.php";

/**
* 	@brief
*/
class DoleticKernel {

	// -- attributes
	// --- managers
	private $log_mgr;
	private $cron_mgr;
	private $db_mgr;
	private $module_mgr;
	private $settings_mgr;
	private $authentication_mgr;
	// --- loaders
	private $module_ldr;
	private $dbobject_ldr;
	// --- flags
	private $initialized;

	// -- functions

	public function __construct() {
		// create managers
		$this->log_mgr = new LogManager($this);
		$this->cron_mgr = new CronManager($this);
		$this->db_mgr = new DBManager($this);
	 	$this->module_mgr = new ModuleManager($this);
	 	$this->settings_mgr = new SettingsManager($this);
	 	$this->authentication_mgr = new AuthenticationManager($this);
	 	// create loggers
	 	$this->module_ldr = new ModuleLoader($this);
	 	$this->dbobject_ldr = new DBObjectLoader($this);
	 	// unset initialized flag
	 	$this->initialized = false;
	}

	public function Init() {
		if(!$this->initialized) {
			// init managers
			$this->log_mgr->Init();
			$this->settings_mgr->Init();
			$this->db_mgr->Init();
			$this->cron_mgr->Init();
		 	$this->module_mgr->Init();
		 	$this->authentication_mgr->Init();
		 	// init loaders
		 	$this->module_ldr->Init();
		 	$this->dbobject_ldr->Init();
		 	// set initialized flag
		 	$this->initialized = true;	
		} else {

		}
	}

	// --- logging functions 

	public function Log($logger,$logMessage) {
		$this->log_mgr->Log($logger,$logMessage);
	}

	// --- settings functions

	public function SettingValue($key) {
		return $this->settings_mgr->GetSettingValue($key);
	}

	// --- setup

	public function SetupDatabase() {
		// build or rebuild database
		$this->dbobject_ldr->FullDBReset($this->db_mgr);
	}
}

# TEST ###########################################################

$kernel = new DoleticKernel();
$kernel->Init();
$kernel->SetupDatabase();





# TEST ###########################################################