<?php

require_once "interfaces/AbstractModule.php";
//require_once "../modules/support/services/objects/TicketDBObject.php";

class TodoModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "todo",
            "Planning",
            "1.0dev",
            array("Paul Dautry", "Nicolas Sorin"),
            (RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
            array(
                // -- module interfaces
                //'ui' => RightsMap::U_RMASK,
                'ui' => RightsMap::SA_RMASK//,
                // -- module services
                // ---- ticket object services
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_TICKET_BY_ID => RightsMap::SA_RMASK,    // only super admin
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_TICKET_BY_STATUS => RightsMap::SA_RMASK,    // only super admin
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_STATUS_BY_ID => RightsMap::G_RMASK,  // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_CATEGO_BY_ID => RightsMap::G_RMASK,  // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_ALL_TICKETS => RightsMap::G_RMASK,    // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_USER_TICKETS => RightsMap::G_RMASK,  // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_ALL_STATUSES => RightsMap::G_RMASK,    // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::GET_ALL_CATEGOS => RightsMap::G_RMASK,  // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::INSERT => RightsMap::G_RMASK,  // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::UPDATE => RightsMap::G_RMASK,    // everyone
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::NEXT_STATUS => RightsMap::SA_RMASK,    // only super admin
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::DELETE => RightsMap::SA_RMASK, // only super admin
        //        TicketDBObject::OBJ_NAME . ':' . TicketServices::ARCHIVE => RightsMap::SA_RMASK // only super admin
            ),
            false, // disable ui
            array('kernel') // kernel is always a dependency
        );
        // -- add module specific dbo objects
    //    parent::addDBObject(new TicketDBObject($this));
        // -- add module specific ui
    //    parent::addUI('Administration', 'admin');    // refer to couple (admin.js, admin.css)
        parent::addUI('Mon ui', 'ui');    // refer to couple (ui.js, ui.css)
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new TodoModule());