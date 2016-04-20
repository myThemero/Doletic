<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/hr/services/objects/TeamDBObject.php";
require_once "../modules/hr/services/objects/AdmMembershipDBObject.php";
require_once "../modules/hr/services/objects/IntMembershipDBObject.php";

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
				TeamDBObject::OBJ_NAME.':'.TeamServices::GET_ALL_DIVISIONS 		=> RightsMap::G_RMASK,  // everyone 
				TeamDBObject::OBJ_NAME.':'.TeamServices::INSERT_MEMBER			=> RightsMap::A_RMASK,	// admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::DELETE_MEMBER	 		=> RightsMap::A_RMASK,  // admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::INSERT 				=> RightsMap::A_RMASK,  // admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::UPDATE 				=> RightsMap::A_RMASK,	// admin 
				TeamDBObject::OBJ_NAME.':'.TeamServices::DELETE 				=> RightsMap::A_RMASK,  // admin

				// ---- adm_membership object services
				AdmMembershipDBObject::OBJ_NAME.':'.AdmMembershipServices::GET_ADM_MEMBERSHIP_BY_ID => RightsMap::A_RMASK,	// only super admin 
				AdmMembershipDBObject::OBJ_NAME.':'.AdmMembershipServices::GET_ALL_ADM_MEMBERSHIPS 	=> RightsMap::G_RMASK,	// everyone 
				AdmMembershipDBObject::OBJ_NAME.':'.AdmMembershipServices::GET_USER_ADM_MEMBERSHIPS => RightsMap::G_RMASK,  // everyone
				AdmMembershipDBObject::OBJ_NAME.':'.AdmMembershipServices::INSERT 		=> RightsMap::A_RMASK,  // admin 
				AdmMembershipDBObject::OBJ_NAME.':'.AdmMembershipServices::UPDATE 		=> RightsMap::A_RMASK,	// admin 
				AdmMembershipDBObject::OBJ_NAME.':'.AdmMembershipServices::DELETE 		=> RightsMap::A_RMASK,  // admin

				// ---- adm_membership object services
				IntMembershipDBObject::OBJ_NAME.':'.IntMembershipServices::GET_INT_MEMBERSHIP_BY_ID => RightsMap::A_RMASK,	// only super admin 
				IntMembershipDBObject::OBJ_NAME.':'.IntMembershipServices::GET_ALL_INT_MEMBERSHIPS 	=> RightsMap::G_RMASK,	// everyone 
				IntMembershipDBObject::OBJ_NAME.':'.IntMembershipServices::GET_USER_INT_MEMBERSHIPS => RightsMap::G_RMASK,  // everyone
				IntMembershipDBObject::OBJ_NAME.':'.IntMembershipServices::INSERT 				=> RightsMap::A_RMASK,  // admin 
				IntMembershipDBObject::OBJ_NAME.':'.IntMembershipServices::UPDATE 				=> RightsMap::A_RMASK,	// admin 
				IntMembershipDBObject::OBJ_NAME.':'.IntMembershipServices::DELETE 				=> RightsMap::A_RMASK,  // admin

				),
				false, // disable ui
				array('kernel') // kernel is always a dependency
			);
		// -- add module specific dbo objects
		parent::addDBObject(new TeamDBObject($this));
		parent::addDBObject(new AdmMembershipDBObject($this));
		parent::addDBObject(new IntMembershipDBObject($this));

		// -- add module specific ui
		parent::addUI('Administration','admin');	// refer to couple (admin.js, admin.css)
		parent::addUI('Membres','ui'); 	// refer to couple (ui.js, ui.css)
	}

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new HrModule());