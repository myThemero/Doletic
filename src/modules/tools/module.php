<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/tools/services/objects/MailingListDBObject.php";
require_once "../modules/tools/services/objects/DocumentTemplateDBObject.php";

class ToolsModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "tools",
            "Outils",
            "1.0dev",
            array("Paul Dautry"),
            (RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
            array(
                // -- module interfaces
                'mail' => RightsMap::U_RMASK,
                'admin' => RightsMap::A_RMASK,
                // -- module services
                // ---- maillist object services
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::GET_MAILLIST_BY_ID => RightsMap::A_RMASK,    // only admins and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::GET_ALL_MAILLIST => RightsMap::U_RMASK,    // only members and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::SUBSCRIBED => RightsMap::A_RMASK,    // only admins and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::SUBSCRIBE => RightsMap::G_RMASK,  // only admins and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::UNSUBSCRIBE => RightsMap::G_RMASK,  // only admins and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::INSERT => RightsMap::A_RMASK,  // only admins and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::UPDATE => RightsMap::A_RMASK,  // only admins and above
                MailingListDBObject::OBJ_NAME . ':' . MailingListServices::DELETE => RightsMap::A_RMASK,    // only admins and above
                // ---- doctemplate object services
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::GET_DOCTEMPLATE_BY_ID => RightsMap::U_RMASK,
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::GET_ALL_DOCTEMPLATE => RightsMap::U_RMASK,
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::GET_DOCUMENT_TYPES => RightsMap::G_RMASK,
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::GET_DOCS_BY_TYPE => RightsMap::G_RMASK,
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::INSERT => RightsMap::A_RMASK,
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::UPDATE => RightsMap::A_RMASK,
                DocumentTemplateDBObject::OBJ_NAME . ':' . DocumentTemplateServices::DELETE => RightsMap::A_RMASK
            ),
            false   , // disable ui links
            array('kernel') // kernel is always a dependency
        );
        // -- add module specific dbo objects
        parent::addDBObject(new MailingListDBObject($this));
        parent::addDBObject(new DocumentTemplateDBObject($this));
        // -- add module specific ui
        parent::addUI('Administration', 'admin');    // refer to couple (admin.js, admin.css)
        parent::addUI('Signature Mail', 'mail');    // refer to couple (mail.js, mail.css)
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new ToolsModule());