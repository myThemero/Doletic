<?php
/**
 *	Cette interface définit les méthodes devant être implémentées pour créer une fonction de wrapper
 */
abstract class AbstractWrapperFunction {

	// -- attributes
	private $wrapper;

	// -- functions

	public function __construct($wrapper) { 
		$this->wrapper = $wrapper;
	}

	/**
	 *	Cette fonction retourne la liste des arguments attendus par la fonction du wrapper
	 */
	abstract public function ExpectedArgs();
	/**
	 *	Cette liste execute la fonction en utilisant le dictionnaire d'arguments fourni.
	 */
	abstract public function Run($args = array());

}

/**
 *	Cette interface définit des comportements génériques pour les wrappers
 */
abstract class AbstractWrapper {
	// -- consts

	// -- attributes
	private $name;
	private $version;
	private $authors;
	private $functions;

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
	public function RunFunction($name, $args) {
		if(array_key_exists($name, $this->functions)) {
			return $this->functions[$name]->Run($args);
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