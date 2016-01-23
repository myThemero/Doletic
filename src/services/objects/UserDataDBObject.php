<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 *	@brief The UserData class
 */
class UserData implements \JsonSerializable {
	
	// -- consts

	// -- attributes
	private $id;
	private $user_id;
	private $gender_id;
	private $firstname;
	private $lastname;
	private $birthdate;
	private $tel;
	private $email;
	private $address;
	private $country_id;
	private $school_year;
	private $insa_dept_id;

	/**
	*	@brief Constructs a udata
	*/
	public function __construct($id, $userId, $genderId, $firstname, $lastname, $birthdate, 
								$tel, $email, $address, $countryId, $schoolYear, 
								$insaDeptId) {
		$this->id = $id;
		$this->user_id = $userId;
		$this->gender_id = $genderId;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->birthdate = $birthdate;
		$this->tel = $tel;
		$this->email = $email;
		$this->address = $address;
		$this->country_id = $countryId;
		$this->school_year = $schoolYear;
		$this->insa_dept_id = $insaDeptId;
	}

	public function jsonSerialize() {
		return [
			UserDataDBObject::COL_ID => $this->id,
			UserDataDBObject::COL_USER_ID => $this->user_id,
			UserDataDBObject::COL_GENDER_ID => $this->gender_id,
			UserDataDBObject::COL_FIRSTNAME => $this->firstname,
			UserDataDBObject::COL_LASTNAME => $this->lastname,
			UserDataDBObject::COL_BIRTHDATE => $this->birthdate,
			UserDataDBObject::COL_TEL => $this->tel,
			UserDataDBObject::COL_EMAIL => $this->email,
			UserDataDBObject::COL_ADDRESS => $this->address,
			UserDataDBObject::COL_COUNTRY_ID => $this->country_id,
			UserDataDBObject::COL_SCHOOL_YEAR => $this->school_year,
			UserDataDBObject::COL_INSA_DEPT_ID => $this->insa_dept_id
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
	public function GetGenderId() {
		return $this->gender_id;
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
	public function GetCountryId() {
		return $this->country_id;
		}
	/**
	*	@brief Returns UserData id
	*/
	public function GetSchoolYear() {
		return $this->school_year;
		}
	/**
	*	@brief Returns UserData id
	*/
	public function GetINSADeptId() {
		return $this->insa_dept_id;
		}
}


class UserDataServices extends AbstractObjectServices {

	// -- consts
	// --- params keys
	const PARAM_ID 				= "id";
	const PARAM_USER_ID			= "userId";
	const PARAM_GENDER_ID		= "genderId";
	const PARAM_FIRSTNAME 		= "firstname";
	const PARAM_LASTNAME  		= "lastname";
	const PARAM_BIRTHDATE  		= "birthdate";
	const PARAM_TEL 			= "tel";
	const PARAM_EMAIL  			= "email";
	const PARAM_ADDRESS  		= "address";
	const PARAM_COUNTRY_ID 		= "countryId";
	const PARAM_SCHOOL_YEAR 	= "schoolYear";
	const PARAM_INSA_DEPT_ID 	= "insaDeptId";
	const PARAM_POSITION_ID  	= "positionId";
	// --- internal services (actions)
	const GET_USER_DATA_BY_ID 	= "byid";
	const GET_ALL_USER_DATA 	= "all";
	const INSERT_USER_DATA		= "insert";
	const UPDATE_USER_DATA		= "update";
	const DELETE_USER_DATA		= "delete";
	// -- functions

	// -- construct
	public function __construct($dbObject, $dbConnection) {
		parent::__construct($dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, UserDataServices::GET_USER_DATA_BY_ID)) {
			$data = $this->getUserDataById($params[UserDataServices::PARAM_ID]);
		} else if(!strcmp($action, UserDataServices::GET_ALL_USER_DATA)) {
			$data = $this->getAllUserData();
		} else if(!strcmp($action, UserDataServices::INSERT_USER_DATA)) {
			$data = $this->insertUserData(
				$params[UserDataServices::PARAM_USER_ID],
				$params[UserDataServices::PARAM_GENDER_ID],
				$params[UserDataServices::PARAM_FIRSTNAME],
				$params[UserDataServices::PARAM_LASTNAME], 
				$params[UserDataServices::PARAM_BIRTHDATE],
				$params[UserDataServices::PARAM_TEL], 	
				$params[UserDataServices::PARAM_EMAIL],  
				$params[UserDataServices::PARAM_ADDRESS],
				$params[UserDataServices::PARAM_COUNTRY_ID],
				$params[UserDataServices::PARAM_SCHOOL_YEAR],
				$params[UserDataServices::PARAM_INSA_DEPT_ID]);
		} else if(!strcmp($action, UserDataServices::UPDATE_USER_DATA)) {
			$data = $this->updateUserData(
				$params[UserDataServices::PARAM_ID],
				$params[UserDataServices::PARAM_USER_ID],
				$params[UserDataServices::PARAM_GENDER_ID],
				$params[UserDataServices::PARAM_FIRSTNAME],
				$params[UserDataServices::PARAM_LASTNAME], 
				$params[UserDataServices::PARAM_BIRTHDATE],
				$params[UserDataServices::PARAM_TEL], 	
				$params[UserDataServices::PARAM_EMAIL],  
				$params[UserDataServices::PARAM_ADDRESS],
				$params[UserDataServices::PARAM_COUNTRY_ID],
				$params[UserDataServices::PARAM_SCHOOL_YEAR],
				$params[UserDataServices::PARAM_INSA_DEPT_ID]);
		} else if(!strcmp($action, UserDataServices::DELETE_USER_DATA)) {
			$data = $this->deleteUserData($params[UserDataServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	// -- consult

	private function getUserDataById($id) {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$udata = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$udata = new UserData(
					$row[UserDataDBObject::COL_ID],
					$row[UserDataDBObject::COL_USER_ID],
					$row[UserDataDBObject::COL_GENDER_ID],
					$row[UserDataDBObject::COL_FIRSTNAME],
					$row[UserDataDBObject::COL_LASTNAME],
					$row[UserDataDBObject::COL_BIRTHDATE],
					$row[UserDataDBObject::COL_TEL],
					$row[UserDataDBObject::COL_EMAIL],
					$row[UserDataDBObject::COL_ADDRESS],
					$row[UserDataDBObject::COL_COUNTRY_ID],
					$row[UserDataDBObject::COL_SCHOOL_YEAR],
					$row[UserDataDBObject::COL_INSA_DEPT_ID]);
			}
		}
		return $udata;
	}

	private function getUserLastPosition($userId) {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_POSITION)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID),
			array(UserDataDBObject::COL_SINCE => DBTable::ORDER_DESC), 1);
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for udata and fill it
		$position = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$position = $row[UserDataDBObject::COL_POSITION_ID];
			}
		}
		return $position;
	}

	private function getGenderById() {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_GENDER)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$gender = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$gender = $row[UserDataDBObject::COL_LABEL];
			}
		}
		return $gender;
	}

	private function getCountryById() {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_COUNTRY)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$country = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$country = $row[UserDataDBObject::COL_LABEL];
			}
		}
		return $country;
	}

	private function getINSADeptById() {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_INSA_DEPT)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$insa_dept = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$insa_dept = $row[UserDataDBObject::COL_LABEL];
			}
		}
		return $insa_dept;
	}

	private function getPositionById() {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_COM_POSITION)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create udata var
		$position = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$position = $row[UserDataDBObject::COL_LABEL];
			}
		}
		return $position;
	}

	private function getAllUserData() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for udata and fill it
		$udata = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($udata, new UserData(
					$row[UserDataDBObject::COL_ID],
					$row[UserDataDBObject::COL_USER_ID],
					$row[UserDataDBObject::COL_GENDER_ID],
					$row[UserDataDBObject::COL_FIRSTNAME],
					$row[UserDataDBObject::COL_LASTNAME],
					$row[UserDataDBObject::COL_BIRTHDATE],
					$row[UserDataDBObject::COL_TEL],
					$row[UserDataDBObject::COL_EMAIL],
					$row[UserDataDBObject::COL_ADDRESS],
					$row[UserDataDBObject::COL_COUNTRY_ID],
					$row[UserDataDBObject::COL_SCHOOL_YEAR],
					$row[UserDataDBObject::COL_INSA_DEPT_ID]));
			}
		}
		return $udata;
	}

	// -- modify

	private function insertUserData($userId, $genderId, $firstname, $lastname, $birthdate, 
									$tel, $email, $address, $countryId, $schoolYear, 
									$insaDeptId, $positionId) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_ID => "NULL",
			":".UserDataDBObject::COL_USER_ID => $userId,
			":".UserDataDBObject::COL_GENDER_ID => $genderId,
			":".UserDataDBObject::COL_FIRSTNAME => $firstname,
			":".UserDataDBObject::COL_LASTNAME => $lastname,
			":".UserDataDBObject::COL_BIRTHDATE => $birthdate,
			":".UserDataDBObject::COL_TEL => $tel,
			":".UserDataDBObject::COL_EMAIL => $email,
			":".UserDataDBObject::COL_ADDRESS => $address,
			":".UserDataDBObject::COL_COUNTRY_ID => $countryId,
			":".UserDataDBObject::COL_SCHOOL_YEAR => $schoolYear,
			":".UserDataDBObject::COL_INSA_DEPT_ID => $insaDeptId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	} 

	private function updateUserData($id, $userId, $genderId, $firstname, $lastname, $birthdate, 
									$tel, $email, $address, $countryId, $schoolYear, 
									$insaDeptId, $positionId) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_ID => $id,
			":".UserDataDBObject::COL_USER_ID => $userId,
			":".UserDataDBObject::COL_GENDER_ID => $genderId,
			":".UserDataDBObject::COL_FIRSTNAME => $firstname,
			":".UserDataDBObject::COL_LASTNAME => $lastname,
			":".UserDataDBObject::COL_BIRTHDATE => $birthdate,
			":".UserDataDBObject::COL_TEL => $tel,
			":".UserDataDBObject::COL_EMAIL => $email,
			":".UserDataDBObject::COL_ADDRESS => $address,
			":".UserDataDBObject::COL_COUNTRY_ID => $countryId,
			":".UserDataDBObject::COL_SCHOOL_YEAR => $schoolYear,
			":".UserDataDBObject::COL_INSA_DEPT_ID => $insaDeptId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function updateUserPosition($userId, $positionId) {
		// create sql params
		$sql_params = array(
			":".UserDataDBObject::COL_ID => "NULL",
			":".UserDataDBObject::COL_USER_ID => $userId,
			":".UserDataDBObject::COL_POSITION_ID => $positionId,
			":".UserDataDBObject::COL_SINCE => date('Y-m-d'));
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_POSITION)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function deleteUserData($id) {
		// create sql params
		$sql_params = array(":".UserDataDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UserDataDBObject::TABL_USER_DATA)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
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
	const COL_GENDER_ID 	= "gender_id";
	const COL_FIRSTNAME 	= "firstname";
	const COL_LASTNAME  	= "lastname";
	const COL_BIRTHDATE  	= "birthdate";
	const COL_TEL 			= "tel";
	const COL_EMAIL  		= "email";
	const COL_ADDRESS  		= "address";
	const COL_COUNTRY_ID 	= "country_id";
	const COL_SCHOOL_YEAR 	= "school_year";
	const COL_INSA_DEPT_ID 	= "insa_dept_id";
	const COL_POSITION_ID  	= "position_id";
	const COL_LABEL			= "label";
	const COL_SINCE			= "since";
	// -- attributes

	// -- functions

	public function __construct(&$dbConnection) {
		// -- construct parent
		parent::__construct($dbConnection, "udata");
		// -- create tables
		// --- dol_udata table
		$dol_udata = new DBTable(UserDataDBObject::TABL_USER_DATA);
		$dol_udata->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_udata->AddColumn(UserDataDBObject::COL_USER_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_GENDER_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_FIRSTNAME, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_LASTNAME, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_BIRTHDATE, DBTable::DT_DATE, -1, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_TEL, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_EMAIL, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_ADDRESS, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_COUNTRY_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_SCHOOL_YEAR, DBTable::DT_VARCHAR, 255, false, "");
		$dol_udata->AddColumn(UserDataDBObject::COL_INSA_DEPT_ID, DBTable::DT_INT, 11, false, "");
		// --- dol_udata_position table
		$dol_udata_position = new DBTable(UserDataDBObject::TABL_USER_POSITION);
		$dol_udata_position->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_udata_position->AddColumn(UserDataDBObject::COL_USER_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata_position->AddColumn(UserDataDBObject::COL_POSITION_ID, DBTable::DT_INT, 11, false, "");
		$dol_udata_position->AddColumn(UserDataDBObject::COL_SINCE, DBTable::DT_DATE, -1, false, "");
		// --- com_gender table
		$com_gender = new DBTable(UserDataDBObject::TABL_COM_GENDER);
		$com_gender->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$com_gender->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "");
		// --- com_country table
		$com_country = new DBTable(UserDataDBObject::TABL_COM_COUNTRY);
		$com_country->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$com_country->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "");
		// --- com_insa_dept table
		$com_insa_dept = new DBTable(UserDataDBObject::TABL_COM_INSA_DEPT);
		$com_insa_dept->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$com_insa_dept->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "");
		// --- com_position table
		$com_position = new DBTable(UserDataDBObject::TABL_COM_POSITION);
		$com_position->AddColumn(UserDataDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$com_position->AddColumn(UserDataDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "");

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
	public function GetServices() {
		return new UserDataServices($this, $this->getDBConnection());
	}

}