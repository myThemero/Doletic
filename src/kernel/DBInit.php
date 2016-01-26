<?php

require_once "DoleticKernel.php";

class DBInitializer {

	// -- functions
	public static function Run($arg) {
		$kernel = DBInitializer::__init_kernel__();
		// ----- fill functions ------
		DBInitializer::__fill_ticket($kernel);
		DBInitializer::__fill_user($kernel);
		DBInitializer::__fill_udata($kernel);
		// ---------------------------
		DBInitializer::__terminate_kernel__($kernel);
	}


# PROTECTED & PRIVATE ##########################################

	/**
	 *	Test for DB ticket object
	 */
	private static function __fill_ticket($kernel) {
		DBInitializer::__partial_trace("Filling ticket object related tables...");
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
		DBInitializer::__finalize_trace("done !");
	}
	/**
	 *	Test for DB user object
	 */
	private static function __fill_user($kernel) {
		DBInitializer::__partial_trace("Filling user object related tables...");
		// --------------------------------------------------------------
		$kernel->GetDBObject(UserDBObject::OBJ_NAME)->GetServices()
					->GetResponseData(UserServices::INSERT, array(
			"username" => "user.test",
			"password" => sha1("password")));
		// --------------------------------------------------------------
		DBInitializer::__finalize_trace("done !");
	}
	/**
	 *	Test for DB userdata object
	 */
	private static function __fill_udata($kernel) {
		DBInitializer::__partial_trace("Filling userdata object related tables...");
		// --------------------------------------------------------------
		DBInitializer::__finalize_trace("skipped !");
		return;
		// --------------------------------------------------------------
		DBInitializer::__finalize_trace("done !");
	}
	// -------------------------------------------------------------------------
	// -------------------------------- COMMON ---------------------------------
	// -------------------------------------------------------------------------
	/**
	 *
	 */
	private static function __init_kernel__($test) {
		
	 	DBInitializer::__trace("-- dbinit process start --");
		$kernel = new DoleticKernel(); 	// instanciate
		$kernel->Init();				// initialize
		$kernel->ConnectDB();			// connect database
		DBInitializer::__partial_trace("Reseting database...");
		$kernel->ResetDatabase(); 		// full database reset
		DBInitializer::__finalize_trace("done !");
		return $kernel;
	}
	/**
	 *
	 */
	private static function __terminate_kernel__(&$kernel) {
		$kernel->DisconnectDB(); // disconnect database
		$kernel = null; // destroy kernel explicitly
		DBInitializer::__trace("-- dbinit process end --");
	}
	private static function __partial_trace($msg) {
		echo "[TRACE]{DBInit.php} > $msg";
	}
	private static function __finalize_trace($msg) {
		echo "$msg\n";
	}
	private static function __trace($msg) {
		echo "[TRACE]{DBInit.php} > $msg\n";
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
DBInitializer::Run($arg);
