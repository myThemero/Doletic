<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/kernel/services/objects/UserDataDBObject.php";
require_once "../modules/kernel/services/objects/UploadDBObject.php";
require_once "../modules/grc/services/objects/FirmDBObject.php";
require_once "../modules/grc/services/objects/ContactDBObject.php";
require_once "../modules/ua/services/objects/ProjectDBObject.php";
require_once "../modules/ua/services/objects/TaskDBObject.php";
require_once "../modules/ua/services/objects/DocumentDBObject.php";
require_once "../modules/ua/services/UaDBService.php";


class UaModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "ua",
            "Unité d'Affaires",
            "1.0dev",
            array("Nicolas Sorin"),
            (RightsMap::A_G | RightsMap::M_G), // means only admin and members group can access this module
            array(
                // -- module interfaces
                'guest' => RightsMap::G_RMASK,
                'user' => RightsMap::U_RMASK,
                'admin' => RightsMap::A_RMASK,
                'superadmin' => RightsMap::SA_RMASK,
                // -- module services

                // ---- project object services
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_NUMBER => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_FULL_BY_NUMBER => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_ORIGIN => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_FIELD => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_STATUS => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_CHADAFF => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_AUDITOR => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_CONTACT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_INT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_BY_FIRM => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_CRITICAL => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_SECRET => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_STATUS => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_ORIGIN => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_AMEND_TYPE => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_AMENDMENT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_AMENDMENT_BY_ID => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_AMENDMENT_BY_PROJECT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_CHADAFF_BY_PROJECT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_CONTACT_BY_PROJECT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::GET_ALL_INT_BY_PROJECT => RightsMap::U_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::INSERT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::INSERT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UPDATE => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UPDATE_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::SIGN => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UNSIGN => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::END => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::BAD_END => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UNEND => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ASSIGN_CHADAFF => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ASSIGN_INT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ASSIGN_INT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ASSIGN_CONTACT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ASSIGN_CONTACT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ASSIGN_AUDITOR => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::REMOVE_CHADAFF => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::REMOVE_INT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::REMOVE_INT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::REMOVE_CONTACT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::REMOVE_CONTACT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::DISABLE => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ENABLE => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::ARCHIVE => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UNARCHIVE => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::DELETE => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::INSERT_AMENDMENT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::INSERT_AMENDMENT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UPDATE_AMENDMENT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::UPDATE_AMENDMENT_OWN => RightsMap::A_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::DELETE_AMENDMENT => RightsMap::SA_RMASK,
                ProjectDBObject::OBJ_NAME . ':' . ProjectServices::HAS_RIGHTS => RightsMap::A_RMASK,

                // ---- task object services
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_ALL => RightsMap::U_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_BY_ID => RightsMap::U_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_BY_PROJECT => RightsMap::U_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_BY_PROJECT_AND_NUMBER => RightsMap::U_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_ALL_WITH_DELIVERY => RightsMap::A_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_BY_PROJECT_WITH_DELIVERY => RightsMap::A_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_ALL_DELIVERY => RightsMap::A_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_DELIVERY_BY_ID => RightsMap::A_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_DELIVERY_BY_TASK => RightsMap::A_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::GET_DELIVERY_BY_PROJECT => RightsMap::A_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::END_TASK => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::UNEND_TASK => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::PAY_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::UNPAY_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::DELIVER_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::UNDELIVER_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::INSERT => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::INSERT_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::UPDATE => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::UPDATE_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::DELETE => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::DELETE_DELIVERY => RightsMap::SA_RMASK,
                TaskDBObject::OBJ_NAME . ':' . TaskServices::SWITCH_TASKS_NUMBER => RightsMap::SA_RMASK,

                // ---- ua service services
                UaDBService::SERV_NAME . ':' . UaDBService::GET_FULL_PROJECT_BY_NUMBER => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::INSERT_TASK_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::INSERT_DELIVERY_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::UPDATE_TASK_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::UPDATE_DELIVERY_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::DELETE_TASK_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::DELETE_DELIVERY_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::END_TASK_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::UNEND_TASK_OWN => RightsMap::A_RMASK,
                UaDBService::SERV_NAME . ':' . UaDBService::SWITCH_TASK_NUMBER_OWN => RightsMap::A_RMASK

            ),
            false, // disable ui
            array('kernel', 'grc') // kernel is always a dependency. GRC required for contact/firm management
        );
        // -- add module specific db objects
        parent::addDBObject(new ProjectDBObject($this));
        parent::addDBObject(new TaskDBObject($this));
        parent::addDBObject(new DocumentDBObject($this));

        // -- add module specific db services
        parent::addDBService(new UaDBService($this));

        // -- add module specific ui
        parent::addUI('Super-Admins', 'superadmin');
        parent::addUI('Admins', 'admin', false);
        parent::addUI('Membres', 'user', false);
        parent::addUI('Invités', 'guest', false);
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new UaModule());