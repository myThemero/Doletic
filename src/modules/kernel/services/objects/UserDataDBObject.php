<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php";
require_once "objects/RightsMap.php";

/**
 *	@brief The UserData class
 */
class UserData implements \JsonSerializable {
	
	// -- consts

	// -- attributes
	private $id = null;
	private $user_id = null;
	private $gender = null;
	private $firstname = null;
	private $lastname = null;
	private $birthdate = null;
	private $tel = null;
	private $email = null;
	private $address = null;
	private $city = null;
	private $postal_code = null;
	private $country = null;
	private $school_year = null;
	private $insa_dept = null;
	private $avatar_id = null;
	private $last_pos = null;

	/**
	*	@brief Constructs a udata
	*/
	public function __construct($id, $userId, $gender, $firstname, $lastname, $birthdate, 
								$tel, $email, $address, $city, $postalCode, $country, $schoolYear, 
								$insaDept, $avatarId, $lastPos) {
		$this->id = intval($id);
		$this->user_id = intval($userId);
		$this->gender = $gender;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->birthdate = $birthdate;
		$this->tel = $tel;
		$this->email = $email;
		$this->address = $address;
		$this->city = $city;
		$this->postal_code = intval($postalCode);
		$this->country = $country;
		$this->school_year = intval($schoolYear);
		$this->insa_dept = $insaDept;
		$this->avatar_id = intval($avatarId);
		$this->last_pos = $lastPos;
	}

	public function jsonSerialize() {
		return [
			UserDataDBObject::COL_ID => $this->id,
			UserDataDBObject::COL_USER_ID => $this->user_id,
			UserDataDBObject::COL_GENDER => $this->gender,
			UserDataDBObject::COL_FIRSTNAME => $this->firstname,
			UserDataDBObject::COL_LASTNAME => $this->lastname,
			UserDataDBObject::COL_BIRTHDATE => $this->birthdate,
			UserDataDBObject::COL_TEL => $this->tel,
			UserDataDBObject::COL_EMAIL => $this->email,
			UserDataDBObject::COL_ADDRESS => $this->address,
			UserDataDBObject::COL_CITY => $this->city,
			UserDataDBObject::COL_POSTAL_CODE => $this->postal_code,
			UserDataDBObject::COL_COUNTRY => $this->country,
			UserDataDBObject::COL_SCHOOL_YEAR => $this->school_year,
			UserDataDBObject::COL_INSA_DEPT => $this->insa_dept,
			UserDataDBObject::COL_AVATAR_ID => $this->avatar_id,
			UserDataDBObject::COL_LAST_POS => $this->last_pos //Not in DB...
		];
	}

	/**
	*	@brief Returns UserData id
	*/
	public function GetId() {
		return $this->id;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetUserId() {
		return $this->user_id;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetGender() {
		return $this->gender;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetFirstname() {
		return $this->firstname;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetLastname() {
		return $this->lastname;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetBirthdate() {
		return $this->birthdate;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetTel() {
		return $this->tel;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetEmail() {
		return $this->email;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetAddress() {
		return $this->address;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetCity() {
		return $this->city;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetPostalCode() {
		return $this->postal_code;
	}
	/**
	*	@brief Returns UserData id
	*/
	public function GetCountry() {
		return $this->country;
	}
	/**
	*	@brief Returns UserData school year
	*/
	public function GetSchoolYear() {
		return $this->school_year;
	}
	/**
	*	@brief Returns UserData INSA departement id
	*/
	public function GetINSADept() {
		return $this->insa_dept;
	}
	/**
	*	@brief Returns UserData avatar id
	*/
	public function GetAvatarId() {
		return $this->avatar_id;
	}
	/**
	*	@brief Returns UserData last position
	*/
	public function GetLastPos() {
		return $this->last_pos;
	}
}


class UserDataServices extends AbstractObjectServices {

	// -- consts
	// --- params keys
	const PARAM_ID 				= "id";
	const PARAM_USER_ID			= "userId";
	const PARAM_GENDER			= "gender";
	const PARAM_FIRSTNAME 		= "firstname";
	const PARAM_LASTNAME  		= "lastname";
	const PARAM_BIRTHDATE  		= "birthdate";
	const PARAM_TEL 			= "tel";
	const PARAM_EMAIL  			= "email";
	const PARAM_ADDRESS  		= "address";
	const PARAM_CITY			="city";
	const PARAM_POSTAL_CODE		="postalCode";
	const PARAM_COUNTRY 		= "country";
	const PARAM_SCHOOL_YEAR 	= "schoolYear";
	const PARAM_INSA_DEPT 		= "insaDept";
	const PARAM_POSITION  		= "position";
	const PARAM_AVATAR_ID  		= "avatarId";
	// --- internal services (actions)
	const GET_USER_DATA_BY_ID 	= "byidud";
	const GET_CURRENT_USER_DATA	= "currud";
	const GET_USER_LAST_POS     = "lastpos";
	const GET_USER_RG_CODE      = "rgcode";
	const GET_ALL_USER_DATA 	= "allud";
	const GET_ALL_GENDERS 		= "allg";
	const GET_ALL_COUNTRIES 	= "allc";
	const GET_ALL_INSA_DEPTS 	= "alldept";
	const GET_ALL_POSITIONS 	= "allpos";
	const CHECK_MAIL			= "checkmail";
	const INSERT				= "insert";
	const UPDATE 				= "update";
	const UPDATE_POSTION        = "updatepos";
	const UPDATE_AVATAR         = "updateava";
	const DELETE 				= "delete";
	// -- functions

	// -- construct
	public function __construct($currentUser, $dbObject, $dbConnection) {
		parent::__construct($currentUser, $dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, UserDataServices::GET_USER_DATA_BY_ID)) {
			$data = $this->__get_user_data_by_id($params[UserDataServices::PARAM_ID]);
		} else if(!strcmp($action, UserDataServices::GET_CURRENT_USER_DATA)) {
			$data = $this->__get_current_user_data();
		} else if(!strcmp($action, UserDataServices::GET_USER_LAST_POS)) {
			$data = $this->__get_user_last_position($params[UserDataServices::PARAM_USER_ID]);
		} else if(!strcmp($action, UserDataServices::GET_USER_RG_CODE)) {
			$data = $this->__get_user_rgcode();
		} else if(!strcmp($action, UserDataServices::GET_ALL_USER_DATA)) {
			$data = $this->__get_all_user_data();
		} else if(!strcmp($action, UserDataServices::GET_ALL_GENDERS)) {
			$data = $this->__get_all_genders();
		} else if(!strcmp($action, UserDataServices::GET_ALL_COUNTRIES)) {
			$data = $this->__get_all_countries();
		} else if(!strcmp($action, UserDataServices::GET_ALL_INSA_DEPTS)) {
			$data = $this->__get_all_INSA_depts();
		} else if(!strcmp($action, UserDataServices::GET_ALL_POSITIONS)) {
			$data = $this->__get_all_positions();
		} else if(!strcmp($action, UserDataServices::CHECK_MAIL)) {
			$data = $this->__check_mail($params[UserDataServices::PARAM_EMAIL]);
		} else if(!strcmp($action, UserDataServices::INSERT)) {
			$data = $this->__insert_user_data(
				$params[UserDataServices::PARAM_USER_ID],
				$params[UserDataServices::PARAM_GENDER],
				$params[UserDataServices::PARAM_FIRSTNAME],
				$params[UserDataServices::PARAM_LASTNAME], 
				$params[UserDataServices::PARAM_BIRTHDATE],
				$params[UserDataServices::PARAM_TEL], 	
				$params[UserDataServices::PARAM_EMAIL],  
				$params[UserDataServices::PARAM_ADDRESS],
				$params[UserDataServices::PARAM_CITY],
				$params[UserDataServices::PARAM_POSTAL_CODE],
				$params[UserDataServices::PARAM_COUNTRY],
				$params[UserDataServices::PARAM_SCHOOL_YEAR],
				$params[UserDataServices::PARAM_INSA_DEPT],
				$params[UserDataServices::PARAM_POSITION]);
		} else if(!strcmp($action, UserDataServices::UPDATE)) {
			$data = $this->__update_user_data(
				$params[UserDataServices::PARAM_ID],
				$params[UserDataServices::PARAM_USER_ID],
				$params[UserDataServices::PARAM_GENDER],
				$params[UserDataServices::PARAM_FIRSTNAME],
				$params[UserDataServices::PARAM_LASTNAME], 
				$params[UserDataServices::PARAM_BIRTHDATE],
				$params[UserDataServices::PARAM_TEL], 	
				$params[UserDataServices::PARAM_EMAIL],  
				$params[UserDataServices::PARAM_ADDRESS],
				$params[UserDataServices::PARAM_CITY],
				$params[UserDataServices::PARAM_POSTAL_CODE],
				$params[UserDataServices::PARAM_COUNTRY],
				$params[UserDataServices::PARAM_SCHOOL_YEAR],
				$params[UserDataServices::PARAM_INSA_DEPT],
				$params[UserDataServices::PARAM_POSITION]);
		} else if(!strcmp($action, UserDataServices::UPDATE_POSTION)) {
			$data = $this->__update_user_position(
				$params[UserDataServices::PARAM_USER_ID],
				$params[UserDataServices::PARAM_POSITION]);
		} else if(!strcmp($action, UserDataServices::UPDATE_AVATAR)) {
			$data = $this->__update_user_avatar(
				$params[UserDataServices::PARAM_AVATAR_ID]);
		} else if(!strcmp($action, UserDataServices::DELETE)) {
			$data = $this->__delete_user_data($params[UserDataServices::PARAM_ID], $params[UserDataServices::PARAM_USER_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	// -- consult

	private function __get_user_data_by_id($id) {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$udata = null;
		if(isset($pdos)) {
			if( ($row = $pdos->fetch()) !== false) {
				$udata = new UserData(
					$row[UserDataDBObject::COL_ID],
					$row[UserDataDBObject::COL_USER_ID],
					$row[UserDataDBObject::COL_GENDER],
					$row[UserDataDBObject::COL_FIRSTNAME],
					$row[UserDataDBObject::COL_LASTNAME],
					$row[UserDataDBObject::COL_BIRTHDATE],
					$row[UserDataDBObject::COL_TEL],
					$row[UserDataDBObject::COL_EMAIL],
					$row[UserDataDBObject::COL_ADDRESS],
					$row[UserDataDBObject::COL_CITY],
					$row[UserDataDBObject::COL_POSTAL_CODE],
					$row[UserDataDBObject::COL_COUNTRY],
					$row[UserDataDBObject::COL_SCHOOL_YEAR],
					$row[UserDataDBObject::COL_INSA_DEPT],
					$row[UserDataDBObject::COL_AVATAR_ID],
					$this->__get_user_last_position($row[UserDataDBObject::COL_USER_ID]));
			}
		}
		return $udata;
	}

	private function __get_current_user_data() {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_USER_ID => parent::getCurrentUser()->GetId());
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_USER_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$udata = null;
		if(isset($pdos)) {
			if( ($row = $pdos->fetch()) !== false) {
				$udata = new UserData(
					$row[UserDataDBObject::COL_ID],
					$row[UserDataDBObject::COL_USER_ID],
					$row[UserDataDBObject::COL_GENDER],
					$row[UserDataDBObject::COL_FIRSTNAME],
					$row[UserDataDBObject::COL_LASTNAME],
					$row[UserDataDBObject::COL_BIRTHDATE],
					$row[UserDataDBObject::COL_TEL],
					$row[UserDataDBObject::COL_EMAIL],
					$row[UserDataDBObject::COL_ADDRESS],
					$row[UserDataDBObject::COL_CITY],
					$row[UserDataDBObject::COL_POSTAL_CODE],
					$row[UserDataDBObject::COL_COUNTRY],
					$row[UserDataDBObject::COL_SCHOOL_YEAR],
					$row[UserDataDBObject::COL_INSA_DEPT],
					$row[UserDataDBObject::COL_AVATAR_ID],
					$this->__get_user_last_position($row[UserDataDBObject::COL_USER_ID]));
			}
		}
		return $udata;
	}

	private function __get_user_last_position($userId) {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_USER_ID => $userId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_POSITION)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_USER_ID),
			array(UserDataDBObject::COL_SINCE => DBTable::ORDER_DESC), 1);
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for udata and fill it
		$position_label = null;
		if(isset($pdos)) {
			if( ($row = $pdos->fetch()) !== false) {
				$position_label = $row[UserDataDBObject::COL_POSITION];
			}
		}
		$position = null;
		if(isset($position_label)) {
			$sql_params = array(":".UserDataDBObject::COL_LABEL => $position_label);
			$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_POSITION)->GetSELECTQuery(
						array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_LABEL));
			// execute SQL query and save result
			$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
			// create an empty array for udata and fill it
			if(isset($pdos)) {
				if( ($row = $pdos->fetch()) !== false) {
					$position = array();
					$position[UserDataDBObject::COL_LABEL] = $row[UserDataDBObject::COL_LABEL];
					$position[UserDataDBObject::COL_RG_CODE] = $row[UserDataDBObject::COL_RG_CODE];
				}
			}
		}
		return $position;
	}

	private function __get_user_rgcode() {
		$position = $this->__get_user_last_position(parent::getCurrentUser()->GetId());
		$rgcode = null;
		if(isset($position)) {
			$rgcode = $position[UserDataDBObject::COL_RG_CODE];
		}
		return $rgcode;
	}

	private function __get_all_user_data() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for udata and fill it
		$udata = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($udata, new UserData(
					$row[UserDataDBObject::COL_ID],
					$row[UserDataDBObject::COL_USER_ID],
					$row[UserDataDBObject::COL_GENDER],
					$row[UserDataDBObject::COL_FIRSTNAME],
					$row[UserDataDBObject::COL_LASTNAME],
					$row[UserDataDBObject::COL_BIRTHDATE],
					$row[UserDataDBObject::COL_TEL],
					$row[UserDataDBObject::COL_EMAIL],
					$row[UserDataDBObject::COL_ADDRESS],
					$row[UserDataDBObject::COL_CITY],
					$row[UserDataDBObject::COL_POSTAL_CODE],
					$row[UserDataDBObject::COL_COUNTRY],
					$row[UserDataDBObject::COL_SCHOOL_YEAR],
					$row[UserDataDBObject::COL_INSA_DEPT],
					$row[UserDataDBObject::COL_AVATAR_ID],
					$this->__get_user_last_position($row[UserDataDBObject::COL_USER_ID])));
			}
		}
		return $udata;
	}

	private function __get_all_genders() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_GENDER)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for labels and fill it
		$labels = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($labels, $row[UserDataDBObject::COL_LABEL]);
			}
		}
		return $labels;
	}

	private function __get_all_countries() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_COUNTRY)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for labels and fill it
		$labels = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($labels, $row[UserDataDBObject::COL_LABEL]);
			}
		}
		return $labels;
	}

	private function __get_all_INSA_depts() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_INSA_DEPT)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for labels and fill it
		$labels = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($labels, array(
					UserDataDBObject::COL_LABEL => $row[UserDataDBObject::COL_LABEL],
					UserDataDBObject::COL_DETAIL => $row[UserDataDBObject::COL_DETAIL]));
			}
		}
		return $labels;
	}

	private function __get_all_positions() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_POSITION)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for labels and fill it
		$labels = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($labels, $row[UserDataDBObject::COL_LABEL]);
			}
		}
		return $labels;
	}

	private function __check_mail($email) {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_EMAIL => $email);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_EMAIL));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$udata = null;
		if(isset($pdos)) {
			if( ($row = $pdos->fetch()) !== false) {
				$udata = new UserData(
					$row[UserDataDBObject::COL_ID],
					$row[UserDataDBObject::COL_USER_ID],
					$row[UserDataDBObject::COL_GENDER],
					$row[UserDataDBObject::COL_FIRSTNAME],
					$row[UserDataDBObject::COL_LASTNAME],
					$row[UserDataDBObject::COL_BIRTHDATE],
					$row[UserDataDBObject::COL_TEL],
					$row[UserDataDBObject::COL_EMAIL],
					$row[UserDataDBObject::COL_ADDRESS],
					$row[UserDataDBObject::COL_CITY],
					$row[UserDataDBObject::COL_POSTAL_CODE],
					$row[UserDataDBObject::COL_COUNTRY],
					$row[UserDataDBObject::COL_SCHOOL_YEAR],
					$row[UserDataDBObject::COL_INSA_DEPT],
					$row[UserDataDBObject::COL_AVATAR_ID],
					$this->__get_user_last_position($row[UserDataDBObject::COL_USER_ID]));
			}
		}
		return $udata;
	}

	// -- modify

	private function __insert_user_data($userId, $gender, $firstname, $lastname, $birthdate, 
									$tel, $email, $address, $city, $postalCode, $country, $schoolYear, 
									$insaDept, $position) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_ID => "NULL",
			":".UserDataDBObject::COL_USER_ID => $userId,
			":".UserDataDBObject::COL_GENDER => $gender,
			":".UserDataDBObject::COL_FIRSTNAME => $firstname,
			":".UserDataDBObject::COL_LASTNAME => $lastname,
			":".UserDataDBObject::COL_BIRTHDATE => $birthdate,
			":".UserDataDBObject::COL_TEL => $tel,
			":".UserDataDBObject::COL_EMAIL => $email,
			":".UserDataDBObject::COL_ADDRESS => $address,
			":".UserDataDBObject::COL_CITY => $city,
			":".UserDataDBObject::COL_POSTAL_CODE => $postalCode,
			":".UserDataDBObject::COL_COUNTRY => $country,
			":".UserDataDBObject::COL_SCHOOL_YEAR => $schoolYear,
			":".UserDataDBObject::COL_INSA_DEPT => $insaDept,
			":".UserDataDBObject::COL_AVATAR_ID => "NULL");
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetINSERTQuery();
		// execute query
		if (parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			return $this->__update_user_position($userId, $position);
		} else {
			return FALSE;
		}
	} 

	private function __update_user_data($id, $userId, $gender, $firstname, $lastname, $birthdate, 
									$tel, $email, $address, $city, $postalCode, $country, $schoolYear, 
									$insaDept, $position) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_ID => $id,
			":".UserDataDBObject::COL_USER_ID => $userId,
			":".UserDataDBObject::COL_GENDER => $gender,
			":".UserDataDBObject::COL_FIRSTNAME => $firstname,
			":".UserDataDBObject::COL_LASTNAME => $lastname,
			":".UserDataDBObject::COL_BIRTHDATE => $birthdate,
			":".UserDataDBObject::COL_TEL => $tel,
			":".UserDataDBObject::COL_EMAIL => $email,
			":".UserDataDBObject::COL_ADDRESS => $address,
			":".UserDataDBObject::COL_CITY => $city,
			":".UserDataDBObject::COL_POSTAL_CODE => $postalCode,
			":".UserDataDBObject::COL_COUNTRY => $country,
			":".UserDataDBObject::COL_SCHOOL_YEAR => $schoolYear,
			":".UserDataDBObject::COL_INSA_DEPT => $insaDept,
			":".UserDataDBObject::COL_AVATAR_ID => "NULL");
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetUPDATEQuery();
		// execute query
		if (parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			return $this->__update_user_position($userId, $position);
		} else {
			return FALSE;
		}
	}

	private function __update_user_position($userId, $position) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_ID => "NULL",
			":".UserDataDBObject::COL_USER_ID => $userId,
			":".UserDataDBObject::COL_POSITION => $position,
			":".UserDataDBObject::COL_SINCE => date('Y-m-d H:i:s'));
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_POSITION)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_user_avatar($avatarId) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_USER_ID => parent::getCurrentUser()->GetId(),
			":".UserDataDBObject::COL_AVATAR_ID => $avatarId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetUPDATEQuery(
			array(UserDataDBObject::COL_AVATAR_ID), array(UserDataDBObject::COL_USER_ID));
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __delete_user_data($id, $userId) {
		// DELETE POSITIONS HISTORY FIRST
		// create sql params
		$sql_params = array(":".UserDataDBObject::COL_USER_ID => $userId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_POSITION)->GetDELETEQuery(array(UserDataDBObject::COL_USER_ID));
		// execute query
		if(parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			// create sql params
			$sql_params_bis = array(":".UserDataDBObject::COL_ID => $id);
			// create sql request
			$sql_bis= parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetDELETEQuery();
			// execute query
			return parent::getDBConnection()->PrepareExecuteQuery($sql_bis, $sql_params_bis);
		}
		return FALSE;
	}

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------
	
	/**
	 *	---------!---------!---------!---------!---------!---------!---------!---------!---------
	 *  !  							DATABASE CONSISTENCY WARNING 							    !
	 *  !  																					    !
	 *  !  Please respect the following points :   												!
	 *	!  - When adding static data to existing data => always add at the end of the list      !
	 *  !  - Never remove data (or ensure that no database element use one as a foreign key)    !
	 *	---------!---------!---------!---------!---------!---------!---------!---------!---------
	 */
	public function ResetStaticData() {
		// -- init gender table --------------------------------------------------------------------
		$genders = array("M.","Mlle","Mme");
		// --- retrieve SQL query
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_GENDER)->GetINSERTQuery();
		foreach ($genders as $gender) {
			// --- create param array
			$sql_params = array(":".UserDataDBObject::COL_LABEL => $gender);
			// --- execute SQL query
			parent::getDBConnection()->PrepareExecuteQuery($sql,$sql_params);
		}
		// -- init countries table --------------------------------------------------------------------
		$countries = array("Afrique du Sud","Algérie",
		"Angola","Bénin","Botswana","Burkina Faso","Burundi","Cameroun","Cap-Vert","République centrafricaine","Comores",
		"République du Congo","République démocratique du Congo","Côte d'Ivoire","Djibouti","Égypte","Érythrée","Éthiopie",
		"Gabon","Gambie","Ghana","Guinée","Guinée-Bissau","Guinée équatoriale","Kenya","Lesotho","Liberia","Libye",
		"Madagascar","Malawi","Mali","Maroc","Maurice","Mauritanie","Mozambique","Namibie","Niger","Nigeria","Ouganda",
		"Rwanda","Sahara Occidental","Sao Tomé-et-Principe","Sénégal","Seychelles","Sierra Leone","Somalie","Soudan",
		"Soudan du Sud","Swaziland","Tanzanie","Tchad","Togo","Tunisie","Zambie","Zimbabwe","Antigua-et-Barbuda",
		"Argentine","Bahamas","Barbade","Belize","Bolivie","Brésil","Canada","Chili","Colombie","Costa Rica",
		"Cuba","République dominicaine","Dominique","Équateur","États-Unis","Grenade","Guatemala","Guyana",
		"Haïti","Honduras","Jamaïque","Mexique","Nicaragua","Panama","Paraguay","Pérou","Saint-Christophe-et-Niévès",
		"Sainte-Lucie","Saint-Vincent-et-les-Grenadines","Salvador","Suriname","Trinité-et-Tobago",
		"Uruguay","Venezuela","Afghanistan","Arabie saoudite","Arménie","Azerbaïdjan","Bahreïn","Bangladesh",
		"Bhoutan","Birmanie","Brunei","Cambodge","Chine","Chypre","Corée du Nord","Corée du Sud","Émirats arabes unis",
		"Géorgie","Inde","Indonésie","Irak","Iran","Israël","Japon","Jordanie","Kazakhstan","Kirghizistan",
		"Koweït","Laos","Liban","Malaisie","Maldives","Mongolie","Népal","Oman","Ouzbékistan","Pakistan",
		"Philippines","Qatar","Singapour","Sri Lanka","Syrie","Tadjikistan","Thaïlande","Timor oriental",
		"Turkménistan","Turquie","Viêt Nam","Yémen","Albanie","Allemagne","Andorre","Autriche","Belgique",
		"Biélorussie","Bosnie-Herzégovine","Bulgarie","Chypre","Crete","Croatie","Danemark","Espagne","Estonie",
		"Finlande","France","Grèce","Hongrie","Irlande","Islande","Italie","Lettonie","Liechtenstein","Lituanie",
		"Luxembourg","Macédoine","Malte","Moldavie","Monaco","Monténégro","Norvège","Pays-Bas","Pologne","Portugal",
		"République tchèque","Roumanie","Royaume-Uni","Russie","Saint-Marin","Serbie","Slovaquie","Slovénie",
		"Suède","Suisse","Ukraine","Vatican","Australie","Fidji","Kiribati","Marshall","Micronésie","Nauru",
		"Nouvelle-Zélande","Palaos","Papouasie-Nouvelle-Guinée","Salomon","Samoa","Tonga","Tuvalu","Vanuatu");
		// --- retrieve SQL query
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_COUNTRY)->GetINSERTQuery();
		foreach ($countries as $country) {
			// --- create param array
			$sql_params = array(":".UserDataDBObject::COL_LABEL => $country);
			// --- execute SQL query
			parent::getDBConnection()->PrepareExecuteQuery($sql,$sql_params);
		}
		// -- init INSA Dept table --------------------------------------------------------------------
		$depts = array(
			"BB" => "Biochimie et Biotechnologies",
		    "BIM" => "BioInformatique et Modélisation",
		    "GCU" => "Génie civil et urbanisme",
		    "GE" => "Génie électrique",
		    "GEN" => "Génie énergétique et environnement",
		    "GMC" => "Génie mécanique conception",
		    "GMD" => "Génie mécanique développement",
		    "GMPP" => "Génie mécanique procédés plasturgie",
		    "GI" => "Génie Industriel",
		    "IF" => "Informatique",
		    "SGM" => "Science et Génie des Matériaux",
		    "TC" => "Télécommunications, Services et Usages");
		// --- retrieve SQL query
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_INSA_DEPT)->GetINSERTQuery();
		foreach ($depts as $dept => $detail) {
			// --- create param array
			$sql_params = array(":".UserDataDBObject::COL_LABEL => $dept,
								":".UserDataDBObject::COL_DETAIL => $detail);
			// --- execute SQL query
			parent::getDBConnection()->PrepareExecuteQuery($sql,$sql_params);
		}
		// -- init ETIC pos table --------------------------------------------------------------------
		$positions = array(//definition: RightsMap::x_R  | RightsMap::x_G (| RightsMap::x_G)*
			"Président" => 				(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
			"Vide-Président" => 		(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
			"Secrétaire Général" => 	(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
			"Trésorier" => 				(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
			"Comptable" => 				(RightsMap::A_R  | RightsMap::M_G | RightsMap::D_G), // A  | M | D
		    "Responsable DSI" => 		(RightsMap::SA_R | RightsMap::A_G | RightsMap::D_G), // SA | A | D
		    "Responsable GRC" => 		(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
		    "Responsable COM" => 		(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
		    "Responsable UA" => 		(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
		    "Responsable Qualité" => 	(RightsMap::A_R  | RightsMap::A_G | RightsMap::D_G), // A  | A | D
		    "Junior DSI" => 			(RightsMap::U_R  | RightsMap::M_G | RightsMap::D_G), // U  | M | D
		    "Junior GRC" => 			(RightsMap::U_R  | RightsMap::M_G | RightsMap::D_G), // U  | M | D
		    "Junior COM" => 			(RightsMap::U_R  | RightsMap::M_G | RightsMap::D_G), // U  | M | D
		    "Chargé d'affaire" => 		(RightsMap::U_R  | RightsMap::M_G | RightsMap::D_G), // U  | M | D
		    "Junior Qualité" => 		(RightsMap::U_R  | RightsMap::M_G | RightsMap::D_G), // U  | M | D
		    "Intervenant" => 			(RightsMap::G_R  | RightsMap::I_G | RightsMap::D_G), // G  | I | D
		    "Client" => 				(RightsMap::G_R  | RightsMap::C_G | RightsMap::D_G)  // G  | C | D
		    );
		// --- retrieve SQL query
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_POSITION)->GetINSERTQuery();
		foreach ($positions as $position => $rgcode) {
			// --- create param array
			$sql_params = array(":".UserDataDBObject::COL_LABEL => $position,
								":".UserDataDBObject::COL_RG_CODE => $rgcode);
			// --- execute SQL query
			parent::getDBConnection()->PrepareExecuteQuery($sql,$sql_params);
		}
	}

}

/**
 *	@brief UserData object interface
 */
class UserDataDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "udata";
	// --- tables
	const TABL_USER_DATA = "dol_udata";
	const TABL_USER_POSITION = "dol_udata_position";
	const TABL_COM_GENDER = "com_gender";
	const TABL_COM_COUNTRY = "com_country";
	const TABL_COM_INSA_DEPT = "com_insa_dept";
	const TABL_COM_POSITION	= "com_position";
	// --- columns
	const COL_ID 			= "id";
	const COL_USER_ID  		= "user_id";
	const COL_GENDER 	= "gender";
	const COL_FIRSTNAME 	= "firstname";
	const COL_LASTNAME  	= "lastname";
	const COL_BIRTHDATE  	= "birthdate";
	const COL_TEL 			= "tel";
	const COL_EMAIL  		= "email";
	const COL_ADDRESS  		= "address";
	const COL_CITY 			= "city";
	const COL_POSTAL_CODE	= "postal_code";
	const COL_COUNTRY 	= "country";
	const COL_SCHOOL_YEAR 	= "school_year";
	const COL_INSA_DEPT 	= "insa_dept";
	const COL_POSITION  	= "position";
	const COL_LABEL			= "label";
	const COL_DETAIL		= "detail";
	const COL_SINCE			= "since";
	const COL_RG_CODE   	= "rg_code";
	const COL_AVATAR_ID   	= "avatar_id";
	const COL_LAST_POS		="last_pos";
	// -- attributes

	// -- functions

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, UserDataDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_udata table
		$dol_udata = new DBTable(UserDataDBObject::TABL_USER_DATA);
		$dol_udata->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_udata->AddColumn(UserDataDBObject::COL_USER_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_GENDER, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_FIRSTNAME, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_LASTNAME, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_BIRTHDATE, DBTable::DT_DATE, -1, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_TEL, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_EMAIL, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_ADDRESS, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_CITY, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_POSTAL_CODE, DBTable::DT_INT, 11, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_COUNTRY, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_SCHOOL_YEAR, DBTable::DT_INT, 11, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_INSA_DEPT, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_AVATAR_ID, DBTable::DT_INT, 11, false, "-1");
		// --- dol_udata_position table
		$dol_udata_position = new DBTable(UserDataDBObject::TABL_USER_POSITION);
		$dol_udata_position->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_udata_position->AddColumn(UserDataDBObject::COL_USER_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata_position->AddColumn(UserDataDBObject::COL_POSITION, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata_position->AddColumn(UserDataDBObject::COL_SINCE, DBTable::DT_DATETIME, -1, false, "");
		// --- com_gender table
		$com_gender = new DBTable(UserDataDBObject::TABL_COM_GENDER);
		$com_gender->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true);
		// --- com_country table
		$com_country = new DBTable(UserDataDBObject::TABL_COM_COUNTRY);
		$com_country->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true);
		// --- com_insa_dept table
		$com_insa_dept = new DBTable(UserDataDBObject::TABL_COM_INSA_DEPT);
		$com_insa_dept->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true);
		$com_insa_dept->AddColumn(UserDataDBObject::COL_DETAIL, DBTable::DT_VARCHAR, 255, false, "");
		// --- com_position table
		$com_position = new DBTable(UserDataDBObject::TABL_COM_POSITION);
		$com_position->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true);
		$com_position->AddColumn(UserDataDBObject::COL_RG_CODE, DBTable::DT_INT, 11, false, "");

		// -- add tables
		parent::addTable($dol_udata);
		parent::addTable($dol_udata_position);
		parent::addTable($com_gender);
		parent::addTable($com_country);
		parent::addTable($com_insa_dept);
		parent::addTable($com_position);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices($currentUser) {
		return new UserDataServices($currentUser, $this, $this->getDBConnection());
	}

	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new UserDataServices(null, $this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}