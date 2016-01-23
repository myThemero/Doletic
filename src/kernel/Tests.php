<?php

require_once "DoleticKernel.php";
require_once "interfaces/AbstractDBObject.php";

class Tests {

	// -- functions
	public static function Run($test) {
		$all=!strcmp($test, "");
		if(!strcmp($test, "reset") || $all) {
			Tests::__test_db_reset();
		} else
		if(!strcmp($test, "update") || $all) {
			Tests::__test_db_update();
		} else
		if(!strcmp($test, "insert_ticket") || $all) {
			Tests::__test_db_insert_ticket();
		}
	}


# PROTECTED & PRIVATE ##########################################

	/*
	 *	Test prototype :
	 *
	 *	private static function __test_xxx() {
	 *		$kernel = Tests::__init_kernel__("__test_xxx"); // initialize kernel
	 *		// --------- content ------------
 	 *
	 *		// ----------- end --------------
	 *		Tests::__terminate_kernel__($kernel); // terminate kernel
	 *	}
	 *
	 */
	// --------------------------------- TESTS ---------------------------------
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
	private static function __test_db_insert_ticket() {
		$kernel = Tests::__init_kernel__("__test_db_insert_ticket"); // initialize kernel
		// --------- content ------------
		$data = $kernel->GetDBObject("ticket")->GetServices()->GetResponseData("insert", array(
			"senderId" => 0,
			"receiverId" => 1,
			"subject" => "Test subject",
			"categoryId" => 2,
			"data" => "Test data",
			"statusId" => 3));
		var_dump($data);
		// ----------- end --------------
		Tests::__terminate_kernel__($kernel); // terminate kernel
	}

	// -------------------------------- COMMON --------------------------------
	/**
	 *
	 */
	private static function __init_kernel__($test) {
		echo "Running $test...\n";
	 	echo "// --------- content ------------\n\n";
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
		echo "\n// ----------- end --------------\n";
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

