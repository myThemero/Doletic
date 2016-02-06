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
		$kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(TicketServices::INSERT, array(
			TicketServices::PARAM_RECEIVER 	=> 1,
			TicketServices::PARAM_SUBJECT 	=> "Test subject",
			TicketServices::PARAM_CATEGO 	=> 2,
			TicketServices::PARAM_DATA 		=> "Test data",
			TicketServices::PARAM_STATUS 	=> 3));
		// --------------------------------------------------------------
		parent::endlog("done !");
		// --- fill users
		parent::info("Filling user object related tables...", true);
		// --------------------------------------------------------------
		// SA account
		$kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserServices::INSERT, array(
			UserServices::PARAM_UNAME 	=> "super-admin.doe",
			UserServices::PARAM_HASH 	=> sha1("password")));
		// A account
		$kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserServices::INSERT, array(
			UserServices::PARAM_UNAME 	=> "admin.doe",
			UserServices::PARAM_HASH 	=> sha1("password")));
		// U account
		$kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserServices::INSERT, array(
			UserServices::PARAM_UNAME 	=> "user.doe",
			UserServices::PARAM_HASH 	=> sha1("password")));
		// G account
		$kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserServices::INSERT, array(
			UserServices::PARAM_UNAME 	=> "guest.doe",
			UserServices::PARAM_HASH 	=> sha1("password")));
		// --------------------------------------------------------------
		parent::endlog("done !");
		// --- fill userdata
		parent::info("Filling userdata object related tables...", true);
		// --------------------------------------------------------------
		// SA account
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::INSERT, array(
					UserDataServices::PARAM_USER_ID => 1,
					UserDataServices::PARAM_GENDER_ID => 1,
					UserDataServices::PARAM_FIRSTNAME => "SuperAdmin",
					UserDataServices::PARAM_LASTNAME => "Doe", 
					UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
					UserDataServices::PARAM_TEL => "0600000000", 	
					UserDataServices::PARAM_EMAIL => "super-admin.doe@gmail.com",  
					UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
					UserDataServices::PARAM_COUNTRY_ID => 153,
					UserDataServices::PARAM_SCHOOL_YEAR => "4ème",
					UserDataServices::PARAM_INSA_DEPT_ID => 10 
						));
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::UPDATE_POSTION, array(
					UserDataServices::PARAM_USER_ID => 1,
					UserDataServices::PARAM_POSITION_ID => 6
					));
		// A account
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::INSERT, array(
					UserDataServices::PARAM_USER_ID => 2,
					UserDataServices::PARAM_GENDER_ID => 1,
					UserDataServices::PARAM_FIRSTNAME => "Admin",
					UserDataServices::PARAM_LASTNAME => "Doe", 
					UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
					UserDataServices::PARAM_TEL => "0600000000", 	
					UserDataServices::PARAM_EMAIL => "admin.doe@gmail.com",  
					UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
					UserDataServices::PARAM_COUNTRY_ID => 153,
					UserDataServices::PARAM_SCHOOL_YEAR => "4ème",
					UserDataServices::PARAM_INSA_DEPT_ID => 10 
						));
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::UPDATE_POSTION, array(
					UserDataServices::PARAM_USER_ID => 2,
					UserDataServices::PARAM_POSITION_ID => 1
					));
		// U account
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::INSERT, array(
					UserDataServices::PARAM_USER_ID => 3,
					UserDataServices::PARAM_GENDER_ID => 1,
					UserDataServices::PARAM_FIRSTNAME => "User",
					UserDataServices::PARAM_LASTNAME => "Doe", 
					UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
					UserDataServices::PARAM_TEL => "0600000000", 	
					UserDataServices::PARAM_EMAIL => "user.doe@gmail.com",  
					UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
					UserDataServices::PARAM_COUNTRY_ID => 153,
					UserDataServices::PARAM_SCHOOL_YEAR => "4ème",
					UserDataServices::PARAM_INSA_DEPT_ID => 10 
						));
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::UPDATE_POSTION, array(
					UserDataServices::PARAM_USER_ID => 3,
					UserDataServices::PARAM_POSITION_ID => 11
					));
		// G account
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::INSERT, array(
					UserDataServices::PARAM_USER_ID => 4,
					UserDataServices::PARAM_GENDER_ID => 1,
					UserDataServices::PARAM_FIRSTNAME => "Guest",
					UserDataServices::PARAM_LASTNAME => "Doe", 
					UserDataServices::PARAM_BIRTHDATE => "1994-02-13",
					UserDataServices::PARAM_TEL => "0600000000", 	
					UserDataServices::PARAM_EMAIL => "guest.doe@gmail.com",  
					UserDataServices::PARAM_ADDRESS => "1 avenue Doletic",
					UserDataServices::PARAM_COUNTRY_ID => 153,
					UserDataServices::PARAM_SCHOOL_YEAR => "4ème",
					UserDataServices::PARAM_INSA_DEPT_ID => 10 
						));
		$kernel->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($kernel->GetCurrentUser())
				->GetResponseData(UserDataServices::UPDATE_POSTION, array(
					UserDataServices::PARAM_USER_ID => 4,
					UserDataServices::PARAM_POSITION_ID => 16
					));
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