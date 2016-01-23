<?php

require_once "interfaces/AbstractLoader.php";
require_once "../services/objects/UserDBObject.php";
require_once "../services/objects/TicketDBObject.php";
require_once "../services/objects/UserDataDBObject.php";

/**
* 	@brief
*/
class DBObjectLoader extends AbstractLoader {

	// -- consts
	const OBJ_USER 		= UserDBObject::OBJ_NAME;
	const OBJ_TICKET 	= TicketDBObject::OBJ_NAME;
	const OBJ_UDATA		= UserDataDBObject::OBJ_NAME;

	// -- attributes
	private $objects;

	// -- functions

	public function __construct(&$kernel, &$manager) {
		parent::__construct($kernel, $manager);
		$this->objects = array();
	}

	public function Init() {
		// -- create user object
		$this->objects[UserDBObject::OBJ_NAME] = new UserDBObject(
			$this->manager()->getOpenConnectionTo(
				$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
	// -- create ticket object
		$this->objects[UserDataDBObject::OBJ_NAME] = new UserDataDBObject(
			$this->manager()->getOpenConnectionTo(
				$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		// -- create ticket object
		$this->objects[TicketDBObject::OBJ_NAME] = new TicketDBObject(
			$this->manager()->getOpenConnectionTo(
				$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
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