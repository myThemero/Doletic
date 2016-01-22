<?php

require_once "DoleticKernel.php";

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
	const QUERY_LOGOUT = "logout";
	const QUERY_AUTHEN = "auth";
	const QUERY_INTERF = "ui";

	// -- functions

	public function Run() {
		// retreive session
		session_start();	
		// check if doletic kernel exists in session
		if(array_key_exists(Main::SPARAM_DOL_KERN, $_SESSION)) {
			// connect database
			$_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
			// check if query received in GET
			if(array_key_exists(Main::RPARAM_QUERY, $_GET)) {
				// GET query is about logout
				if(!strcmp($_GET[Main::RPARAM_QUERY], Main::QUERY_LOGOUT)) {
					$this->logout();
				} else 
				// GET query is about interface
				if(!strcmp($_GET[Main::RPARAM_QUERY], Main::QUERY_INTERF)) {
					$this->displayInterface();
				}
			} else 
			//check if query received in POST
			if(array_key_exists(Main::RPARAM_QUERY, $_POST)) {
				// POST query is about authentication
				if(!strcmp($_POST[Main::RPARAM_QUERY], Main::QUERY_AUTHEN)) {
					$this->authenticate();
				}
			} else 
			// check if kernel has a valid user registered
			if($_SESSION[Main::SPARAM_DOL_KERN]->HasValidUser()) {
				$this->loadUserInterface();
			} else { // if no valid user ask for a login
				$this->login();
			}
			// disconnect database
			$_SESSION[Main::SPARAM_DOL_KERN]->DisconnectDB();
		} else { // if no doletic kernel in session, create one
			$this->initDoleticKernel();
		}
	}

# PROTECTED & PRIVATE ##########################################################

	private function initDoleticKernel() {
		// Create a doletic kernel, initialize it & put it in session vars
		$_SESSION[Main::SPARAM_DOL_KERN] = new DoleticKernel();
		$_SESSION[Main::SPARAM_DOL_KERN]->Init();
		// connect to database
		$_SESSION[Main::SPARAM_DOL_KERN]->ConnectDB();
		// call login to show login interface
		$this->login();
		// connect to database
		$_SESSION[Main::SPARAM_DOL_KERN]->DisconnectDB();
	}

	private function login() {
		
		// display login interface
		$_SESSION[Main::SPARAM_DOL_KERN]->DisplayInterface(DoleticKernel::INTERFACE_LOGIN);
	}

	private function authenticate() {
		// check if all post params are present
		if(array_key_exists(Main::PPARAM_USER, $_POST) &&
		   array_key_exists(Main::PPARAM_HASH, $_POST)) {
			// Ask kernel to authenticate user
			$_SESSION[Main::SPARAM_DOL_KERN]->AuthenticateUser($_POST[Main::PPARAM_USER], $_POST[Main::PPARAM_HASH]);
		} else { // if params are missing show login page
			$this->login();
		}
	}

	private function displayInterface() {
		// check if params
		if(array_key_exists(Main::GPARAM_PAGE, $_GET)) {
			// display given interface
			$_SESSION[Main::SPARAM_DOL_KERN]->DisplayInterface($_GET[Main::GPARAM_PAGE]);
		} else {
			// display page not found interface
			$_SESSION[Main::SPARAM_DOL_KERN]->DisplayInterface(DoleticKernel::INTERFACE_404);
		}
	}

	private function logout() {
		// unset session vars
		$_SESSION = array();
		// destroy session
		session_destroy();
		// recall init to show login interface
		$this->initDoleticKernel();
	}

	private function loadUserInterface() {
		// load home interface
		$_SESSION[Main::SPARAM_DOL_KERN]->DisplayInterface(DoleticKernel::INTERFACE_HOME);
	}

}

// Create an instance of main and run it

$main = new Main();
$main->Run();