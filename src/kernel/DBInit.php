<?php

require_once "interfaces/AbstractScript.php";
require_once "DoleticKernel.php";

//________________________________________________________________________________________________________________________
// ------------- declare script functions --------------------------------------------------------------------------------

class ResetDBFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'Reset DB',
            '-rdb',
            '--reset-database',
            "Reset database dropping tables and creating them again.");
    }

    public function Execute()
    {
        parent::info("-- dbinit process starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        parent::info("Reseting database...", true);
        $kernel->ResetDatabase();        // full database reset
        parent::endlog("done !");
        $kernel = null;
        parent::info("-- dbinit process ends --");
    }
}

class FakeDataFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'Fake Data',
            '-fd',
            '--fake-data',
            "Adds fake data to the data base.");
    }

    public function Execute()
    {
        parent::info("-- fake data process starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --- fill tickets
        parent::info("Filling ticket object related tables...", true);
        // --------------------------------------------------------------
        $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TicketServices::INSERT, array(
                TicketServices::PARAM_RECEIVER => 1,
                TicketServices::PARAM_SUBJECT => "Test subject",
                TicketServices::PARAM_CATEGO => 2,
                TicketServices::PARAM_DATA => "Test data",
                TicketServices::PARAM_STATUS => 3));
        // --------------------------------------------------------------
        parent::endlog("done !");
        // --- fill users
        parent::info("Filling user object related tables...", true);
        // --------------------------------------------------------------
        // SA account
        $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::INSERT, array(
                UserServices::PARAM_UNAME => "super-admin.doe",
                UserServices::PARAM_HASH => sha1("password")));
        // A account
        $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::INSERT, array(
                UserServices::PARAM_UNAME => "admin.doe",
                UserServices::PARAM_HASH => sha1("password")));
        // U account
        $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::INSERT, array(
                UserServices::PARAM_UNAME => "user.doe",
                UserServices::PARAM_HASH => sha1("password")));
        // G account
        $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::INSERT, array(
                UserServices::PARAM_UNAME => "guest.doe",
                UserServices::PARAM_HASH => sha1("password")));
        // --------------------------------------------------------------
        parent::endlog("done !");
        // --- fill userdata
        parent::info("Filling userdata object related tables...", true);
        // --------------------------------------------------------------
        // Add AG
        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT_AG, array(
                UserDataServices::PARAM_AG => "2016-02-20",
                UserDataServices::PARAM_PRESENCE => 7
            ));

        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT_AG, array(
                UserDataServices::PARAM_AG => "2015-02-17",
                UserDataServices::PARAM_PRESENCE => 4
            ));

        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT_AG, array(
                UserDataServices::PARAM_AG => "2015-10-04",
                UserDataServices::PARAM_PRESENCE => 5
            ));

        // SA account
        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT, array(
                UserDataServices::PARAM_USER_ID => 1,
                UserDataServices::PARAM_GENDER => "M.",
                UserDataServices::PARAM_FIRSTNAME => "SuperAdmin",
                UserDataServices::PARAM_LASTNAME => "Doe",
                UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
                UserDataServices::PARAM_TEL => "0600000000",
                UserDataServices::PARAM_EMAIL => "super-admin.doe@gmail.com",
                UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
                UserDataServices::PARAM_CITY => "Etic",
                UserDataServices::PARAM_POSTAL_CODE => 99999,
                UserDataServices::PARAM_COUNTRY => "France",
                UserDataServices::PARAM_SCHOOL_YEAR => 4,
                UserDataServices::PARAM_INSA_DEPT => "IF",
                UserDataServices::PARAM_AG => "2015-02-17",
                UserDataServices::PARAM_POSITION => "Responsable DSI"
            ));
        // A account
        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT, array(
                UserDataServices::PARAM_USER_ID => 2,
                UserDataServices::PARAM_GENDER => "M.",
                UserDataServices::PARAM_FIRSTNAME => "Admin",
                UserDataServices::PARAM_LASTNAME => "Doe",
                UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
                UserDataServices::PARAM_TEL => "0600000000",
                UserDataServices::PARAM_EMAIL => "admin.doe@gmail.com",
                UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
                UserDataServices::PARAM_CITY => "Etic",
                UserDataServices::PARAM_POSTAL_CODE => 99999,
                UserDataServices::PARAM_COUNTRY => "France",
                UserDataServices::PARAM_SCHOOL_YEAR => 4,
                UserDataServices::PARAM_INSA_DEPT => "IF",
                UserDataServices::PARAM_AG => "2016-02-20",
                UserDataServices::PARAM_POSITION => "Secrétaire Général"

            ));
        // U account
        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT, array(
                UserDataServices::PARAM_USER_ID => 3,
                UserDataServices::PARAM_GENDER => "M.",
                UserDataServices::PARAM_FIRSTNAME => "User",
                UserDataServices::PARAM_LASTNAME => "Doe",
                UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
                UserDataServices::PARAM_TEL => "0600000000",
                UserDataServices::PARAM_EMAIL => "user.doe@gmail.com",
                UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
                UserDataServices::PARAM_CITY => "Etic",
                UserDataServices::PARAM_POSTAL_CODE => 99999,
                UserDataServices::PARAM_COUNTRY => "France",
                UserDataServices::PARAM_SCHOOL_YEAR => 2,
                UserDataServices::PARAM_INSA_DEPT => "PC",
                UserDataServices::PARAM_AG => "2016-02-20",
                UserDataServices::PARAM_POSITION => "Junior DSI"
            ));
        // G account
        $kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserDataServices::INSERT, array(
                UserDataServices::PARAM_USER_ID => 4,
                UserDataServices::PARAM_GENDER => "M.",
                UserDataServices::PARAM_FIRSTNAME => "Guest",
                UserDataServices::PARAM_LASTNAME => "Doe",
                UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
                UserDataServices::PARAM_TEL => "0600000000",
                UserDataServices::PARAM_EMAIL => "guest.doe@gmail.com",
                UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
                UserDataServices::PARAM_CITY => "Etic",
                UserDataServices::PARAM_POSTAL_CODE => 99999,
                UserDataServices::PARAM_COUNTRY => "France",
                UserDataServices::PARAM_SCHOOL_YEAR => 3,
                UserDataServices::PARAM_INSA_DEPT => "GI",
                UserDataServices::PARAM_AG => "2015-10-04",
                UserDataServices::PARAM_POSITION => "Trésorier"
            ));

        $kernel->GetDBObject(IntMembershipDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(IntMembershipServices::INSERT, array(
                IntMembershipServices::PARAM_USER => 1,
                IntMembershipServices::PARAM_START => "2016-02-02",
                IntMembershipServices::PARAM_FEE => 0,
                IntMembershipServices::PARAM_FORM => 1,
                IntMembershipServices::PARAM_CERTIF => 1,
                IntMembershipServices::PARAM_RIB => 1,
                IntMembershipServices::PARAM_IDENTITY => 0
            ));

        $kernel->GetDBObject(TeamDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TeamServices::INSERT, array(
                TeamServices::PARAM_LEADER_ID => 1,
                TeamServices::PARAM_NAME => "Doletic",
                TeamServices::PARAM_CREATION_DATE => date('Y-m-d'),
                TeamServices::PARAM_DIVISION => "DSI"
            ));
        $kernel->GetDBObject(TeamDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TeamServices::INSERT_MEMBER, array(
                TeamServices::PARAM_ID => 1,
                TeamServices::PARAM_MEMBER_ID => array(2)
            ));
        // --------------------------------------------------------------
        parent::endlog("done !");
        // --- fill maillist
        parent::info("Filling mailing list object related tables...", true);
        // --------------------------------------------------------------
        // membres-ca@...
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::INSERT, array(
                MailingListServices::PARAM_CAN_SUBSCRIBE => "false",
                MailingListServices::PARAM_NAME => "Membres CA"));
        // membres-ca@...
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::INSERT, array(
                MailingListServices::PARAM_CAN_SUBSCRIBE => "true",
                MailingListServices::PARAM_NAME => "Newsletter"));
        // attach users to mailing lists
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::AFFECT, array(
                MailingListServices::PARAM_MAILLIST_ID => 1,
                MailingListServices::PARAM_USER_ID => 1));
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::AFFECT, array(
                MailingListServices::PARAM_MAILLIST_ID => 1,
                MailingListServices::PARAM_USER_ID => 2));
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::AFFECT, array(
                MailingListServices::PARAM_MAILLIST_ID => 2,
                MailingListServices::PARAM_USER_ID => 1));
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::AFFECT, array(
                MailingListServices::PARAM_MAILLIST_ID => 2,
                MailingListServices::PARAM_USER_ID => 3));
        $kernel->GetDBObject(MailingListDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(MailingListServices::AFFECT, array(
                MailingListServices::PARAM_MAILLIST_ID => 2,
                MailingListServices::PARAM_USER_ID => 4));
        // --------------------------------------------------------------
        parent::endlog("done !");
        // --- disconnect database
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- fake data process ends --");
    }
}

//________________________________________________________________________________________________________________________
// ------------- declare script in itself --------------------------------------------------------------------------------

class DBInitializerScript extends AbstractScript
{

    public function __construct($arg_v)
    {
        // ---- build parent
        parent::__construct($arg_v, "DBInitializerScript", true, "This script can be used to automate database operations.");
        // ---- add script functions
        parent::addFunction(new ResetDBFunction($this));
        parent::addFunction(new FakeDataFunction($this));
    }
}

//________________________________________________________________________________________________________________________
// ------------- run script ----------------------------------------------------------------------------------------------

$script = new DBInitializerScript($argv);
$script->Run();