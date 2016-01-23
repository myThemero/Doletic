<?php

/**
* 	@brief
*/
class AbstractModule {
	
	// -- attributes
	private $name;
	private $version;
	private $authors;
	private $dependencies;
	private $interfaces;

	// -- functions

	public function GetName() {
		return $this->name;
	}
	public function GetVersion() {
		return $this->version;
	}
	public function GetAuthors() {
		return $this->authors;
	}
	public function GetDependencies() {
		return $this->dependencies;
	}
	public function GetInterface($id) {
		$interface = "no_interface";
		if(array_key_exists($id, $this->interfaces)) {
			$interface = $this->interfaces[$key];
		}
		return $interface;
	}

# PROTECTED & PRIVATE ###################################################

	protected function __construct($name, $version, $authors = array(), $dependencies = array(), $interfaces = array()) {
		$this->name = $name;
		$this->version = $version;
		$this->authors = $authors;
		$this->dependencies = $dependencies;
		$this->interfaces = $interfaces;
	}

}