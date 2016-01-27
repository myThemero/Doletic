<?php

require_once "interfaces/AbstractLoader.php";
require_once "../services/objects/SettingDBObject.php";
require_once "../services/objects/ModuleDBObject.php";
require_once "../services/objects/UserDBObject.php";
require_once "../services/objects/UserDataDBObject.php";
require_once "../services/objects/UploadDBObject.php";
require_once "../services/objects/CommentDBObject.php";
require_once "../services/objects/TicketDBObject.php";


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

	public function Init() {
		// -- create setting object
		$this->objects[SettingDBObject::OBJ_NAME] = new SettingDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create module object
		$this->objects[ModuleDBObject::OBJ_NAME] = new ModuleDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create user object
		$this->objects[UserDBObject::OBJ_NAME] = new UserDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create userdata object
		$this->objects[UserDataDBObject::OBJ_NAME] = new UserDataDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create upload object
		$this->objects[UploadDBObject::OBJ_NAME] = new UploadDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create comment object
		$this->objects[CommentDBObject::OBJ_NAME] = new CommentDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create ticket object
		$this->objects[TicketDBObject::OBJ_NAME] = new TicketDBObject(
			$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
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
}