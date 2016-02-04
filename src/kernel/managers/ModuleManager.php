<?php

require_once "interfaces/AbstractManager.php";

/**
* 	This manager takes care of Doletic modules (plugins)
*/
class ModuleManager extends AbstractManager {

	// -- attributes
	private $modules;

	// -- functions

	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->modules = array();
	}

	public function Init() {
		// nothing to do here
	}

	public function RegisterModules($modules) {
		$this->modules = $modules;
	}
	/**
	 *
	 */
	public function GetModule($code) {
		$module = null;
		if(array_key_exists($code, $this->modules)) {
			$module = $this->modules[$code];	
		}
		return $module; 
	}
	/**
	 *
	 */
	public function GetModulesDBObjects() {
		$dbobjects = array();
		foreach ($this->modules as $module) {
			$dbobjects = array_merge($dbobjects, $module->GetDBObjects());
		}
		return $dbobjects;
	}
	/**
	 *
	 */
	public function GetModulesJSServices() {
		$js_services = array();
		foreach ($this->modules as $module) {
			array_push($js_services, ModuleLoader::MODS_DIR.'/'.$module->GetJSServices());
		}
		return $js_services;
	}
	/**
	 *
	 */
	public function GetModuleUILinks() {
		$ui_links = "[";
		foreach ($this->modules as $module) {
			$ui_links .= $module->GetAvailableUILinks().",";
		}
		$ui_links = substr($ui_links, 0, strlen($ui_links)-1);
		return $ui_links."]";
	}
}