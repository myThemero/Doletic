<?php
/**
 *	Cette interface définit les méthodes devant être implémentées pour créer une fonction de wrapper
 */
abstract class AbstractWrapperFunction {

	// -- attributes
	private $wrapper = null;
	private $name = null;
	private $result = null;

	// -- functions

	public function __construct($wrapper, $name) { 
		$this->wrapper = $wrapper;
		$this->name = $name;
		$this->result = null;
	}

	public function GetResult() {
		return $this->result;
	}

	/**
	 *	Cette fonction retourne la liste des arguments attendus par la fonction du wrapper
	 */
	abstract public function ExpectedArgs();
	/**
	 *	Cette liste execute la fonction en utilisant le dictionnaire d'arguments fourni.
	 */
	abstract public function Run($args = array());

# PROTECTED & PRIVATE ###################################################

	protected function updateLastError($lastError) {
		$this->wrapper->SetLastError($this->name.":".$lastError);
	}

	protected function setResult($result) {
		$this->result = $result;
	}

	protected function wrapper() {
		return $this->wrapper;
	}

}

/**
 *	Cette interface définit des comportements génériques pour les wrappers
 */
abstract class AbstractWrapper {
	// -- consts

	// -- attributes
	private $kernel;
	private $name;
	private $version;
	private $authors;
	private $last_error;
	private $functions;

	// -- functions

	public function GetKernel() {
		return $this->kernel;
	}
	public function SetKernel($kernel) {
		$this->kernel = $kernel;
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
	/**
	 *
	 */
	public function ListFunctions() {
		$funcs = array();
		foreach ($this->functions as $name => $func) {
			array_push($funcs, $name);
		}
		return $funcs;
	}
	/**
	 *
	 */
	public function GetFunction($name) {
		if(array_key_exists($name, $this->functions)) {
			return $this->functions[$name];
		}
		return null;
	}

# PROTECTED & PRIVATE #############################################################

	/**
	 *
	 */
	protected function __construct($name, $version, $authors = array()) {
		$this->name = $name;
		$this->version = $version;
		$this->authors = $authors;
		$this->functions = array();
	}

	/**
	 *
	 */
	protected function addFunction($name, $func) {
		$this->functions[$name] = $func;
	}

}