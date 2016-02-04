<?php

require_once "interfaces/AbstractManager.php";
require_once "loaders/DBObjectLoader.php";
require_once "services/php/UserDBObject.php";

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
		$this->user = parent::kernel()->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
						->GetResponseData(
							UserServices::GET_USER_BY_UNAME, array(
								UserServices::PARAM_UNAME => $username, 
								UserServices::PARAM_HASH => $hash));
		// return valid user check 
		return $this->HasValidUser();
	}

	public function GetCurrentUser() {
		if($this->user != null) {
			return $this->user;						// return current user
		} else {
			return new User(-1, "invalid", "", ""); // return invalid user
		}	
	}
}