<?php

require_once "interfaces/AbstractManager.php";
require_once "loaders/DBObjectLoader.php";
require_once "../services/objects/UserDBObject.php";

/**
* 	The role of this manager is to manage user and its rights
*/
class AuthenticationManager extends AbstractManager {

	// -- attributes 
	private $user;
	// -- functions
	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->user = null;
	}
	/**
	 *	Initializes this manager 
	 */
	public function Init() {
		// nothing to do here
	}
	/**
	 *	Returns true if a valid user is registered
	 */
	public function HasValidUser() {
		return ($this->user != null);
	}
	/**
	 *	Returns true if a valid user has been retrieved
	 */
	public function AuthenticateUser($username, $hash) {
		// load user into class private attribute
		$this->user = parent::kernel()->GetDBObject(DBObjectLoader::OBJ_USER)->GetServices()->GetResponseData(
					UserServices::GET_USER_BY_UNAME, array(
						UserServices::PARAM_UNAME => $username, 
						UserServices::PARAM_HASH => $hash));
		// return valid user check 
		return $this->HasValidUser();
	}
}