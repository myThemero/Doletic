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
		/// \todo implement here
	}

	public function RegisterModule($module) {
		array_push($this->modules, $module);
	}
}