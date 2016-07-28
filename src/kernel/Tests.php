<?php

require_once "interfaces/AbstractScript.php";
require_once "DoleticKernel.php";
require_once "services/Services.php";


//________________________________________________________________________________________________________________________
// ------------- declare script functions --------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------------------------
//	DATABASE RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class DBClearTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Clear Test',
            '-dbcl',
            '--test-database-clear',
            "Test database clear function.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        $kernel->ClearDatabase();
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBResetTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Reset Test',
            '-dbr',
            '--test-database-reset',
            "Test database reset function.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        $kernel->ResetDatabase();
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBUpdateTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Update Test',
            '-dbu',
            '--test-database-update',
            "Test database update function.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        $kernel->UpdateDatabase();
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

// ---------------------------------------------------------------------------------------------------
//	DATABASE OBJECTS RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class DBObjectTicketTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Object Ticket Test',
            '-dbot',
            '--test-object-ticket',
            "Test database object ticket.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        // insert
        $data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TicketServices::INSERT, array(
                TicketServices::PARAM_SENDER => 0,
                TicketServices::PARAM_RECEIVER => 1,
                TicketServices::PARAM_SUBJECT => "Test subject",
                TicketServices::PARAM_CATEGO => 2,
                TicketServices::PARAM_DATA => "Test data",
                TicketServices::PARAM_STATUS => 3));
        var_dump($data);
        // get all
        $data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TicketServices::GET_ALL_TICKETS, array());
        var_dump($data);
        // update
        $data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TicketServices::UPDATE, array(
                TicketServices::PARAM_ID => 1,
                TicketServices::PARAM_SENDER => -1,
                TicketServices::PARAM_RECEIVER => -1,
                TicketServices::PARAM_SUBJECT => "Another test subject",
                TicketServices::PARAM_CATEGO => -1,
                TicketServices::PARAM_DATA => "Another test data",
                TicketServices::PARAM_STATUS => -1));
        var_dump($data);
        // get by id
        $data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TicketServices::GET_TICKET_BY_ID, array(
                TicketServices::PARAM_ID => 1));
        var_dump($data);
        // archive
        $data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(TicketServices::ARCHIVE, array(
                TicketServices::PARAM_ID => 1));
        var_dump($data);
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBObjectUserTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Object User Test',
            '-dbou',
            '--test-object-user',
            "Test database object user.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        // insert
        $data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::INSERT, array(
                UserServices::PARAM_UNAME => "user.test",
                UserServices::PARAM_HASH => "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8")); // sha1("password");
        var_dump($data);
        // get all
        $data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::GET_ALL_USERS, array());
        var_dump($data);
        // update
        $data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::UPDATE, array(
                UserServices::PARAM_ID => 1,
                UserServices::PARAM_HASH => "6184d6847d594ec75c4c07514d4bb490d5e166df"));    // sha1("blank");
        var_dump($data);
        // get by id
        $data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UserServices::GET_USER_BY_ID, array(
                UserServices::PARAM_ID => 1));
        var_dump($data);
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBObjectUserDataTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Object UserData Test',
            '-dboud',
            '--test-object-user-data',
            "Test database object user data.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        parent::warn("/// \\todo implement this test !");
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBObjectCommentTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Object Comment Test',
            '-dboc',
            '--test-object-comment',
            "Test database object comment.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        parent::warn("/// \\todo implement this test !");
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBObjectModuleTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Object Module Test',
            '-dbom',
            '--test-object-module',
            "Test database object module.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        parent::warn("/// \\todo implement this test !");
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class DBObjectUploadTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'DB Object Upload Test',
            '-dboup',
            '--test-object-upload',
            "Test database object upload.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        // insert
        $data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UploadServices::INSERT, array(
                UploadServices::PARAM_USER_ID => 1,
                UploadServices::PARAM_FNAME => 'fakefile.pdf',
                UploadServices::PARAM_STOR_FNAME => '/fake_2016_01_27_17_55_30.pdf'));
        var_dump($data);
        // get all
        $data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UploadServices::GET_ALL_UPLOADS, array());
        var_dump($data);
        // update
        $data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UploadServices::UPDATE, array(
                UploadServices::PARAM_ID => 1,
                UploadServices::PARAM_FNAME => "fakefile_renamed.pdf"));
        var_dump($data);
        // get by id
        $data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
            ->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
                UploadServices::PARAM_ID => 1));
        var_dump($data);
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

// ---------------------------------------------------------------------------------------------------
//	USER INTERFACE RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class UITestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'UI Test',
            '-ui',
            '--test-ui',
            "Test kernel user interface.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        // -- specials ui
        echo $kernel->GetInterface(UIManager::INTERFACE_LOGIN);
        echo $kernel->GetInterface(UIManager::INTERFACE_LOGOUT);
        echo $kernel->GetInterface(UIManager::INTERFACE_404);
        echo $kernel->GetInterface(UIManager::INTERFACE_HOME);
        // -- unknown ui
        echo $kernel->GetInterface("unknown:strange:ui");
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

class UILinksTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'UI Links Test',
            '-uil',
            '--test-ui-links',
            "Test kernel user interface links getter.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        // --------- content ------------
        var_dump($kernel->GetModuleUILinks());
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

// ---------------------------------------------------------------------------------------------------
//	SERVICES RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class ServiceUILinksTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'Service UI Links Test',
            '-suil',
            '--test-service-ui-links',
            "Test kernel user interface links service.");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        $kernel = new DoleticKernel();    // instanciate
        $kernel->Init();                // initialize
        $kernel->ConnectDB();            // connect database
        $services = new Services($kernel); // create services
        // --------- content ------------
        echo $services->Response(array(
            Services::PPARAM_OBJ => "service",
            Services::PPARAM_ACT => "uilinks"), true);
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

// ---------------------------------------------------------------------------------------------------
//	WRAPPERS RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class OVHMailWrapperTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'OVH Mail Wrapper Test',
            '-omw',
            '--ovh-mail-wrapper',
            "Test OVH Mail Wrapper functions");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        echo "before kernel construction\n"; // DEBUG
        $kernel = new DoleticKernel();    // instanciate
        echo "before kernel initialization\n"; // DEBUG
        $kernel->Init();                // initialize
        echo "before kernel connect to db\n"; // DEBUG
        $kernel->ConnectDB();            // connect database
        // ----------- start --------------
        $wrapper = $kernel->GetWrapper(OVHMailWrapper::NAME);
        if (isset($wrapper)) {
            // liste des domaines d'ETIC
            var_dump($wrapper->Execute(OVHMailWrapper::FUNC_LIST_DOMAINS));
            // liste des mailing listes
            var_dump($wrapper->Execute(OVHMailWrapper::FUNC_LIST_MAILLISTS, array(OVHMailWrapper::ARG_DOMAIN => "etic-insa.com")));
        }
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

// ---------------------------------------------------------------------------------------------------
//	MAILER RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class MailerTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'Mailer Test',
            '-m',
            '--mailer',
            "Test Mailer functions");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        echo "before kernel construction\n"; // DEBUG
        $kernel = new DoleticKernel();    // instanciate
        echo "before kernel initialization\n"; // DEBUG
        $kernel->Init();                // initialize
        echo "before kernel connect to db\n"; // DEBUG
        $kernel->ConnectDB();            // connect database
        // ----------- start --------------
        //echo "You must edit the source and add an email address to run this test !";
        $kernel->SendMail(
            array(
                "nsorin@etic-insa.com",
                "pdautry2@etic-insa.com"
            ),
            new WelcomeMail(),
            array(
                "PRENOM" => "John",
                "URL" => "www.google.com"
            )
        );
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

// ---------------------------------------------------------------------------------------------------
//	LOGGER RELATED TEST FUNCTIONS
// ---------------------------------------------------------------------------------------------------
class LoggerTestFunction extends AbstractFunction
{
    public function __construct($script)
    {
        parent::__construct($script,
            'Logger Test',
            '-l',
            '--logger',
            "Test Logging functions");
    }

    public function Execute()
    {
        parent::info("-- test - starts --");
        echo "before kernel construction\n"; // DEBUG
        $kernel = new DoleticKernel();    // instanciate
        echo "before kernel initialization\n"; // DEBUG
        $kernel->Init();                // initialize
        echo "before kernel connect to db\n"; // DEBUG
        $kernel->ConnectDB();            // connect database
        // ----------- start --------------
        $kernel->LogInfo(get_class(), "Test info message");
        $kernel->LogWarning(get_class(), "Test warning message");
        $kernel->LogError(get_class(), "Test error message");
        $kernel->LogFatal(get_class(), "Test fatal message");
        // ----------- end --------------
        $kernel->DisconnectDB();
        $kernel = null;
        parent::info("-- test - ends --");
    }
}

//________________________________________________________________________________________________________________________
// ------------- declare script in itself --------------------------------------------------------------------------------

class TestScript extends AbstractScript
{

    public function __construct($arg_v)
    {
        // ---- build parent
        parent::__construct($arg_v, "TestScript", false, "This script purpose is to run unit test for Doletic.");
        // ---- add script functions
        // ----- database related tests
        parent::addFunction(new DBClearTestFunction($this));
        parent::addFunction(new DBResetTestFunction($this));
        parent::addFunction(new DBUpdateTestFunction($this));
        // ----- database objects related tests
        parent::addFunction(new DBObjectTicketTestFunction($this));
        parent::addFunction(new DBObjectUserTestFunction($this));
        parent::addFunction(new DBObjectUserDataTestFunction($this));
        parent::addFunction(new DBObjectCommentTestFunction($this));
        parent::addFunction(new DBObjectModuleTestFunction($this));
        parent::addFunction(new DBObjectUploadTestFunction($this));
        // ----- kernel ui related tests
        parent::addFunction(new UITestFunction($this));
        parent::addFunction(new UILinksTestFunction($this));
        // ----- service tests
        parent::addFunction(new ServiceUILinksTestFunction($this));
        // ----- wrappers tests
        parent::addFunction(new OVHMailWrapperTestFunction($this));
        // ----- mailer tests
        parent::addFunction(new MailerTestFunction($this));
        // ----- logger tests
        parent::addFunction(new LoggerTestFunction($this));
    }
}

//________________________________________________________________________________________________________________________
// ------------- run script ----------------------------------------------------------------------------------------------

$script = new TestScript($argv);
$script->Run();

