<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 * @brief Ticket object
 */
class MailingList implements \JsonSerializable {
	
	// -- consts

	// -- attributes
	private $id;
	private $can_subscribe;
	private $name;

	/**
	*	@brief Constructs a MailingList
	*	@param int $id
	*		MailingList's ID 
	*	@param int $canSubscribe
	*		Can subscribe
	*	@param int $name
	*		MainlingList's name
	*/
	public function __construct($id, $canSubscribe, $name) {
		$this->id = intval($id);
		$this->can_subscribe = $canSubscribe;
		$this->name = $name;
	}

public function jsonSerialize() {
		return [
			MailingListDBObject::COL_ID => $this->id,
			MailingListDBObject::COL_CAN_SUBSCRIBE => $this->can_subscribe,
			MailingListDBObject::COL_NAME => $this->name
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
	public function GetCanSubscribe() {
		return $this->can_subscribe;
	}
	/**
	 * @brief
	 */
	public function GetName() {
		return $this->name;
	}
}

/**
 * @brief Ticket object related services
 */
class MailingListServices extends AbstractObjectServices {
	
	// -- consts
	// --- params
	const PARAM_ID 				= "id";
	const PARAM_USER_ID			= "userId";
	const PARAM_MAILLIST_ID		= "maillistId";
	const PARAM_CAN_SUBSCRIBE	= "canSubscribe";
	const PARAM_NAME 			= "name";
	// --- actions
	const GET_MAILLIST_BY_ID 	= "byid";
	const GET_ALL_MAILLIST  	= "all";
	const SUBSCRIBED 			= "subscribed";
	const SUBSCRIBE 			= "subscribe";
	const UNSUBSCRIBE 			= "unsubscribe";
	const INSERT 		   		= "insert";
	const UPDATE           		= "update";
	const DELETE          		= "delete";
	// --- test actions
	const AFFECT 				= "affect";

	// -- functions

	// --- construct
	public function __construct($currentUser, $dbObject, $dbConnection) {
		parent::__construct($currentUser, $dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, MailingListServices::GET_MAILLIST_BY_ID)) {
			$data = $this->__get_maillist_by_id($params[MailingListServices::PARAM_ID]);
		} else if(!strcmp($action, MailingListServices::GET_ALL_MAILLIST)) {
			$data = $this->__get_all_maillist();
		} else if(!strcmp($action, MailingListServices::INSERT)) {
			$data = $this->__insert_maillist(
				$params[MailingListServices::PARAM_CAN_SUBSCRIBE],
				$params[MailingListServices::PARAM_NAME]
				);
		} else if(!strcmp($action, MailingListServices::UPDATE)) {
			$data = $this->__update_maillist(
				$params[MailingListServices::PARAM_ID],
				$params[MailingListServices::PARAM_CAN_SUBSCRIBE],
				$params[MailingListServices::PARAM_NAME]
				);
		} else if(!strcmp($action, MailingListServices::DELETE)) {
			$data = $this->__delete_maillist($params[MailingListServices::PARAM_ID]);
		} else if(!strcmp($action, MailingListServices::SUBSCRIBE)) {
			$data = $this->__subscribe(
				$params[MailingListServices::PARAM_MAILLIST_ID]);
		} else if(!strcmp($action, MailingListServices::SUBSCRIBED)) {
			$data = $this->__subscribed(
				$params[MailingListServices::PARAM_MAILLIST_ID]);
		} else if(!strcmp($action, MailingListServices::UNSUBSCRIBE)) {
			$data = $this->__unsubscribe(
				$params[MailingListServices::PARAM_MAILLIST_ID]);
		} else if(!strcmp($action, MailingListServices::AFFECT)) {
			$data = $this->__affect(
				$params[MailingListServices::PARAM_USER_ID],
				$params[MailingListServices::PARAM_MAILLIST_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ###############################################################

	// --- consult

	private function __get_maillist_by_id($id) {
		// create sql params array
		$sql_params = array(":".MailingListDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_MAILLIST)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(MailingListDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create maillist var
		$maillist = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$maillist = new MailingList(
					$row[MailingListDBObject::COL_ID], 
					($row[MailingListDBObject::COL_CAN_SUBSCRIBE] === "0" ? false : true), 
					$row[MailingListDBObject::COL_NAME]);
			}
		}
		return $maillist;
	}

	private function __get_all_maillist() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_MAILLIST)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create maillists var
		$maillists = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($maillists, new MailingList(
					$row[MailingListDBObject::COL_ID], 
					($row[MailingListDBObject::COL_CAN_SUBSCRIBE] === "0" ? false : true), 
					$row[MailingListDBObject::COL_NAME]));
			}
		}
		return $maillists;
	}

	private function __subscribed($maillistId) {
		$sql_params = array(":".MailingListDBObject::COL_MAILLIST_ID => $maillistId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_USER_MAILLIST)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(MailingListDBObject::COL_MAILLIST_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create maillists var
		$userIds = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($userIds, $row[MailingListDBObject::COL_USER_ID]);
			}
		}
		return $userIds;
	}

	// --- modify

	private function __subscribe($maillistId) {
		// create sql params array
		$sql_params = array(":".MailingListDBObject::COL_ID => "NULL",
							":".MailingListDBObject::COL_MAILLIST_ID => $maillistId,
							":".MailingListDBObject::COL_USER_ID => $this->getCurrentUser()->GetId());
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_USER_MAILLIST)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __affect($userId, $maillistId) {
		// create sql params array
		$sql_params = array(":".MailingListDBObject::COL_ID => "NULL",
							":".MailingListDBObject::COL_MAILLIST_ID => $maillistId,
							":".MailingListDBObject::COL_USER_ID => $userId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_USER_MAILLIST)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __unsubscribe($maillistId) {
		// create sql params array
		$sql_params = array(":".MailingListDBObject::COL_MAILLIST_ID => $maillistId,
							":".MailingListDBObject::COL_USER_ID => $this->getCurrentUser()->GetId());
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_USER_MAILLIST)->GetDELETEQuery(array(
			MailingListDBObject::COL_MAILLIST_ID, MailingListDBObject::COL_USER_ID));
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __insert_maillist($canSubscribe, $name) {
		// create sql params array
		$sql_params = array(":".MailingListDBObject::COL_ID => "NULL",
							":".MailingListDBObject::COL_CAN_SUBSCRIBE => ($canSubscribe === "true" ? 1 : 0),
							":".MailingListDBObject::COL_NAME => $name);
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_MAILLIST)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_maillist($id, $canSubscribe, $name) {
		// create sql params
		$sql_params = array(
			":".MailingListDBObject::COL_ID => $id,
			":".MailingListDBObject::COL_CAN_SUBSCRIBE => ($canSubscribe === "true" ? 1 : 0),
			":".MailingListDBObject::COL_NAME => $name);
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_MAILLIST)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}	

	private function __delete_maillist($id) {
		// create sql params
		$sql_params = array(":".MailingListDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(MailingListDBObject::TABL_MAILLIST)->GetDELETEQuery();
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
		// no static data to init for this object
	}

}

/**
 *	@brief Mailing List object interface
 */
class MailingListDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "maillist";
	// --- tables
	const TABL_MAILLIST = "dol_maillist";
	const TABL_USER_MAILLIST = "dol_maillist_user";
	// --- columns
	const COL_ID = "id";
	const COL_MAILLIST_ID = "maillist_id";
	const COL_USER_ID = "user_id";
	const COL_CAN_SUBSCRIBE = "can_subscribe";
	const COL_NAME = "name";
	// -- attributes

	// -- functions

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, MailingListDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_maillist table
		$dol_maillist = new DBTable(MailingListDBObject::TABL_MAILLIST);
		$dol_maillist->AddColumn(MailingListDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_maillist->AddColumn(MailingListDBObject::COL_CAN_SUBSCRIBE, DBTable::DT_BOOLEAN);
		$dol_maillist->AddColumn(MailingListDBObject::COL_NAME, DBTable::DT_VARCHAR, 255, false);
		// --- dol_user_maillist table
		$dol_user_maillist = new DBTable(MailingListDBObject::TABL_USER_MAILLIST);
		$dol_user_maillist->AddColumn(MailingListDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_user_maillist->AddColumn(MailingListDBObject::COL_MAILLIST_ID, DBTable::DT_INT, 11, false);
		$dol_user_maillist->AddColumn(MailingListDBObject::COL_USER_ID, DBTable::DT_INT, 11, false);

		// -- add tables
		parent::addTable($dol_maillist);
		parent::addTable($dol_user_maillist);
	}
	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices($currentUser) {
		return new MailingListServices($currentUser, $this, $this->getDBConnection());
	}
	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new MailingListServices(null, $this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}