<?php

require_once "loaders/AbstractLoader.php";
require_once "../services/objects/UserDBObject.php";
require_once "../services/objects/TicketDBObject.php";

/**
* 	@brief
*/
class DBObjectLoader extends AbstractLoader {

	// -- consts
	const OBJ_USER = "user";
	const OBJ_TICKET = "ticket";

	// -- attributes
	private $objects;

	// -- functions

	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->objects = array();
	}

	public function Init() {
		$this->objects[DBObjectLoader::OBJ_USER] = new UserDBObject();
		$this->objects[DBObjectLoader::OBJ_TICKET] = new TicketDBObject();
	}

	public function GetObject($key) {
		$object = "undefined";
		if(array_key_exists($id, $this->objects)) {
			$object = $this->objects[$key];	
		}
		return $object; 
	}

	public function FullDBReset(&$dbmanager) {
		foreach ($this->objects as $key => $object) {
			$object->ResetDB(
				$dbmanager, 
				$this->kernel()->SettingValue(SettingsManager::KEY_DBNAME));
		}
	}
}