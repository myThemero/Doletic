<?php

class ModuleManager {

	// -- attributes
	private $modules;

	// -- functions

	public function __construct() {
		$this->modules = array();
	}

	public function Init() {
		/// \todo implement here
	}

	public function RegisterModule($module) {
		array_push($this->modules, $module);
	}
}