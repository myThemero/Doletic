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
		if(!isset($this->connector)) {
			// retrieve wrapper config
			$object = $this->kernel->GetDBObject(OVHWrapperDBObject::OBJ_NAME);
			if(isset($object)) {
				// retrieve wrapper config
				$wrapperConfig = $object->GetServices($this->kernel->GetCurrentUser())
									->GetResponseData(OVHWrapperServices::GET_BY_NAME, 
									array(OVHWrapperServices::PARAM_NAME => $this->name));
				// test if object has effectively been retrieved
				if(isset($wrapperConfig)) {
					// initialize connector
					$this->connector = new OVHAPIConnector($wrapperConfig);
					// test api connector
					$api = $this->connector->GetAPI();
					if(!isset($api)) {
						$this->kernel->LogError(get_class(), "OVH API Connector configuration failed !");
					} else {
						$this->kernel->LogInfo(get_class(), "OVH API Connector successfully configured !");
					}
				} else {
					// critical error -> terminate explicitly or a call on a null object will occur
					$this->kernel->LogFatal(get_class(), "Can't configure connector, configuration is missing for '".$this->name."' wrapper.");
				}
			}
		}
		return $this->connector->GetAPI();	
	}

}