<?php

require_once "loaders/ModuleLoader.php";

/**
* 	@brief
*/
abstract class AbstractModule {
	
	// -- attributes
	private $code;
	private $name;
	private $version;
	private $authors;
	private $dependencies;
	private $db_objects;
	private $uis;

	// -- functions
	public function GetCode() {
		return $this->code;
	}
	/**
	 *
	 */
	public function GetName() {
		return $this->name;
	}
	/**
	 *
	 */
	public function GetVersion() {
		return $this->version;
	}
	/**
	 *
	 */
	public function GetAuthors() {
		return $this->authors;
	}
	/**
	 *
	 */
	public function GetDependencies() {
		return $this->dependencies;
	}
	/**
	 *
	 */
	public function GetDBObjects() {
		return $this->db_objects;
	}
	/**
	 *
	 */
	public function GetJSServices() {
		return $this->code."/services/services.js";
	}
	/**
	 *
	 */
	public function GetJS($ui) {
		$js = array();
		array_push($js, ModuleLoader::MODS_DIR.'/'.$this->code."/ui/".$ui.".js");
		return $js;
	}
	/**
	 *
	 */
	public function GetCSS($ui) {
		$css = array();
		array_push($css, ModuleLoader::MODS_DIR.'/'.$this->code."/ui/".$ui.".css");
		return $css;
	}
	/**
	 *
	 */
	public function GetAvailableUILinks() {
		$ui_links = "[\"".$this->name."\",[";
		foreach ($this->uis as $ui_name => $ui_code) {
			$ui_links .= "[\"".$ui_name."\",\"".$this->code.":".$ui_code."\"],";
		}
		$ui_links = substr($ui_links, 0, strlen($ui_links)-1);
		return $ui_links."]]";
	}

# PROTECTED & PRIVATE ###################################################

	protected function __construct($code, $name, $version, $authors = array(), $dependencies = array()) {
		$this->code = $code;
		$this->name = $name;
		$this->version = $version;
		$this->authors = $authors;
		$this->dependencies = $dependencies;
		$this->db_objects = array();
		$this->uis = array();
	}

	protected function addDBObject($object) {
		$this->db_objects[$object->GetName()] = $object;
	}

	protected function addUI($uiName, $uiCode) {
		$this->uis[$uiName] = $uiCode;
	}

}