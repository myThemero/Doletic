<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 *	@brief The User class
 */
class User implements \JsonSerializable {
	
	// -- consts

	// -- attributes
	// --- persistent
	private $id = null;
	private $username = null;
	private $last_connection_timestamp = null;
	private $sign_up_timestamp = null;
	// --- non-persistent
	private $timestamp_update_failed = null;

	/**
	*	@brief Constructs a user
	*/
	public function __construct($id, $username, $lastConnectionTimestamp, $signUpTimestamp) {
		$this->id = intval($id);
		$this->username = $username;
		$this->last_connection_timestamp = $lastConnectionTimestamp;
		$this->sign_up_timestamp = $signUpTimestamp;
		// -- initialize fail flag down
		$this->timestamp_update_failed = false;
	}

	public function jsonSerialize() {
		return [
			UserDBObject::COL_ID => $this->id,
			UserDBObject::COL_USERNAME => $this->username,
			UserDBObject::COL_LAST_CON_TSMP => $this->last_connection_timestamp,
			UserDBObject::COL_SIGNUP_TSMP => $this->sign_up_timestamp,
			'timestamp_update_failed' => $this->timestamp_update_failed
		];
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

	public function HasTimestampUpdateFailed() {
		return $this->timestamp_update_failed;
	}

	public function RaiseTimestampUpdateFailed() {
		$this->timestamp_update_failed = true;
	}
}


class UserServices extends AbstractObjectServices {

	// -- consts
	// --- params keys
	const PARAM_ID 		= "id";
	const PARAM_UNAME 	= "username";
	const PARAM_HASH	= "hash";
	const PARAM_TOKEN	= "token";
	// --- internal services (actions)
	const GET_USER_BY_ID 	= "byid";
	const GET_USER_BY_UNAME = "byuname";
	const GET_ALL_USERS 	= "all";
	const INSERT			= "insert";
	const UPDATE			= "update";
	const UPDATE_TOKEN		= "updatetok";
	const UPDATE_PASS       = "updatepass";
	const DELETE			= "delete";
	// -- functions

	// -- construct
	public function __construct($currentUser, $dbObject, $dbConnection) {
		parent::__construct($currentUser, $dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, UserServices::GET_USER_BY_ID)) {
			$data = $this->__get_user_by_id($params[UserServices::PARAM_ID]);
		} else if(!strcmp($action, UserServices::GET_USER_BY_UNAME)) {
			$data = $this->__get_user_by_username_and_hash($params[UserServices::PARAM_UNAME], $params[UserServices::PARAM_HASH]);
		} else if(!strcmp($action, UserServices::GET_ALL_USERS)) {
			$data = $this->__get_all_users();
		} else if(!strcmp($action, UserServices::INSERT)) {
			$data = $this->__insert_user($params[UserServices::PARAM_UNAME], $params[UserServices::PARAM_HASH]);
		} else if(!strcmp($action, UserServices::UPDATE)) {
			$data = $this->__update_user_password($params[UserServices::PARAM_ID], $params[UserServices::PARAM_HASH]);
		} else if(!strcmp($action, UserServices::UPDATE_TOKEN)) {
			$data = $this->__update_token($params[UserServices::PARAM_ID]);
		} else if(!strcmp($action, UserServices::UPDATE_PASS)) {
			$data = $this->__change_pass($params[UserServices::PARAM_TOKEN]);
		} else if(!strcmp($action, UserServices::DELETE)) {
			$data = $this->__delete_user($params[UserServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	// -- consult

	private function __get_user_by_id($id) {
		// create sql params array
		$sql_params = array(":".UserDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create ticket var
		$user = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$user = new User(
					$row[UserDBObject::COL_ID], 
					$row[UserDBObject::COL_USERNAME], 
					$row[UserDBObject::COL_LAST_CON_TSMP], 
					$row[UserDBObject::COL_SIGNUP_TSMP]);
			}
		}
		return $user;
	}

	private function __get_user_by_username_and_hash($username, $hash) {
		// create sql params array
		$sql_params = array(
			":".UserDBObject::COL_USERNAME => $username,
			":".UserDBObject::COL_PASSWORD => $hash
			);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDBObject::COL_USERNAME, UserDBObject::COL_PASSWORD));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// current_timestamp
		$timestamp = date(DateTime::ISO8601);
		// create ticket var
		$user = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$user = new User(
					$row[UserDBObject::COL_ID], 
					$row[UserDBObject::COL_USERNAME], 
					$timestamp, 
					$row[UserDBObject::COL_SIGNUP_TSMP]);
			}
		}
		// check if user is valid and update last connection timestamp
		if($user != null) {
			$sql_params = array(UserDBObject::COL_ID => $user->GetId(), UserDBObject::COL_LAST_CON_TSMP => $timestamp);
			$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetUPDATEQuery(array(UserDBObject::COL_LAST_CON_TSMP));
			if(!parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
				$user->RaiseTimestampUpdateFailed();
			}
		}
		// return user
		return $user;
	}

	private function __get_all_users() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$users = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($users, new User(
					$row[UserDBObject::COL_ID], 
					$row[UserDBObject::COL_USERNAME], 
					$row[UserDBObject::COL_LAST_CON_TSMP], 
					$row[UserDBObject::COL_SIGNUP_TSMP]));
			}
		}
		return $users;
	}

	// -- modify

	private function __insert_user($username, $hash) {
		// create sql params
		$sql_params = array(
			":".UserDBObject::COL_ID => "NULL",
			":".UserDBObject::COL_USERNAME => $username,
			":".UserDBObject::COL_PASSWORD => $hash,
			":".UserDBObject::COL_LAST_CON_TSMP => date(DateTime::ISO8601),
			":".UserDBObject::COL_SIGNUP_TSMP => date(DateTime::ISO8601),
			":".UserDBObject::COL_TOKEN => "");
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	} 

	private function __update_user_password($id, $hash) {
		// create sql params
		$sql_params = array(
			":".UserDBObject::COL_ID => $id,
			":".UserDBObject::COL_PASSWORD => $hash);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetUPDATEQuery(array(UserDBObject::COL_PASSWORD));
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_token($id) {
		$token = bin2hex(openssl_random_pseudo_bytes(32));
		// create sql params
		$sql_params = array(
			":".UserDBObject::COL_ID => $id,
			":".UserDBObject::COL_TOKEN => $token);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetUPDATEQuery(array(UserDBObject::COL_TOKEN));
		// execute query
		if(!parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			$token = null;
		}
		return $token;
	}

	private function __change_pass($token) {
		$new_pass = $this->generateRandomString(16);
		// create sql params
		$sql_params = array(
			":".UserDBObject::COL_PASSWORD => sha1($new_pass),
			":".UserDBObject::COL_TOKEN => $token);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetUPDATEQuery(
				array(UserDBObject::COL_PASSWORD), array(UserDBObject::COL_TOKEN));
		// execute query
		if(!parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			$new_pass = null;
		}
		return $new_pass;
	}

	private function __delete_user($id) {
		// create sql params
		$sql_params = array(":".UserDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDBObject::TABL_USER)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

	public function ResetStaticData() {
		// no static data for this module
	}

	private function generateRandomString($length = 10) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
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
	const COL_TOKEN = "reset_token";
	// -- attributes

	// -- functions

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, UserDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_user table
		$dol_user = new DBTable(UserDBObject::TABL_USER);
		$dol_user->AddColumn(UserDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_user->AddColumn(UserDBObject::COL_USERNAME, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_PASSWORD, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_LAST_CON_TSMP, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_SIGNUP_TSMP, DBTable::DT_VARCHAR, 255, false);
		$dol_user->AddColumn(UserDBObject::COL_TOKEN, DBTable::DT_VARCHAR, 255, false);

		// -- add tables
		parent::addTable($dol_user);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices($currentUser) {
		return new UserServices($currentUser, $this, $this->getDBConnection());
	}

	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new UserServices(null, $this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}