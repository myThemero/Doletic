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
	// -- functions

	public function __construct(&$kernel, &$manager) {
		// -- construct parent
		parent::__construct($kernel, $manager);
	}

	/**
	 *
	 */
	public function Init() {
		parent::manager()->RegisterModules(ModuleLoader::$_MODULES);
	}
	/**
	 *
	 */
	public static function RegisterModule($module) {
		ModuleLoader::$_MODULES[$module->GetCode()] = $module;
	}
}