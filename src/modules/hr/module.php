<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/kernel/services/objects/UserDataDBObject.php";
require_once "../modules/hr/services/objects/TeamDBObject.php";

class HrModule extends AbstractModule {

	// -- attributes

	// -- functions

	public function __construct() {
		// -- build abstract module
		parent::__construct(
			"hr",
			"Ressources humaines", 
			"1.0dev", 
			array("Paul Dautry","Nicolas Sorin"), 
			(RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
			array(
				// -- module interfaces
				'ui' => RightsMap::G_RMASK,
				'admin' => RightsMap::SA_RMASK,
				// -- module services
				// ---- team object services
				TeamDBObject::OBJ_NAME.':'.TeamServices::GET_TEAM_BY_ID 		=> RightsMap::G_RMASK,	// only super admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::GET_TEAM_BY_DIV 		=> RightsMap::G_RMASK,	// only super admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::GET_TEAM_MEMBERS 		=> RightsMap::G_RMASK,  // everyone 
				TeamDBObject::OBJ_NAME.':'.TeamServices::GET_ALL_TEAMS 			=> RightsMap::G_RMASK,	// everyone 
				TeamDBObject::OBJ_NAME.':'.TeamServices::GET_USER_TEAMS 		=> RightsMap::G_RMASK,  // everyone
				TeamDBObject::OBJ_NAME.':'.TeamServices::INSERT_MEMBER			=> RightsMap::A_RMASK,	// admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::DELETE_MEMBER	 		=> RightsMap::A_RMASK,  // admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::INSERT 				=> RightsMap::A_RMASK,  // admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::UPDATE 				=> RightsMap::A_RMASK,	// admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::DELETE 				=> RightsMap::A_RMASK  // admin

				),
				false, // disable ui
				array('kernel') // kernel is always a dependency
			);
		// -- add module specific dbo objects
		parent::addDBObject(new TeamDBObject($this));

		// -- add module specific ui
		parent::addUI('Super-Admins', 'superadmin');
		parent::addUI('Admins','admin');	// refer to couple (admin.js, admin.css)
		parent::addUI('Membres','ui'); 	// refer to couple (ui.js, ui.css)
	}

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new HrModule());