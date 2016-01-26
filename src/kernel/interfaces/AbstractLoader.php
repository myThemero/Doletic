<?php

/**
* 	@brief
*/
abstract class AbstractLoader {

	// -- attributes
	private $kernel;
	private $manager;
	
	// -- functions

	protected function __construct(&$kernel, &$manager) {
		$this->kernel = $kernel;
		$this->manager = $manager;
	}

	protected function kernel() {
		return $this->kernel;
	}

	protected function manager() {
		return $this->manager;
	} 

}