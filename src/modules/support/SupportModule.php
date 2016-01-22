<?php

require_once "interfaces/AbstractModule.php";

class SupportModule extends AbstractModule {

	// -- attributes

	// -- functions

	public function __construct() {
		parent::__construct(
			"Support", 
			"1.0dev", 
			array("Paul Dautry","Nicolas Sorin"), 
			array(), // no dependency
			array("main" => "js/support_main.js")  //  
			);
	}

}