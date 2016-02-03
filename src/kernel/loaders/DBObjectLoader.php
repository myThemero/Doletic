<?php

require_once "interfaces/AbstractLoader.php";
require_once "services/php/SettingDBObject.php";
require_once "services/php/ModuleDBObject.php";
require_once "services/php/UserDBObject.php";
require_once "services/php/UserDataDBObject.php";
require_once "services/php/UploadDBObject.php";
require_once "services/php/CommentDBObject.php";


/**
* 	@brief
*/
class DBObjectLoader extends AbstractLoader {

	// -- consts

	// -- attributes
	private $objects;

	// -- functions

	public function __construct(&$kernel, &$manager) {
		parent::__construct($kernel, $manager);
		$this->objects = array();
	}

	public function Init($modulesDBObjects) {
		// -- add kernel db objects
		$this->__add_kernel_db_objects();
		// -- add modules db objects
		foreach ($modulesDBObjects as $dboname => $dbo) {
			$this->objects[$dboname] = $dbo;
		}
		// -- init db objects connections
		$this->__connect_db_objects();
	}

	public function GetDBObject($key) {
		$object = null;
		if(array_key_exists($key, $this->objects)) {
			$object = $this->objects[$key];	
		}
		return $object; 
	}

	public function FullDBClear() {
		foreach ($this->objects as $key => $object) {
			$object->ClearDB();
		}
	}

	public function FullDBReset() {
		foreach ($this->objects as $key => $object) {
			$object->ResetDB();
		}
	}

	public function FullDBUpdate() {
		foreach ($this->objects as $key => $object) {
			$object->UpdateDB();
		}
	}

# PROTECTED & PRIVATE ######################################################

	private function __add_kernel_db_objects() {
		$this->objects[SettingDBObject::OBJ_NAME] = new SettingDBObject();
		$this->objects[ModuleDBObject::OBJ_NAME] = new ModuleDBObject();
		$this->objects[UserDBObject::OBJ_NAME] = new UserDBObject();
		$this->objects[UserDataDBObject::OBJ_NAME] = new UserDataDBObject();
		$this->objects[UploadDBObject::OBJ_NAME] = new UploadDBObject();
		$this->objects[CommentDBObject::OBJ_NAME] = new CommentDBObject();
	}

	private function __connect_db_objects() {
		foreach ($this->objects as $dbo) {
			$dbo->SetDBConnection(
				$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		}
	}

}