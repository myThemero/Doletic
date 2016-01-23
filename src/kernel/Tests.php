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
	// --------------------------------- TESTS ---------------------------------
	/**
	 *
	 */
	private static function __test_db_clear() {
		$kernel = Tests::__init_kernel__("__test_db_clear"); // initialize kernel
		// --------- content ------------
		$kernel->ClearDatabase();
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *
	 */
	private static function __test_db_reset() {
		$kernel = Tests::__init_kernel__("__test_db_reset"); // initialize kernel
		// --------- content ------------
		$kernel->ResetDatabase();
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *
	 */
	private static function __test_db_update() {
		$kernel = Tests::__init_kernel__("__test_db_update");
		// --------- content ------------
		$kernel->UpdateDatabase();
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel);
	}
	/**
	 *
	 */
	private static function __test_dbo_ticket() {
		$kernel = Tests::__init_kernel__("__test_dbo_ticket"); // initialize kernel
		// --------- content ------------
		// insert
		$data = $kernel->GetDBObject("ticket")->GetServices()->GetResponseData("insert", array(
			"senderId" => 0,
			"receiverId" => 1,
			"subject" => "Test subject",
			"categoryId" => 2,
			"data" => "Test data",
			"statusId" => 3));
		var_dump($data);
		// get all
		$data = $kernel->GetDBObject("ticket")->GetServices()->GetResponseData("allt", array());
		var_dump($data);
		// update
		$data = $kernel->GetDBObject("ticket")->GetServices()->GetResponseData("update", array(
			"id" => 1,
			"senderId" => -1,
			"receiverId" => -1,
			"subject" => "Another test subject",
			"categoryId" => -1,
			"data" => "Another test data",
			"statusId" => -1));
		var_dump($data);
		// get by id
		$data = $kernel->GetDBObject("ticket")->GetServices()->GetResponseData("byidt", array("id" => 1));
		var_dump($data);
		// archive
		$data = $kernel->GetDBObject("ticket")->GetServices()->GetResponseData("archive", array("id" => 1));
		var_dump($data);
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *
	 */
	private static function __test_dbo_user() {
		$kernel = Tests::__init_kernel__("__test_dbo_user"); // initialize kernel
		// --------- content ------------
		// insert
		$data = $kernel->GetDBObject("user")->GetServices()->GetResponseData("insert", array(
			"username" => "user.test",
			"password" => "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8")); // sha1("password");
		var_dump($data);
		// get all
		$data = $kernel->GetDBObject("user")->GetServices()->GetResponseData("all", array());
		var_dump($data);
		// update
		$data = $kernel->GetDBObject("user")->GetServices()->GetResponseData("update", array(
			"id" => 1,
			"password" => "6184d6847d594ec75c4c07514d4bb490d5e166df"));	// sha1("blank");
		var_dump($data);
		// get by id
		$data = $kernel->GetDBObject("user")->GetServices()->GetResponseData("byid", array("id" => 1));
		var_dump($data);
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}
	/**
	 *
	 */
	private static function __test_dbo_udata() {
		$kernel = Tests::__init_kernel__("__test_dbo_udata"); // initialize kernel
		// --------- content ------------
		echo "/// \\todo implement this test !\n";
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}

	// -------------------------------- COMMON --------------------------------
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

