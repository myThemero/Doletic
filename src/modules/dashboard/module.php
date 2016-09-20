<?php

require_once "interfaces/AbstractModule.php";

class DashboardModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "dashboard",
            "Accueil",
            "1.0dev",
            array("Nicolas Sorin"),
            RightsMap::D_G, // means everyone in default group can access this module
            array(
                // -- module interfaces
                'home' => RightsMap::G_RMASK,
                // -- module services

            ),
            false, // disable ui
            array('kernel', 'ua') // kernel is always a dependency
        );
        // -- add module specific dbo objects

        // -- add module specific db services

        // -- add module specific ui
        parent::addUI('Accueil', 'home');    // refer to couple (home.js, home.css)
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new DashboardModule());