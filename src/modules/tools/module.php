<?php

require_once "interfaces/AbstractModule.php";

class ToolsModule extends AbstractModule {

	// -- attributes

	// -- functions

	public function __construct() {
		// -- build abstract module
		parent::__construct(
			"tools",
			"Outils", 
			"1.0dev", 
			array("Paul Dautry"), 
			(RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
			array(
				// -- module interfaces
				'mail' => RightsMap::U_RMASK
				// -- module services
				// no services
				),
				false, // disable ui links
				array('kernel') // kernel is always a dependency
			);
		// -- add module specific dbo objects
		// no object to add
		// -- add module specific ui
		parent::addUI('Signature Mail','mail'); 	// refer to couple (mail.js, mail.css)
	}

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new ToolsModule());