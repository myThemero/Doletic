<?php

require_once "interfaces/AbstractManager.php";
require_once "loaders/DBObjectLoader.php";

/**
* 	The role of this manager is to manage user and its rights
*/
class AuthenticationManager extends AbstractManager {

	// -- attributes 
	private $user = null;
	private $rgcode = null;

	// -- functions
	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->user = null;
		$this->rgcode = null;
	}
	/**
	 *	Initializes this manager 
	 */
	public function Init() {
		// nothing to init
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
		if($this->HasValidUser())
		{	// retrieve RG code if user is valid
			$this->rgcode = parent::kernel()->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
						->GetResponseData(
							UserDataServices::GET_USER_RG_CODE, array());	
		}
		// return valid user check 
		return $this->HasValidUser();
	}

	public function ResetPasswordExec($token) {
		$ok = false;
		$new_pass = parent::kernel()->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
						->GetResponseData(UserServices::UPDATE_PASS, array(
							UserServices::PARAM_TOKEN => $token
							));
		// if token has been created
		if($new_pass != null) {
			/// \todo send new_pass using user mail
			var_dump($new_pass); // DEBUG
			$ok = true;
		}
		return $ok;
	}

	public function ResetPasswordInit($mail) {
		$ok = false;
		// retrieve mail using service
		$udata = parent::kernel()->GetDBObject(UserDataDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
						->GetResponseData(UserDataServices::CHECK_MAIL, array(
							UserDataServices::PARAM_EMAIL => $mail
							));
		// if mail exists 
		if($udata != null) {
			// create token using service
			$token = parent::kernel()->GetDBObject(UserDBObject::OBJ_NAME)->GetServices($this->GetCurrentUser())
						->GetResponseData(UserServices::UPDATE_TOKEN, array(
							UserServices::PARAM_ID => $udata->GetUserId()
							));
			// if token has been created
			if($token != null) {
				/// \todo send token using user mail
				var_dump($token); // DEBUG
				$ok = true;
			}	
		}
		return $ok;
	}

	public function GetCurrentUser() {
		if($this->user != null) {
			return $this->user;						// return current user
		} else {
			return new User(-1, "invalid", "", ""); // return invalid user
		}	
	}

	public function GetCurrentUserRGCode() {
		if($this->rgcode != null) {
			return $this->rgcode;						// return current user
		} else {
			return (RightsMap::G_R | RightsMap::D_G);  	// return guest and default group
		}	
		
	}
}