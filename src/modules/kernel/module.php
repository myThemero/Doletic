<?php

require_once "interfaces/AbstractModule.php";
require_once "../modules/kernel/services/objects/CommentDBObject.php";
require_once "../modules/kernel/services/objects/ModuleDBObject.php";
require_once "../modules/kernel/services/objects/SettingDBObject.php";
require_once "../modules/kernel/services/objects/UploadDBObject.php";
require_once "../modules/kernel/services/objects/UserDBObject.php";
require_once "../modules/kernel/services/objects/UserDataDBObject.php";
require_once "../modules/kernel/services/objects/OVHWrapperDBObject.php";
require_once "../modules/kernel/services/objects/LogDBObject.php";
require_once "../modules/kernel/services/objects/AdmMembershipDBObject.php";
require_once "../modules/kernel/services/objects/IntMembershipDBObject.php";
require_once "../modules/kernel/services/objects/TeamDBObject.php";
require_once "../modules/kernel/services/objects/IndicatorDBObject.php";
require_once "../modules/kernel/services/KernelDBService.php";

class KernelModule extends AbstractModule
{

    // -- attributes

    // -- functions

    public function __construct()
    {
        // -- build abstract module
        parent::__construct(
            "kernel",
            "Kernel",
            "1.0dev",
            array("Paul Dautry", "Nicolas Sorin"),
            RightsMap::D_G, // means everyone in default group can access this module
            array(
                // -- module interfaces
                'home' => RightsMap::G_RMASK,
                'login' => RightsMap::G_RMASK,
                'logout' => RightsMap::G_RMASK,
                'lost' => RightsMap::G_RMASK,
                'test' => RightsMap::SA_RMASK,
                // -- module services
                // ---- comment object services
                CommentDBObject::OBJ_NAME . ':' . CommentServices::GET_COMMENT_BY_ID => RightsMap::G_RMASK,    // everyone 
                CommentDBObject::OBJ_NAME . ':' . CommentServices::GET_ALL_COMMENTS => RightsMap::SA_RMASK, // only super admin
                CommentDBObject::OBJ_NAME . ':' . CommentServices::INSERT => RightsMap::G_RMASK,  // everyone 
                CommentDBObject::OBJ_NAME . ':' . CommentServices::UPDATE => RightsMap::G_RMASK,    // everyone 
                CommentDBObject::OBJ_NAME . ':' . CommentServices::DELETE => RightsMap::SA_RMASK, // only super admin
                // ---- module object services
                ModuleDBObject::OBJ_NAME . ':' . ModuleServices::GET_MODULE_BY_LABEL => RightsMap::SA_RMASK, // only super admin
                ModuleDBObject::OBJ_NAME . ':' . ModuleServices::GET_MODULE_BY_NAME => RightsMap::SA_RMASK, // only super admin
                ModuleDBObject::OBJ_NAME . ':' . ModuleServices::GET_ALL_MODULES => RightsMap::SA_RMASK, // only super admin
                ModuleDBObject::OBJ_NAME . ':' . ModuleServices::INSERT => RightsMap::SA_RMASK, // only super admin
                ModuleDBObject::OBJ_NAME . ':' . ModuleServices::UPDATE => RightsMap::SA_RMASK, // only super admin
                ModuleDBObject::OBJ_NAME . ':' . ModuleServices::DELETE => RightsMap::SA_RMASK, // only super admin
                // ---- setting object services
                SettingDBObject::OBJ_NAME . ':' . SettingServices::GET_SETTING_BY_ID => RightsMap::A_RMASK,  // only admin and above
                SettingDBObject::OBJ_NAME . ':' . SettingServices::GET_SETTING_BY_KEY => RightsMap::A_RMASK,  // only admin and above
                SettingDBObject::OBJ_NAME . ':' . SettingServices::GET_ALL_SETTINGS => RightsMap::A_RMASK,  // only admin and above
                SettingDBObject::OBJ_NAME . ':' . SettingServices::INSERT => RightsMap::SA_RMASK, // only super admin
                SettingDBObject::OBJ_NAME . ':' . SettingServices::UPDATE => RightsMap::A_RMASK,  // only admin and above
                SettingDBObject::OBJ_NAME . ':' . SettingServices::DELETE => RightsMap::SA_RMASK, // only super admin
                // ---- upload object services
                UploadDBObject::OBJ_NAME . ':' . UploadServices::GET_UPLOAD_BY_ID => RightsMap::G_RMASK,    // everyone
                UploadDBObject::OBJ_NAME . ':' . UploadServices::GET_UPLOAD_BY_STOR_FNAME => RightsMap::G_RMASK,    // everyone
                UploadDBObject::OBJ_NAME . ':' . UploadServices::GET_ALL_UPLOADS => RightsMap::SA_RMASK, // only super admin
                UploadDBObject::OBJ_NAME . ':' . UploadServices::INSERT => RightsMap::G_RMASK,    // everyone
                UploadDBObject::OBJ_NAME . ':' . UploadServices::UPDATE => RightsMap::G_RMASK,    // everyone
                UploadDBObject::OBJ_NAME . ':' . UploadServices::DELETE => RightsMap::SA_RMASK, // only super admin
                // ---- user object services
                UserDBObject::OBJ_NAME . ':' . UserServices::GET_USER_BY_ID => RightsMap::G_RMASK,    // everyone
                UserDBObject::OBJ_NAME . ':' . UserServices::GET_USER_BY_UNAME => RightsMap::G_RMASK,    // everyone
                UserDBObject::OBJ_NAME . ':' . UserServices::GET_ALL_USERS => RightsMap::G_RMASK,    // everyone
                UserDBObject::OBJ_NAME . ':' . UserServices::INSERT => RightsMap::A_RMASK,    // only admin and above
                UserDBObject::OBJ_NAME . ':' . UserServices::UPDATE => RightsMap::A_RMASK,    // only admin and above
                UserDBObject::OBJ_NAME . ':' . UserServices::DELETE => RightsMap::SA_RMASK,    // only super admin
                UserDBObject::OBJ_NAME . ':' . UserServices::DISABLE => RightsMap::A_RMASK,    // only admin and above
                UserDBObject::OBJ_NAME . ':' . UserServices::RESTORE => RightsMap::A_RMASK,    // only admin and above
                // ---- user data object services
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_USER_DATA_BY_ID => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_USER_LAST_POS => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_USER_RG_CODE => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_USER_DATA => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_BY_DIV => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_BY_POS => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_BY_DPT => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_GENDERS => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_COUNTRIES => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_INSA_DEPTS => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_DIVISIONS => RightsMap::G_RMASK,  // everyone 
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_POSITIONS => RightsMap::G_RMASK,    // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::INSERT => RightsMap::A_RMASK,    // only admin and above
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::UPDATE => RightsMap::A_RMASK,    // only admin and above
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::UPDATE_POSTION => RightsMap::A_RMASK,    // only admin and above
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::DELETE => RightsMap::SA_RMASK,    // only super admin
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::DISABLE => RightsMap::A_RMASK,    // only admin and above
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::ENABLE => RightsMap::A_RMASK,    // only admin and above
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::GET_ALL_AGS => RightsMap::G_RMASK,  // everyone
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::INSERT_AG => RightsMap::A_RMASK,  // admin  
                UserDataDBObject::OBJ_NAME . ':' . UserDataServices::DELETE_AG => RightsMap::A_RMASK,  // admin

                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_BY_ID => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_VALUE => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_GRAPH => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_TABLE => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_BY_MODULE => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_VALUE_BY_MODULE => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_GRAPH_BY_MODULE => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::GET_ALL_TABLE_BY_MODULE => RightsMap::A_RMASK,  // only admin and above
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_BY_ID => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_VALUE => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_GRAPH => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_TABLE => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_BY_MODULE => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_VALUE_BY_MODULE => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_GRAPH_BY_MODULE => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::PROCESS_ALL_TABLE_BY_MODULE => RightsMap::G_RMASK,  // everyone
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::INSERT_VALUE => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::INSERT_GRAPH => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::INSERT_TABLE => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::UPDATE_VALUE => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::UPDATE_GRAPH => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::UPDATE_TABLE => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::DELETE => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::DISABLE => RightsMap::SA_RMASK,  // only super-admin
                IndicatorDBObject::OBJ_NAME . ':' . IndicatorServices::ENABLE => RightsMap::SA_RMASK,  // only super-admin

                KernelDBService::SERV_NAME . ':' . KernelDBService::GET_ALL_UDATA_WITH_STATUS => RightsMap::G_RMASK,  // everyone

                // ---- adm_membership object services
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::GET_ADM_MEMBERSHIP_BY_ID => RightsMap::A_RMASK,    // only super admin 
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::GET_ALL_ADM_MEMBERSHIPS => RightsMap::G_RMASK,    // everyone 
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::GET_USER_ADM_MEMBERSHIPS => RightsMap::G_RMASK,  // everyone
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::GET_CURRENT_ADM_MEMBERSHIP => RightsMap::G_RMASK,  // everyone
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::INSERT => RightsMap::A_RMASK,  // admin 
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::UPDATE => RightsMap::A_RMASK,    // admin 
                AdmMembershipDBObject::OBJ_NAME . ':' . AdmMembershipServices::DELETE => RightsMap::A_RMASK,  // admin

                // ---- adm_membership object services
                IntMembershipDBObject::OBJ_NAME . ':' . IntMembershipServices::GET_INT_MEMBERSHIP_BY_ID => RightsMap::A_RMASK,    // only super admin 
                IntMembershipDBObject::OBJ_NAME . ':' . IntMembershipServices::GET_ALL_INT_MEMBERSHIPS => RightsMap::G_RMASK,    // everyone 
                IntMembershipDBObject::OBJ_NAME . ':' . IntMembershipServices::GET_USER_INT_MEMBERSHIPS => RightsMap::G_RMASK,  // everyone
                IntMembershipDBObject::OBJ_NAME . ':' . IntMembershipServices::INSERT => RightsMap::A_RMASK,  // admin 
                IntMembershipDBObject::OBJ_NAME . ':' . IntMembershipServices::UPDATE => RightsMap::A_RMASK,    // admin 
                IntMembershipDBObject::OBJ_NAME . ':' . IntMembershipServices::DELETE => RightsMap::A_RMASK,  // admin

                // -- module services
                // ---- team object services
                TeamDBObject::OBJ_NAME . ':' . TeamServices::GET_TEAM_BY_ID => RightsMap::G_RMASK,    // only super admin 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::GET_TEAM_BY_DIV => RightsMap::G_RMASK,    // only super admin 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::GET_TEAM_MEMBERS => RightsMap::G_RMASK,  // everyone 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::GET_ALL_TEAMS => RightsMap::G_RMASK,    // everyone 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::GET_USER_TEAMS => RightsMap::G_RMASK,  // everyone
                TeamDBObject::OBJ_NAME . ':' . TeamServices::INSERT_MEMBER => RightsMap::A_RMASK,    // admin 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::DELETE_MEMBER => RightsMap::A_RMASK,  // admin 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::INSERT => RightsMap::A_RMASK,  // admin 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::UPDATE => RightsMap::A_RMASK,    // admin 
                TeamDBObject::OBJ_NAME . ':' . TeamServices::DELETE => RightsMap::A_RMASK  // admin

            ),
            false // disable ui must be true in production version
        );
        // -- add module specific dbo objects
        parent::addDBObject(new SettingDBObject($this));
        parent::addDBObject(new UploadDBObject($this));
        parent::addDBObject(new ModuleDBObject($this));
        parent::addDBObject(new UserDBObject($this));
        parent::addDBObject(new UserDataDBObject($this));
        parent::addDBObject(new CommentDBObject($this));
        parent::addDBObject(new OVHWrapperDBObject($this));
        parent::addDBObject(new LogDBObject($this));
        parent::addDBObject(new AdmMembershipDBObject($this));
        parent::addDBObject(new IntMembershipDBObject($this));
        parent::addDBObject(new TeamDBObject($this));
        parent::addDBObject(new IndicatorDBObject($this));

        // -- add module specific db services
        parent::addDBService(new KernelDBService($this));

        // -- add module specific ui
        parent::addUI('Home', 'home');    // refer to couple (home.js, home.css)
        parent::addUI('Login', 'login');    // refer to couple (login.js, login.css)
        parent::addUI('Logout', 'logout');    // refer to couple (logout.js, logout.css)
        parent::addUI('Lost', 'lost');    // refer to couple (lost.js, lost.css)
        parent::addUI('Restored', 'restored');    // refer to couple (restored.js, restored.css)
        parent::addUI('Test', 'test');    // refer to couple (test.js, test.css)
    }

}

// ----------- ADD MODULE TO GLOBAL MODULES VAR -------------

ModuleLoader::RegisterModule(new KernelModule());