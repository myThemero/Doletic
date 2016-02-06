<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/support/services/objects/TicketDBObject.php";

class SupportModule extends AbstractModule {

	// -- attributes

	// -- functions

	public function __construct() {
		// -- build abstract module
		parent::__construct(
			"support",
			"Support", 
			"1.0dev", 
			array("Paul Dautry","Nicolas Sorin"), 
			(RightsMap::A_G | RightsMap::M_G),
			array(
				/// \todo add rules here for ticket services
				)
			);
		// -- add module specific dbo objects
		parent::addDBObject(new TicketDBObject($this));
		// -- add module specific ui
		parent::addUI('Mes tickets','ui'); 	// refer to couple (ui.js, ui.css)
		parent::addUI('Admin tickets','admin');	// refer to couple (admin.js, admin.css)
	}

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new SupportModule());