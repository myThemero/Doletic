<?php

require_once "DoleticKernel.php";
require_once "interfaces/AbstractDBObject.php";

class Tests {

	// -- functions
	public static function Run($test) {
		$all=!strcmp($test, "");
		if(!strcmp($test, "clear") || $all) {
			Tests::__test_db_clear();
		}
		if(!strcmp($test, "reset") || $all) {
			Tests::__test_db_reset();
		}
		if(!strcmp($test, "update") || $all) {
			Tests::__test_db_update();
		}
		if(!strcmp($test, "dbo_ticket") || $all) {
			Tests::__test_dbo_ticket();
		}
		if(!strcmp($test, "dbo_user") || $all) {
			Tests::__test_dbo_user();
		}
		if(!strcmp($test, "dbo_udata") || $all) {
			Tests::__test_dbo_udata();
		}
		if(!strcmp($test, "dbo_comment") || $all) {
			Tests::__test_dbo_comment();
		}
		if(!strcmp($test, "dbo_module") || $all) {
			Tests::__test_dbo_module();
		}
		if(!strcmp($test, "dbo_upload") || $all) {
			Tests::__test_dbo_upload();
		}
		if(!strcmp($test, "ui") || $all) {
			Tests::__test_get_interface();
		}
	}


# PROTECTED & PRIVATE ##########################################

	/*
	 *	Test prototype :
	 *
	 *	private static function __test_xxx() {
	 *		$kernel = Tests::__init_kernel__("__test_xxx"); // initialize kernel
	 *		// --------- content ------------
 	 *		echo "/// \\todo implement this test !\n";
	 *		// ----------- end --------------
	 *		Tests::__terminate_kernel__($kernel); // terminate kernel
	 *	}
	 *
	 */
	// -------------------------------------------------------------------------
	// --------------------------------- TESTS ---------------------------------
	// -------------------------------------------------------------------------
	/**
	 *	Test for DoleticKernel DB clear function
	 */
	private static function __test_db_clear() {
		$kernel = Tests::__init_kernel__("__test_db_clear"); // initialize kernel
		// --------- content ------------
		$kernel->ClearDatabase();
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DoleticKernel DB reset function
	 */
	private static function __test_db_reset() {
		$kernel = Tests::__init_kernel__("__test_db_reset"); // initialize kernel
		// --------- content ------------
		$kernel->ResetDatabase();
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DoleticKernel DB update function
	 */
	private static function __test_db_update() {
		$kernel = Tests::__init_kernel__("__test_db_update");
		// --------- content ------------
		$kernel->UpdateDatabase();
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel);
	}
	/**
	 *	Test for DB ticket object
	 */
	private static function __test_dbo_ticket() {
		$kernel = Tests::__init_kernel__("__test_dbo_ticket"); // initialize kernel
		// --------- content ------------
		// insert
		$data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(TicketServices::INSERT, array(
								TicketServices::PARAM_SENDER => 0,
								TicketServices::PARAM_RECEIVER => 1,
								TicketServices::PARAM_SUBJECT => "Test subject",
								TicketServices::PARAM_CATEGO => 2,
								TicketServices::PARAM_DATA => "Test data",
								TicketServices::PARAM_STATUS => 3));
		var_dump($data);
		// get all
		$data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(TicketServices::GET_ALL_TICKETS, array());
		var_dump($data);
		// update
		$data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices()
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
		$data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(TicketServices::GET_TICKET_BY_ID, array(
								TicketServices::PARAM_ID => 1));
		var_dump($data);
		// archive
		$data = $kernel->GetDBObject(TicketDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(TicketServices::ARCHIVE, array(
								TicketServices::PARAM_ID => 1));
		var_dump($data);
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DB user object
	 */
	private static function __test_dbo_user() {
		$kernel = Tests::__init_kernel__("__test_dbo_user"); // initialize kernel
		// --------- content ------------
		// insert
		$data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(UserServices::INSERT, array(
								UserServices::PARAM_UNAME => "user.test",
								UserServices::PARAM_HASH => "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8")); // sha1("password");
		var_dump($data);
		// get all
		$data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(UserServices::GET_ALL_USERS, array());
		var_dump($data);
		// update
		$data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(UserServices::UPDATE, array(
								UserServices::PARAM_ID => 1,
								UserServices::PARAM_HASH => "6184d6847d594ec75c4c07514d4bb490d5e166df"));	// sha1("blank");
		var_dump($data);
		// get by id
		$data = $kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices()
							->GetResponseData(UserServices::GET_USER_BY_ID, array(
								UserServices::PARAM_ID => 1));
		var_dump($data);
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DB userdata object
	 */
	private static function __test_dbo_udata() {
		$kernel = Tests::__init_kernel__("__test_dbo_udata"); // initialize kernel
		// --------- content ------------
		echo "/// \\todo implement this test !\n";
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DB comment object
	 */
	private static function __test_dbo_comment() {
		$kernel = Tests::__init_kernel__("__test_dbo_comment"); // initialize kernel
		// --------- content ------------
		echo "/// \\todo implement this test !\n";
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DB comment object
	 */
	private static function __test_dbo_module() {
		$kernel = Tests::__init_kernel__("__test_dbo_module"); // initialize kernel
		// --------- content ------------
		echo "/// \\todo implement this test !\n";
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DB comment object
	 */
	private static function __test_dbo_upload() {
		$kernel = Tests::__init_kernel__("__test_dbo_upload"); // initialize kernel
		// --------- content ------------
		// insert
		$data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(UploadServices::INSERT, array(
		    			UploadServices::PARAM_USER_ID => 1,
		    			UploadServices::PARAM_FNAME => 'fakefile.pdf',
		    			UploadServices::PARAM_STOR_FNAME => '/fake_2016_01_27_17_55_30.pdf'));
		var_dump($data);
		// get all
		$data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(UploadServices::GET_ALL_UPLOADS, array());
		var_dump($data);
		// update
		$data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(UploadServices::UPDATE, array(
						UploadServices::PARAM_ID => 1,
						UploadServices::PARAM_FNAME => "fakefile_renamed.pdf"));
		var_dump($data);
		// get by id
		$data = $kernel->GetDBObject(UploadDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(UploadServices::GET_UPLOAD_BY_ID, array(
						UploadServices::PARAM_ID => 1));
		var_dump($data);
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *	Test for DoleticKernel GetInterface function
	 */
	private static function __test_get_interface() {
		$kernel = Tests::__init_kernel__("__test_get_interface"); // initialize kernel
		// --------- content ------------
		// -- specials ui
		echo $kernel->GetInterface(UIManager::INTERFACE_LOGIN);
		echo $kernel->GetInterface(UIManager::INTERFACE_LOGOUT);
		echo $kernel->GetInterface(UIManager::INTERFACE_404);
		echo $kernel->GetInterface(UIManager::INTERFACE_HOME);
		// -- unknown ui
		echo $kernel->GetInterface("unknown:strange:ui");
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	// -------------------------------------------------------------------------
	// -------------------------------- COMMON ---------------------------------
	// -------------------------------------------------------------------------
	/**
	 *
	 */
	private static function __init_kernel__($test) {
		echo "Running $test...\n";
	 	echo "// ---------- test - start -------------\n\n";
		$kernel = new DoleticKernel(); 	// instanciate
		$kernel->Init();				// initialize
		$kernel->ConnectDB();		// connect database
		return $kernel;
	}
	/**
	 *
	 */
	private static function __terminate_kernel__(&$kernel) {
		$kernel->DisconnectDB(); // disconnect database
		$kernel = null; // destroy kernel explicitly
		echo "\n// ----------- test - end --------------\n";
	 	echo "...done !\n";
	}
	private function __constructs() {
		// cannot be constructed
	}
}

# SCRIPT
// retrieve argument
$arg = "";
if(sizeof($argv) > 1) {
	$arg = $argv[1];
}
// run tests
Tests::Run($arg);

