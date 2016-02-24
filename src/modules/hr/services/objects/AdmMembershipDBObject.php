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
	private $cotis;
	private $fiche;
	private $certif;

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
	public function __construct($id, $usererId, $startDate, $endDate, $fee, $form, $certif) {
		$this->id = intval($id);
		$this->user_id = intval($userId);
		$this->start_date = $startDate;
		$this->end_date = $endDate;
		$this->fee = (bool)$fee;
		$this->form = (bool)$form;
		$this->certif = (bool)$certif;
	}

public function jsonSerialize() {
		return [
			AdmMembershipDBObject::COL_ID => $this->id,
			AdmMembershipDBObject::COL_USER_ID => $this->user_id,
			AdmMembershipDBObject::COL_START_DATE => $this->start_date,
			AdmMembershipDBObject::COL_END_DATE => $this->end_date,
			AdmMembershipDBObject::COL_FEE => $this->fee,
			AdmMembershipDBObject::COL_FORM => $this->form,
			AdmMembershipDBObject::COL_CERTIF => $this->certif
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
	// --- actions
	const GET_ADM_MEMBERSHIP_BY_ID 	= "byidaa";
	const GET_ALL_ADM_MEMBERSHIPS   = "allaa";
	const GET_USER_ADM_MEMBERSHIPS  = "alluaa";
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
		} else if(!strcmp($action, AdmMembershipServices::GET_USER_ADM_MEMBERSHIPS)) {
			$data = $this->__get_current_user_adm_memberships();
		} else if(!strcmp($action, AdmMembershipServices::INSERT)) {
			$data = $this->__insert_adm_membership(
				$params[AdmMembershipServices::PARAM_USER],
				$params[AdmMembershipServices::PARAM_START],
				$params[AdmMembershipServices::PARAM_END],
				$params[AdmMembershipServices::PARAM_FEE],
				$params[AdmMembershipServices::PARAM_FORM],
				$params[AdmMembershipServices::PARAM_CERTIF]);
		} else if(!strcmp($action, AdmMembershipServices::UPDATE)) {
			$data = $this->__update_adm_membership(
				$params[AdmMembershipServices::PARAM_ID],
				$params[AdmMembershipServices::PARAM_USER],
				$params[AdmMembershipServices::PARAM_START],
				$params[AdmMembershipServices::PARAM_END],
				$params[AdmMembershipServices::PARAM_FEE],
				$params[AdmMembershipServices::PARAM_FORM],
				$params[AdmMembershipServices::PARAM_CERTIF]);
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
					$row[AdmMembershipDBObject::COL_CERTIF]);
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
					$row[AdmMembershipDBObject::COL_NAME], 
					$row[AdmMembershipDBObject::COL_LEADER_ID], 
					$row[AdmMembershipDBObject::COL_CREATION_DATE], 
					$row[AdmMembershipDBObject::COL_DIVISION],  
					__get_adm_membership_members($row[AdmMembershipDBObject::COL_ID])));
			}
		}
		return $adm_memberships;
	}

	private function __get_all_divisions() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_DIV)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for adm_memberships and fill it
		$divisions = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				$divisions[$row[AdmMembershipDBObject::COL_ID]] = $row[AdmMembershipDBObject::COL_LABEL];
			}
		}
		return $divisions;
	}

	private function __get_current_user_adm_memberships() {
		// create sql params array
		$sql_params = array(":".AdmMembershipDBObject::COL_MEMBER_ID => parent::getCurrentUser()->GetId());
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_MEMBERS)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(AdmMembershipDBObject::COL_MEMBER_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for AdmMemberships and fill it
		$adm_memberships = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($adm_memberships, __get_adm_membership_by_id($row[AdmMembershipDBObject::COL_ID]));
			}
		}
		return $AdmMemberships;
	}

	private function __insert_member($id, $memberId){
		// create sql request
		$sql_params = array(
			":".AdmMembershipDBObject::COL_ID => $id,
			":".AdmMembershipDBObject::COL_MEMBER_ID => $memberId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_MEMBERS)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __delete_member($id, $memberId) {
		// create sql params
		$sql_params = array(
			":".AdmMembershipDBObject::COL_ID => $id,
			":".AdmMembershipDBObject::COL_MEMBER_ID => $memberId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_MEMBERS)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	// --- modify

	private function __insert_adm_membership($name, $leaderId, $division) {
		// create sql params
		$sql_params = array(
			":".AdmMembershipDBObject::COL_ID => "NULL",
			":".AdmMembershipDBObject::COL_NAME => $name,
			":".AdmMembershipDBObject::COL_LEADER_ID => $leaderId,
			":".AdmMembershipDBObject::COL_CREATION_DATE => date('Y-m-d'),
			":".AdmMembershipDBObject::COL_DIVISION => $division);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetINSERTQuery();
		// execute query
		if (parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			// Add leader as member
			$params = array(":".AdmMembershipDBObject::COL_NAME => $name);
			$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP)->GetSELECTQuery();
			if( ($row = $pdos->fetch()) !== false) {
				return __insert_member($row[AdmMembershipDBObject::COL_ID], $leaderId);
			}
		}
		return FALSE;
	}

	private function __update_AdmMembership($id, $name, $leaderId, $division) {
		// create sql params
		$sql_params = array(
			":".AdmMembershipDBObject::COL_ID => $id,
			":".AdmMembershipDBObject::COL_NAME => $name,
			":".AdmMembershipDBObject::COL_LEADER_ID => $leaderId,
			":".AdmMembershipDBObject::COL_DIVISION => $division);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_AdmMembership)->GetUPDATEQuery();
		// execute query
		if(parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			// Add leader as member if not already there
			$members = __get_adm_membership_members($id);
			if(!in_array($leaderId, $members)) {
				return __insert_member($id, $leaderId);
			}
			return TRUE;
		}
		return FALSE;
	}	

	private function __delete_AdmMembership($id) {
		// create sql params
		$sql_params = array(":".AdmMembershipDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_AdmMembership)->GetDELETEQuery();
		// execute query
		if(parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			// Delete members
			$members = __get_adm_membership_members($id);
			foreach($members as $member) {
				__delete_member($id, $member);
			}
			return TRUE;
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
		// -- init categories table --------------------------------------------------------------------
		$divisions = array("DSI","UA","GRC","Com", "Qualité", "SG", "Présidence", "RH", "Trésorerie");
		// --- retrieve SQL query
		$sql = parent::getDBObject()->GetTable(AdmMembershipDBObject::TABL_DIV)->GetINSERTQuery();
		foreach ($divisions as $division) {
			// --- create param array
			$sql_params = array(":".AdmMembershipDBObject::COL_ID => "NULL",
								":".AdmMembershipDBObject::COL_LABEL => $division);
			// --- execute SQL query
			parent::getDBConnection()->PrepareExecuteQuery($sql,$sql_params);
		}
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
	const TABL_MEMBERS = "dol_adm_membership_member";
	const TABL_DIV = "com_division";
	// --- columns
	const COL_ID = "id";
	const COL_NAME = "name";
	const COL_LEADER_ID = "leader_id";
	const COL_CREATION_DATE = "creation_date";
	const COL_DIVISION = "division";
	const COL_MEMBER_ID = "member_id";
	const COL_LABEL = "label";
	// -- attributes

	// -- functions

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, AdmMembershipDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_adm_membership table
		$dol_adm_membership = new DBTable(AdmMembershipDBObject::TABL_ADM_MEMBERSHIP);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_NAME, DBTable::DT_VARCHAR, 255, false);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_LEADER_ID, DBTable::DT_INT, 11, false);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_CREATION_DATE, DBTable::DT_VARCHAR, 255, false);
		$dol_adm_membership->AddColumn(AdmMembershipDBObject::COL_DIVISION, DBTable::DT_INT, 11, false);
		// --- dol_adm_membership_member
		$dol_adm_membership_member = new DBTable(AdmMembershipDBObject::TABL_MEMBERS);
		$dol_adm_membership_member->AddColumn(AdmMembershipDBObject::COL_ID, DBTable::DT_INT, 11, false, "", false, false);
		$dol_adm_membership_member->AddColumn(AdmMembershipDBObject::COL_MEMBER_ID, DBTable::DT_INT, 11, false, false);
		// --- com_division table
		$com_adm_membership_division = new DBTable(AdmMembershipDBObject::TABL_DIV);
		$com_adm_membership_division->AddColumn(AdmMembershipDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$com_adm_membership_division->AddColumn(AdmMembershipDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false);

		// -- add tables
		parent::addTable($dol_adm_membership);
		parent::addTable($dol_adm_membership_member);
		parent::addTable($com_adm_membership_division);
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