<?php 

require_once "interfaces/AbstractLoader.php";
require_once "../modules/support/SupportModule.php";

/**
* 	@brief
*/
class ModuleLoader extends AbstractLoader {

	// -- consts
	const MOD_SUPPORT = "support";

	// -- attributes
	private $modules;

	// -- functions

	public function __construct(&$kernel, &$manager) {
		// -- construct parent
		parent::__construct($kernel, $manager);
		// -- init attributes
		$this->modules = array();
	}

	public function Init() {
		// add all installed modules
		$this->modules[ModuleLoader::MOD_SUPPORT] = new SupportModule();
	}

	public function GetModule($key) {
		$module = "undefined";
		if(array_key_exists($key, $this->modules)) {
			$module = $this->modules[$key];	
		}
		return $module; 
	}

}