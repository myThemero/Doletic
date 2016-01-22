<?php

/**
* 	@brief
*/
class AbstractLoader {

	// -- attributes
	private $kernel;
	
	// -- functions

	public function __construct(&$kernel) {
		$this->kernel = $kernel;
	}

	protected function kernel() {
		return $this->kernel;
	} 

}