<?php

require_once "interfaces/AbstractScript.php";
require_once "DoleticKernel.php";

//________________________________________________________________________________________________________________________
// ------------- declare script functions --------------------------------------------------------------------------------

class ResetDBFunction extends AbstractFunction {
	public function __construct($script) {
		parent::__construct($script, 
			'Reset DB', 
			'-rdb', 
			'--reset-database', 
			"Reset database dropping tables and creating them again.");
	}
	public function Execute() {
		parent::info("-- dbinit process starts --");
		$kernel = new DoleticKernel(); 	// instanciate
		$kernel->Init();				// initialize
		$kernel->ConnectDB();			// connect database
		parent::info("Reseting database...", true);
		$kernel->ResetDatabase(); 		// full database reset
		parent::endlog("done !");
		$kernel = null;
		parent::info("-- dbinit process ends --");
	}
}
class FakeDataFunction extends AbstractFunction {
	public function __construct($script) {
		parent::__construct($script, 
			'Fake Data', 
			'-fd', 
			'--fake-data', 
			"Adds fake data to the data base.");
	}
	public function Execute() {
		parent::info("-- fake data process starts --");
		$kernel = new DoleticKernel(); 	// instanciate
		$kernel->Init();				// initialize
		$kernel->ConnectDB();			// connect database
		// --- fill tickets
		parent::info("Filling ticket object related tables...", true);
		// --------------------------------------------------------------
		$kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(TicketServices::INSERT, array(
			"senderId" => 0,
			"receiverId" => 1,
			"subject" => "Test subject",
			"categoryId" => 2,
			"data" => "Test data",
			"statusId" => 3));
		// --------------------------------------------------------------
		parent::endlog("done !");
		// --- fill users
		parent::info("Filling user object related tables...", true);
		// --------------------------------------------------------------
		$kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(UserServices::INSERT, array(
			"username" => "user.test",
			"password" => sha1("password")));
		// --------------------------------------------------------------
		parent::endlog("done !");
		// --- fill userdata
		parent::info("Filling userdata object related tables...", true);
		// --------------------------------------------------------------
		parent::warn("skipped !");
		// --------------------------------------------------------------
		//DBInitializer::info("done !");
		// --- disconnect database
		parent::endlog("done !");
		$kernel->DisconnectDB();
		$kernel = null;
		parent::info("-- fake data process ends --");
	}
}

//________________________________________________________________________________________________________________________
// ------------- declare script in itself --------------------------------------------------------------------------------

class DBInitializerScript extends AbstractScript {

	public function __construct($arg_v) {
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