<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 * @brief AdmMembership object
 */
class AdmMembership implements \JsonSerializable {
	
	// -- consts
	const AUTO_ID = -1;

	// -- attributes
	private $id;
	private $user_id;
	private $start_date;
	private $end_date;
	private $fee;
	private $form;
	private $certif;
	private $ag;

	/**
	*	@brief Constructs a adm_membership
	*	@param int $userId
	*		User's ID 
	*	@param string $startDate
	*		Membership start date
	*	@param string $endDate
	*		Membership end date
	*	@param bool $fee
	*		Membership fee
	*   @param bool $form
	*		Member registration form
	*   @param bool $certif
	*		School certificate
	*/
	public function __construct($id, $usererId, $startDate, $endDate, $fee, $form, $certif, $ag) {
		$this->id = intval($id);
		$this->user_id = intval($userId);
		$this->start_date = $startDate;
		$this->end_date = $endDate;
		$this->fee = (bool)$fee;
		$this->form = (bool)$form;
		$this->certif = (bool)$certif;
		$this->ag = $ag;
	}

public function jsonSerialize() {
		return [
			AdmMembershipDBObject::COL_ID => $this->id,
			AdmMembershipDBObject::COL_USER_ID => $this->user_id,
			AdmMembershipDBObject::COL_START_DATE => $this->start_date,
			AdmMembershipDBObject::COL_END_DATE => $this->end_date,
			AdmMembershipDBObject::COL_FEE => $this->fee,
			AdmMembershipDBObject::COL_FORM => $this->form,
			AdmMembershipDBObject::COL_CERTIF => $this->certif,
			AdmMembershipDBObject::COL_AG => $this->ag
		];
	}

	/**
	 * @brief Returns object's id
	 * @return int
	 */
	public function GetId() {
		return $this->id;
	}
	/**
	 * @brief
	 */
	public function GetUserId() {
		return $this->user_id;
	}
	/**
	 * @brief 
	 */
	public function GetStartDate() {
		return $this->start_date;
	}
	/**
	 * @brief
	 */
	public function GetEndDate() {
		return $this->end_date;
	}
	/**
	 * @brief
	 */
	public function GetFee() {
		return $this->fee;
	}
	/**
	 * @brief 
	 */
	public function GetForm() {
		return $this->form;
	}
	/**
	 * @brief
	 */
	public function GetCertif() {
		return $this->certif;
	}
	/**
	 * @brief
	 */
	public function GetAg() {
		return $this->ag;
	}
}

/**
 * @brief AdmMembership object related services
 */
class AdmMembershipServices extends AbstractObjectServices {
	
	// -- consts
	// --- params
	const PARAM_ID 			= "id";
	const PARAM_USER		= "userId";
	const PARAM_START	 	= "startDate";
	const PARAM_END		 	= "endDate";
	const PARAM_FEE			= "fee";
	const PARAM_FORM 		= "form";
	const PARAM_CERTIF	 	= "certif";
	const PARAM_AG		 	= "ag";
	// --- actions
	const GET_ADM_MEMBERSHIP_BY_ID 	= "byidam";
	const GET_ALL_ADM_MEMBERSHIPS   = "allam";
	const GET_USER_ADM_MEMBERSHIPS  = "alluam";
	const GET_ALL_AGS = "allag";
	const INSERT 		    = "insert";
	const UPDATE            = "update";
	const DELETE            = "delete";

	// -- functions

	// --- construct
	public function __construct($currentUser, $dbObject, $dbConnection) {
		parent::__construct($currentUser, $dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, AdmMembershipServices::GET_ADM_MEMBERSHIP_BY_ID)) {
			$data = $this->__get_adm_membership_by_id($params[AdmMembershipServices::PARAM_ID]);
		} else if(!strcmp($action, AdmMembershipServices::GET_ALL_ADM_MEMBERSHIPS)) {
			$data = $this->__get_all_adm_memberships();
		} else if(!strcmp($action, AdmMembershipServices::GET_ALL_AGS)) {
			$data = $this->__get_all_ags();
		} else if(!strcmp($action, AdmMembershipServices::GET_USER_ADM_MEMBERSHIPS)) {
			$data = $this->__get_current_user_adm_memberships();
		} else if(!strcmp($action, AdmMembershipServices::INSERT)) {
			$data = $this->__insert_adm_membership(
				$params[AdmMembershipServices::PARAM_USER],
				$params[AdmMembershipServices::PARAM_START],
				$params[AdmMembershipServices::PARAM_END],
				$params[AdmMembershipServices::PARAM_FEE],
				$params[AdmMembershipServices::PARAM_FORM],
				$params[AdmMembershipServices::PARAM_CERTIF],
				$params[AdmMembershipServices::PARAM_AG]);
		} else if(!strcmp($action, AdmMembershipServices::UPDATE)) {
			$data = $this->__update_adm_membership(
				$params[AdmMembershipServices::PARAM_ID],
				$params[AdmMembershipServices::PARAM_USER],
				$params[AdmMembershipServices::PARAM_START],
				$params[AdmMembershipServices::PARAM_END],
				$params[AdmMembershipServices::PARAM_FEE],
				$params[AdmMembershipServices::PARAM_FORM],
				$params[AdmMembershipServices::PARAM_CERTIF],
				$params[AdmMembershipServices::PARAM_AG]);
		} else if(!strcmp($action, AdmMembershipServices::DELETE)) {
			$data = $this->__delete_adm_membership($params[AdmMembershipServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ###############################################################

	// --- consult

	private function __get_adm_membership_by_id($id) {
		// create sql params array
		$sql_params = array(":".AdmMembershipDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(AdmMembershipDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create adm_membership var
		$adm_membership = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$adm_membership = new AdmMembership(
					$row[AdmMembershipDBObject::COL_ID], 
					$row[AdmMembershipDBObject::COL_USER_ID], 
					$row[AdmMembershipDBObject::COL_START_DATE], 
					$row[AdmMembershipDBObject::COL_END_DATE], 
					$row[AdmMembershipDBObject::COL_FEE],
					$row[AdmMembershipDBObject::COL_FORM],
					$row[AdmMembershipDBObject::COL_CERTIF],
					$row[AdmMembershipDBObject::COL_AG]);
			}
		}
		return $adm_membership;
	}

	private function __get_all_adm_memberships() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for adm_memberships and fill it
		$adm_memberships = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($adm_memberships, new AdmMembership(
					$row[AdmMembershipDBObject::COL_ID], 
					$row[AdmMembershipDBObject::COL_USER_ID], 
					$row[AdmMembershipDBObject::COL_START_DATE], 
					$row[AdmMembershipDBObject::COL_END_DATE], 
					$row[AdmMembershipDBObject::COL_FEE],
					$row[AdmMembershipDBObject::COL_FORM],
					$row[AdmMembershipDBObject::COL_CERTIF],
					$row[AdmMembershipDBObject::COL_AG]));
			}
		}
		return $adm_memberships;
	}

	private function __get_current_user_adm_memberships() {
		// create sql params array
		$sql_params = array(":".AdmMembershipDBObject::COL_USER_ID => parent::getCurrentUser()->GetId());
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(AdmMembershipDBObject::COL_USER_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for adm_memberships and fill it
		$adm_memberships = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($adm_memberships, new AdmMembership(
					$row[AdmMembershipDBObject::COL_ID], 
					$row[AdmMembershipDBObject::COL_USER_ID], 
					$row[AdmMembershipDBObject::COL_START_DATE], 
					$row[AdmMembershipDBObject::COL_END_DATE], 
					$row[AdmMembershipDBObject::COL_FEE], 
					$row[AdmMembershipDBObject::COL_FORM], 
					$row[AdmMembershipDBObject::COL_CERTIF],
					$row[AdmMembershipDBObject::COL_AG]));
			}
		}
		return $adm_memberships;
	}

	private function __get_all_ags() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_AG)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for teams and fill it
		$ags = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($ags,$row[AdmMembershipDBObject::COL_AG]); 
			}
		}
		return $ags;
	}

	// --- modify

	private function __insert_adm_membership($userId, $startDate, $endDate, $fee, $form, $certif, $ag) {
		// create sql params
		$sql_params = array(
			":".AdmMembershipDBObject::COL_ID => "NULL",
			":".AdmMembershipDBObject::COL_USER_ID => $userId,
			":".AdmMembershipDBObject::COL_START_DATE => $startDate,
			":".AdmMembershipDBObject::COL_END_DATE => $endDate,
			":".AdmMembershipDBObject::COL_FEE => $fee,
			":".AdmMembershipDBObject::COL_FORM => $form,
			":".AdmMembershipDBObject::COL_CERTIF => $certif,
			":".AdmMembershipDBObject::COL_AG => $ag);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_AdmMembership($id, $userId, $startDate, $endDate, $fee, $form, $certif, $ag) {
		// create sql params
		$sql_params = array(
			":".AdmMembershipDBObject::COL_ID => $id,
			":".AdmMembershipDBObject::COL_USER_ID => $userId,
			":".AdmMembershipDBObject::COL_START_DATE => $startDate,
			":".AdmMembershipDBObject::COL_END_DATE => $endDate,
			":".AdmMembershipDBObject::COL_FEE => $fee,
			":".AdmMembershipDBObject::COL_FORM => $form,
			":".AdmMembershipDBObject::COL_CERTIF => $certif,
			":".AdmMembershipDBObject::COL_AG => $ag);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}	

	private function __delete_AdmMembership($id) {
		// create sql params
		$sql_params = array(":".AdmMembershipDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
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
		
	}

}

/**
 *	@brief AdmMembership object interface
 */
class AdmMembershipDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "adm_membership";
	// --- tables
	const TABL_ADM_MEMBERSHIP = "dol_adm_membership";
	const TABL_AG = "com_ag";
	// --- columns
	const COL_ID = "id";
	const COL_USER_ID = "user_id";
	const COL_START_DATE = "start_date";
	const COL_END_DATE = "end_date";
	const COL_FEE = "fee";
	const COL_FORM = "form";
	const COL_CERTIF = "certif";
	const COL_AG = "ag";
	// -- attributes

	// -- functions

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, AdmMembershipDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_adm_membership table
		$dol_adm_membership = new DBTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_USER_ID, DBTable::DT_INT, 11, false);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_START_DATE, DBTable::DT_VARCHAR, 255, false);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_END_DATE, DBTable::DT_VARCHAR, 255, false);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_FEE, DBTable::DT_INT, 1, false); // boolean
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_FORM, DBTable::DT_INT, 1, false); // boolean
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_CERTIF, DBTable::DT_INT, 1, false); // boolean
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_AG, DBTable::DT_VARCHAR, 255, false);

		// --- com_ag table
		$com_ag = new DBTable(AdmMembershipDBObject::TABL_AG);
		$com_ag->AddColumn(AdmMembershipDBObject::COL_AG, DBTable::DT_VARCHAR, 255, false, "", false, true);

		// -- add tables
		parent::addTable($dol_adm_membership);
		parent::addTable($com_ag);
	}
	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices($currentUser) {
		return new AdmMembershipServices($currentUser, $this, $this->getDBConnection());
	}
	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new AdmMembershipServices(null, $this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}