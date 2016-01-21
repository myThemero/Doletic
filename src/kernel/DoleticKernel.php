<?php

require_once "LogManager.php";
require_once "CronManager.php";
require_once "DBManager.php";
require_once "ModuleManager.php";
require_once "SettingsManager.php";
require_once "AuthenticationManager.php";
require_once "ModuleLoader.php";


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
	// --- flags
	private $initialized;

	// -- functions

	public function __construct() {
		// create instances
		$this->log_mgr = new LogManager();
		$this->cron_mgr = new CronManager();
		$this->db_mgr = new DBManager();
	 	$this->module_mgr = new ModuleManager();
	 	$this->settings_mgr = new SettingsManager();
	 	$this->authentication_mgr = new AuthenticationManager();
	 	$this->module_ldr = new ModuleLoader();
	 	$this->initialized = false;
	}

	public function Init() {
		if(!$this->initialized) {
			$this->log_mgr->Init();
			$this->cron_mgr->Init();
			$this->db_mgr->Init();
		 	$this->module_mgr->Init();
		 	$this->settings_mgr->Init();
		 	$this->authentication_mgr->Init();
		 	$this->module_ldr->Init();
		 	$this->initialized = true;	
		} else {

		}
	}

	// --- logging functions 

	public function Log($logMessage) {
		$log_mgr->Log("kernel",$logMessage);
	}
}

# TEST ###########################################################

$kernel = new DoleticKernel();
$kernel->Init();






# TEST ###########################################################