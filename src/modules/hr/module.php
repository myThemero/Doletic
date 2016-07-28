<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/kernel/services/objects/UserDataDBObject.php";


class HrModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "hr",
            "Ressources humaines",
            "1.0dev",
            array("Paul Dautry", "Nicolas Sorin"),
            (RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
            array(
                // -- module interfaces
                'ui' => RightsMap::G_RMASK,
                'admin' => RightsMap::SA_RMASK,

            ),
            false, // disable ui
            array('kernel') // kernel is always a dependency
        );
        // -- add module specific dbo objects

        // -- add module specific ui
        parent::addUI('Super-Admins', 'superadmin');
        parent::addUI('Admins', 'admin');    // refer to couple (admin.js, admin.css)
        parent::addUI('Membres', 'ui');    // refer to couple (ui.js, ui.css)
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new HrModule());