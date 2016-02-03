<?php 

require_once "interfaces/AbstractLoader.php";
// load all modules descriptions
foreach (new DirectoryIterator(ModuleLoader::MODS_DIR) as $fileInfo) {
	if($fileInfo->isDot()) continue;
	if($fileInfo->isDir()) {
		require_once "../modules/".$fileInfo->getFilename()."/module.php";
	}
}

/**
* 	@brief
*/
class ModuleLoader extends AbstractLoader {

	// -- consts
	const MODS_DIR = "../modules";
	// -- static
	public static $_MODULES = array();
	// -- attributes
	private $modules;

	// -- functions

	public function __construct(&$kernel, &$manager) {
		// -- construct parent
		parent::__construct($kernel, $manager);
		$this->modules = false;
	}

	/**
	 *
	 */
	public function Init() {
		$this->modules = ModuleLoader::$_MODULES;
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
	public static function RegisterModule($module) {
		ModuleLoader::$_MODULES[$module->GetCode()] = $module;
	}
}