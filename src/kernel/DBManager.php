<?php

class DBManager {

	// -- attributes
	private $databases;

	// -- function

	public function __construct() {
		$this->databases = array();
	}

	public function Init() {
		/// \todo implement here
	}

	public function RegisterDatabase($name, $db) {
		$ok = false;
		if($this->databases[$name] == null) {	
			$this->databases[$name] = $db;
			$ok = true;
		}
		return $ok;
	}

	public function GetOpenConnectionTo($name) {
		return $this->database[$name];
	}

}