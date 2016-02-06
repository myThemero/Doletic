<?php

require_once "interfaces/AbstractLoader.php";

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

	private function __connect_db_objects() {
		foreach ($this->objects as $dbo) {
			$dbo->SetDBConnection(
				$this->manager()->getOpenConnectionTo(
				parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
		}
	}

}