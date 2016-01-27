<?php

require_once "DoleticKernel.php";
require_once "../services/Services.php";

/**
 *	@warning all displayXXX and service function exit script after their execution
 *				in order to ensure nothing is printed after.
 */
class Main {

	// -- consts
	// --- GET & POST keys
	const RPARAM_QUERY = "q";
	// --- GET specific params
	const GPARAM_PAGE = "page";
	// --- POST specific params
	const PPARAM_USER = "user";
	const PPARAM_HASH = "hash";
	// --- SESSION keys
	const SPARAM_DOL_KERN = "doletic_kernel";
	// --- known queries
	const QUERY_SERVICE = "service";
	const QUERY_LOGOUT = "logout";
	const QUERY_AUTHEN = "auth";
	const QUERY_INTERF = "ui";

	// -- functions

	public function Run() {
		// retreive session
		session_start();	
		// check if doletic kernel exists in session
		if(array_key_exists(Main::SPARAM_DOL_KERN, $_SESSION)) {
			// reload settings
			$_SESSION[Main::SPARAM_DOL_KERN]->ReloadSettings();
			// connect database
			$_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
			// check if kernel has a valid user registered
			if($_SESSION[Main::SPARAM_DOL_KERN]->HasValidUser()) {
				// check if query received in GET
			  	if(array_key_exists(Main::RPARAM_QUERY, $_GET)) { 
					// GET query is about logout
					if(!strcmp($_GET[Main::RPARAM_QUERY], Main::QUERY_LOGOUT)) {
						$this->__display_logout();
					} else 
					// GET query is about interface
					if(!strcmp($_GET[Main::RPARAM_QUERY], Main::QUERY_INTERF)) {
						$this->__display_interface();
					}
				} //check if query received in POST
				else if(array_key_exists(Main::RPARAM_QUERY, $_POST)) {
					// GET query is about interface
					if(!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_SERVICE)) {
						$this->__service();
					}
				} else {
					$this->__display_home();	
				}
			}  //check if query received in POST
			else if(array_key_exists(Main::RPARAM_QUERY, $_POST)) {
				// POST query is about authentication
				if(!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_AUTHEN)) {
					$this->__authenticate();
				}
			} else { // if no valid user ask for a login
				$this->__display_login();
			} 
			// disconnect database
			$_SESSION[Main::SPARAM_DOL_KERN]->DisconnectDB();
		} else { // if no doletic kernel in session, create one
			$this->__init();
		}
		exit; // terminate script explicitly
	}

# PROTECTED & PRIVATE ##########################################################

	private function __init() {
		// Create a doletic kernel, initialize it & put it in session vars
		$_SESSION[Main::SPARAM_DOL_KERN] = new DoleticKernel();
		$_SESSION[Main::SPARAM_DOL_KERN]->Init();
		// connect to database
		$_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
		// call login to show login interface
		$this->__display_login();
	}

	private function __authenticate() {
		// check if all post params are present
		if(array_key_exists(Main::PPARAM_USER, $_POST) &&
		   array_key_exists(Main::PPARAM_HASH, $_POST)) {
			// Ask kernel to authenticate user
			$ok = $_SESSION[Main::SPARAM_DOL_KERN]->AuthenticateUser($_POST[Main::PPARAM_USER], $_POST[Main::PPARAM_HASH]);
			// Terminate returning approriated json structure
			$this->__terminate(json_encode(array('authenticated' => $ok)));
		} else { // if params are missing show login page
			$this->__display_login();
		}
	}

	private function __service() {
		// create service instance
		$service = new Services($_SESSION[Main::SPARAM_DOL_KERN]);
		// check if minimum post params are present
		if(array_key_exists(Services::PPARAM_OBJ, $_POST) &&
		   array_key_exists(Services::PPARAM_ACT, $_POST)) {
			// return service response JSON encoded
			$this->__terminate($service->Response($_POST));
		} else { // if params are missing return service default response
			$this->__terminate($service->DefaultResponse());
		}
	}

	private function __display_interface() {
		// check if params
		if(array_key_exists(Main::GPARAM_PAGE, $_GET)) {
			// if user asks for login page
			if($_GET[Main::GPARAM_PAGE] === UIManager::INTERFACE_LOGIN) {
				// display home
				$this->__display_home();
			} else // if user asks for logout page
			if($_GET[Main::GPARAM_PAGE] === UIManager::INTERFACE_LOGOUT) {
					// display logout
					$this->__display_logout();
			} else {
				// display given interface
				$this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterface($_GET[Main::GPARAM_PAGE]));	
			}
		} else {
			// display page not found interface
			$this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterface(UIManager::INTERFACE_404));
		}
	}

	private function __display_login() {
		// display login interface
		$this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterface(UIManager::INTERFACE_LOGIN));
	}

	private function __display_logout() {
		// display logout interface
		echo $_SESSION[Main::SPARAM_DOL_KERN]->GetInterface(UIManager::INTERFACE_LOGOUT);
		// unset session vars
		$_SESSION = array();
		// destroy session
		session_destroy();
		// exit explicitly
		exit;
	}

	private function __display_home() {
		// load home interface
		$this->__terminate($_SESSION[Main::SPARAM_DOL_KERN]->GetInterface(UIManager::INTERFACE_HOME));
	}

	private function __terminate($response) {
		// print
		echo $response;
		// disconnect database
		$_SESSION[Main::SPARAM_DOL_KERN]->DisconnectDB();
		// explicitly exit
		exit; 
	}

}

// Create an instance of main and run it

$main = new Main();
$main->Run();