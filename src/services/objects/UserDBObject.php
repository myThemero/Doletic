<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 *	@brief The User class
 */
class User {
	
	// -- consts

	// -- attributes
	private $id;
	private $username;
	private $last_connection_timestamp;
	private $sign_up_timestamp;

	/**
	*	@brief Constructs a user
	*/
	public function __construct($id, $username, $last_connection_timestamp, $sign_up_timestamp) {
		$this->id = $id;
		$this->username = $username;
		$this->last_connection_timestamp = $last_connection_timestamp;
		$this->sign_up_timestamp = $sign_up_timestamp;
	}

	/**
	*	@brief Returns user firstname and name concatenated
	*	@return string
	*/
	public function GetId() {
		return $this->id;
	}
	/**
	*	@brief Returns user firstname and name concatenated
	*	@return string
	*/
	public function GetUsername() {
		return $this->username;
	}
	public function SetUsername($username) {
		$this->username = $username;
	}
	/**
	*	@brief Returns birthdate formatted : yyyy-mm-dd
	*	@return string
	*/
	public function GetLastConnectionTimestamp() {
		return $this->last_connection_timestamp;
	}
	/**
	*	@brief Returns inscription date
	*	@return string
	*/
	public function GetSignUpTimestamp() {
		return $this->sign_up_timestamp;
	}

}


class UserServices extends AbstractObjectServices {

	// -- consts
	// --- params keys
	const PARAM_ID 		= "id";
	const PARAM_UNAME 	= "username";
	const PARAM_HASH	= "hash";
	// --- internal services (actions)
	const GET_USER_BY_ID 	= "byid";
	const GET_USER_BY_UNAME = "byuname";
	const GET_ALL_USERS 	= "all";
	const INSERT_USER		= "insert";
	const UPDATE_USER		= "update";
	const DELETE_USER		= "delete";
	// -- functions

	// -- construct
	public function __construct(&$dbConnection) {
		parent::__construct($dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, UserServices::GET_USER_BY_ID)) {
			$data = $this->getUserById($params[UserServices::PARAM_ID]);
		} else if(!strcmp($action, UserServices::GET_USER_BY_UNAME)) {
			$data = $this->getUserByUsername($params[UserServices::PARAM_UNAME], $params[UserServices::PARAM_HASH]);
		} else if(!strcmp($action, UserServices::GET_ALL_USERS)) {
			$data = $this->getAllUsers();
		} else if(!strcmp($action, UserServices::INSERT_USER)) {
			$data = $this->insertUser($params[UserServices::PARAM_ID]);
		} else if(!strcmp($action, UserServices::UPDATE_USER)) {
			$data = $this->updateUserPassword($params[UserServices::PARAM_ID], $params[UserServices::PARAM_HASH]);
		} else if(!strcmp($action, UserServices::DELETE_USER)) {
			$data = $this->deleteUser($params[UserServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	// -- consult

	private function getUserById($id) {
		/// \todo implement here
	}

	private function getUserByUsername($username, $hash) {
		/// \todo implement here
	}

	private function getAllUsers() {
		/// \todo implement here
	}

	// -- modify

	private function insertUser($username, $hash) {
		/// \todo implement here
	} 

	private function updateUserPassword($id, $hash) {
		/// \todo implement here
	}

	private function deleteUser($id) {
		/// \todo implement here
	}

}

/**
 *	@brief User object interface
 */
class UserDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "user";
	// --- tables
	const TABL_USER = "dol_user";
	// --- columns
	const COL_ID = "id";
	const COL_USERNAME = "username";
	const COL_PASSWORD = "password";
	const COL_LAST_CON_TSMP = "last_connect_timestamp";
	const COL_SIGNUP_TSMP = "sign_up_timestamp";
	// -- attributes

	// -- functions

	public function __construct(&$dbConnection) {
		// -- construct parent
		parent::__construct($dbConnection, "user");
		// -- create tables
		// --- dol_user table
		$dol_user = new DBTable(UserDBObject::TABL_USER);
		$dol_user->AddColumn(UserDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_user->AddColumn(UserDBObject::COL_USERNAME, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_PASSWORD, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_LAST_CON_TSMP, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_SIGNUP_TSMP, DBTable::DT_VARCHAR, 255, false);

		// -- add tables
		parent::addTable($dol_user);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices() {
		return new UserServices($this, $this->getDBConnection());
	}

}