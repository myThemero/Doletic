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
			(RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
			array(
				// -- module interfaces
				'ui' => RightsMap::G_RMASK,
				'admin' => RightsMap::SA_RMASK,
				// -- module services
				// ---- ticket object services
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_TICKET_BY_ID 		=> RightsMap::SA_RMASK,	// only super admin 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_TICKET_BY_STATUS 	=> RightsMap::SA_RMASK,	// only super admin 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_STATUS_BY_ID 		=> RightsMap::G_RMASK,  // everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_CATEGO_BY_ID 		=> RightsMap::G_RMASK,  // everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_ALL_TICKETS 		=> RightsMap::G_RMASK,	// everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_USER_TICKETS 		=> RightsMap::G_RMASK,  // everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_ALL_STATUSES 		=> RightsMap::G_RMASK,	// everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::GET_ALL_CATEGOS 		=> RightsMap::G_RMASK,  // everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::INSERT 				=> RightsMap::G_RMASK,  // everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::UPDATE 				=> RightsMap::G_RMASK,	// everyone 
				TicketDBObject::OBJ_NAME.':'.TicketServices::NEXT_STATUS 			=> RightsMap::SA_RMASK,	// only super admin 
				TicketDBObject::OBJ_NAME.':'.TicketServices::DELETE 				=> RightsMap::SA_RMASK, // only super admin
				TicketDBObject::OBJ_NAME.':'.TicketServices::ARCHIVE 				=> RightsMap::SA_RMASK // only super admin
				),
				false // disable ui
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