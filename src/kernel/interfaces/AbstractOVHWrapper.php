<?php

require_once "objects/OVHAPIConnector.php";

/**
 *	Cette interface définit des comportements génériques pour les wrappers
 */
abstract class AbstractOVHWrapper {
	// -- consts

	// -- attributes
	private $kernel = null;
	private $name = null;
	private $version = null;
	private $authors = null;
	private $last_error = null;
	private $kernel_set = null;
	private $connector = null;

	// -- functions

	public function GetKernel() {
		return $this->kernel;
	}
	public function SetKernel($kernel = null) {
		if(isset($kernel) && !$this->kernel_set) {
			// set kernel
			$this->kernel = $kernel;
			// initialize connector
			$this->connector = new OVHAPIConnector($this->kernel);
			// raise kernel set flag
			$this->kernel_set = true;
		}
	}
	public function GetName() {
		return $this->name;
	}
	public function GetVersion() {
		return $this->version;
	}
	public function GetAuthors() {
		return $this->authors;
	}
	public function GetLastError() {
		return $this->last_error;
	}
	/**
	 *
	 */
	public function SetLastError($lastError) {
		$this->last_error = $this->name.":".$lastError;
	}

# PROTECTED & PRIVATE #############################################################

	/**
	 *
	 */
	protected function __construct($name, $version, $authors = array()) {
		$this->kernel = null;
		$this->name = $name;
		$this->version = $version;
		$this->authors = $authors;
		$this->last_error = "no-error";
		$this->kernel_set = false;
		$this->connector = null;
	}

	/**
	 *	Retourne l'instance Api du connecteur OVH
	 */
	protected function getAPI() {
		if(isset($connector)) {
			return $connector->GetAPI();	
		}
		return null;
	}

}