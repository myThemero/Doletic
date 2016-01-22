<?php

require_once "interfaces/AbstractDBObject.php";
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


class UserServices {

	// -- consult

	public function GetUserById($id) {
		/// \todo implement here
	}

	public function GetUserByUsername($username, $hash) {
		/// \todo implement here
	}

	public function GetAllUsers() {
		/// \todo implement here
	}

	// -- modify

	public function InsertUser($username, $hash) {
		/// \todo implement here
	} 

	public function UpdateUserPassword($id, $hash) {
		/// \todo implement here
	}

	public function DeleteUser($id) {
		/// \todo implement here
	}

}

/**
 *	@brief User object interface
 */
class UserDBObject extends AbstractDBObject {

	// -- consts
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

	public function __construct() {
		// -- construct parent
		parent::__construct("user", new UserServices());
		// -- create tables
		// --- dol_user table
		$dol_user = new DBTable(UserDBObject::TABL_USER);
		$dol_user->AddColumn(UserDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_user->AddColumn(UserDBObject::COL_USERNAME, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_PASSWORD, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_LAST_CON_TSMP, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_SIGNUP_TSMP, DBTable::DT_VARCHAR, 255, false);

		// -- add tables
		$this->addTable($dol_user);
	}

}