<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/grc/services/objects/ContactDBObject.php";
require_once "../modules/grc/services/objects/FirmDBObject.php";

class GRCModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "grc",
            "Gestion Relations Clients",
            "1.0dev",
            array("Paul Dautry", "Nicolas Sorin"),
            (RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
            array(
                // -- module interfaces
                'ui' => RightsMap::G_RMASK,
                'admin' => RightsMap::SA_RMASK,
                // -- module services
                // ---- contact object services
                ContactDBObject::OBJ_NAME . ':' . ContactServices::GET_CONTACT_BY_ID => RightsMap::U_RMASK,    // only super admin
                ContactDBObject::OBJ_NAME . ':' . ContactServices::GET_ALL_CONTACTS => RightsMap::U_RMASK,    // everyone
                ContactDBObject::OBJ_NAME . ':' . ContactServices::INSERT => RightsMap::U_RMASK,  // everyone
                ContactDBObject::OBJ_NAME . ':' . ContactServices::UPDATE => RightsMap::U_RMASK,    // everyone
                ContactDBObject::OBJ_NAME . ':' . ContactServices::DELETE => RightsMap::SA_RMASK, // only super admin
            ),
            false, // disable ui
            array('kernel') // kernel is always a dependency
        );
        // -- add module specific dbo objects
        parent::addDBObject(new ContactDBObject($this));
        parent::addDBObject(new FirmDBObject($this));
        // -- add module specific ui
        parent::addUI('Administration', 'admin');    // refer to couple (admin.js, admin.css)
        parent::addUI('Membres', 'ui');    // refer to couple (ui.js, ui.css)
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new GRCModule());
