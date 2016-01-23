<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 * @brief Ticket object
 */
class Ticket {
	
	// -- consts
	const AUTO_ID = -1;
	const NO_RCVR_ID = -1;
	const STATUS_OPEN = 1;
	const STATUS_AFFECTED = 2;
	const STATUS_SOLVED = 3;

	// -- attributes
	private $id;
	private $sender_id;
	private $receiver_id;
	private $category_id;
	private $status_id;
	private $data;
	private $subject;

	/**
	*	@brief Constructs a ticket
	*	@param int $senderId
	*		Sender's ID 
	*	@param int $receiverId
	*		Receiver's ID
	*	@param int $category
	*		Ticket category
	*	@param string $data
	*		ticket data
	*   @param int status
	*		Ticket status
	*/
	public function __construct($id, $senderId, $receiverId, $subject, $categoryId, $data, $statusId) {
		$this->id = $id;
		$this->sender_id = $senderId;
		$this->receiver_id = $receiverId;
		$this->category_id = $categoryId;
		$this->status_id = $statusId;
		$this->data = $data;
		$this->subject = $subject;
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
	public function GetSubject() {
		return $this->subject;
	}
	public function SetSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @brief
	 */
	public function GetCategoryId() {
		return $this->category_id;
	}
	public function SetCategoryId($category_id) {
		$this->category_id = $category_id;
	}

	/**
	 * @brief
	 */
	public function GetData() {
		return $this->data;
	}
	public function SetData($data) {
		$this->data = $data;
	}
	/**
	 * @brief
	 */
	public function GetStatusId() {
		return $this->status_id;
	}
	public function SetStatusId($status_id) {
		$this->status_id = $status_id;
	}

	/**
	 * @brief
	 */
	public function GetSenderId() {
		return $this->sender_id;
	}
	public function SetSenderId($sender_id) {
		$this->sender_id = $sender_id;
	}
}

/**
 * @brief Ticket object related services
 */
class TicketServices extends AbstractObjectServices {
	
	// -- consts
	// --- params
	const PARAM_ID 			= "id";
	const PARAM_SENDER 		= "senderId";
	const PARAM_RECEIVER 	= "receiverId";
	const PARAM_SUBJECT 	= "subject";
	const PARAM_CATEGO 		= "categoryId";
	const PARAM_DATA 		= "data";
	const PARAM_STATUS 		= "statusId";
	// --- actions
	const GET_TICKET_BY_ID = "byidt";
	const GET_STATUS_BY_ID = "byids";
	const GET_CATEGO_BY_ID = "byidc";
	const GET_ALL_TICKETS  = "allt";
	const GET_ALL_STATUSES = "alls";
	const GET_ALL_CATEGOS  = "allc";
	const INSERT_TICKET    = "insert";
	const UPDATE_TICKET    = "update";
	const DELETE_TICKET    = "delete";
	const ARCHIVE_TICKET   = "archive";

	// -- functions

	// --- construct
	public function __construct($dbObject, $dbConnection) {
		parent::__construct($dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, TicketServices::GET_TICKET_BY_ID)) {
			$data = $this->getTicketById($params[TicketServices::PARAM_ID]);
		} else if(!strcmp($action, TicketServices::GET_STATUS_BY_ID)) {
			$data = $this->getStatusId($params[TicketServices::PARAM_ID]);
		} else if(!strcmp($action, TicketServices::GET_CATEGO_BY_ID)) {
			$data = $this->getCategoryId($params[TicketServices::PARAM_ID]);
		} else if(!strcmp($action, TicketServices::GET_ALL_TICKETS)) {
			$data = $this->getAllTickets();
		} else if(!strcmp($action, TicketServices::GET_ALL_STATUSES)) {
			$data = $this->getAllTicketStatuses();
		} else if(!strcmp($action, TicketServices::GET_ALL_CATEGOS)) {
			$data = $this->getAllTicketCategories();
		} else if(!strcmp($action, TicketServices::INSERT_TICKET)) {
			$data = $this->insertTicket(
				$params[TicketServices::PARAM_SENDER],
				$params[TicketServices::PARAM_RECEIVER],
				$params[TicketServices::PARAM_SUBJECT],
				$params[TicketServices::PARAM_CATEGO],
				$params[TicketServices::PARAM_DATA],
				$params[TicketServices::PARAM_STATUS]);
		} else if(!strcmp($action, TicketServices::UPDATE_TICKET)) {
			$data = $this->updateTicket(
				$params[TicketServices::PARAM_ID],
				$params[TicketServices::PARAM_SENDER],
				$params[TicketServices::PARAM_RECEIVER],
				$params[TicketServices::PARAM_SUBJECT],
				$params[TicketServices::PARAM_CATEGO],
				$params[TicketServices::PARAM_DATA],
				$params[TicketServices::PARAM_STATUS]);
		} else if(!strcmp($action, TicketServices::DELETE_TICKET)) {
			$data = $this->deleteTicket($params[TicketServices::PARAM_ID]);
		} else if(!strcmp($action, TicketServices::ARCHIVE_TICKET)) {
			$data = $this->archiveTicket($params[TicketServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ###############################################################

	// --- consult

	private function getTicketById($id) {
		// create sql params array
		$sql_params = array(":".TicketDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(TicketDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create ticket var
		$ticket = null;
		if( ($row = $pdos->fetch()) !== false) {
			$ticket = new Ticket(
				$row[TicketDBObject::COL_ID], 
				$row[TicketDBObject::COL_SENDER_ID], 
				$row[TicketDBObject::COL_RECEIVER_ID], 
				$row[TicketDBObject::COL_SUBJECT], 
				$row[TicketDBObject::COL_CATEGORY_ID], 
				$row[TicketDBObject::COL_DATA], 
				$row[TicketDBObject::COL_STATUS_ID]);
		}
		return $ticket;
	}

	private function getStatusId($id) {
		// create sql params array
		$sql_params = array(":".TicketDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_STATUS)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(TicketDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for tickets and fill it
		$status = null;
		if( ($row = $pdos->fetch()) !== false) {
			$status = $row[DBTable::COL_LABEL];
		}
		return $status;
	}

	private function getCategoryId($id) {
		// create sql params array
		$sql_params = array(":".TicketDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_CATEGO)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(TicketDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for tickets and fill it
		$category = null;
		if( ($row = $pdos->fetch()) !== false) {
			$category = $row[DBTable::COL_LABEL];
		}
		return $category;
	}

	private function getAllTickets() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$tickets = array();
		while( ($row = $pdos->fetch()) !== false) {
			array_push($tickets, new Ticket(
				$row[TicketDBObject::COL_ID], 
				$row[TicketDBObject::COL_SENDER_ID], 
				$row[TicketDBObject::COL_RECEIVER_ID], 
				$row[TicketDBObject::COL_SUBJECT], 
				$row[TicketDBObject::COL_CATEGORY_ID], 
				$row[TicketDBObject::COL_DATA], 
				$row[TicketDBObject::COL_STATUS_ID]));
		}
		return $tickets;
	}

	private function getAllTicketStatuses() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_STATUS)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$statuses = array();
		while( ($row = $pdos->fetch()) !== false) {
			array_push($statuses, $row[DBTable::COL_LABEL]);
		}
		return $statuses;
	}

	private function getAllTicketCategories() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_CATEGO)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$categories = array();
		while( ($row = $pdos->fetch()) !== false) {
			array_push($categories, $row[DBTable::COL_LABEL]);
		}
		return $categories;
	}

	// --- modify

	private function insertTicket($senderId, $receiverId, $subject, $categoryId, $data, $statusId) {
		// create sql params
		$sql_params = array(
			":".TicketDBObject::COL_ID => "NULL",
			":".TicketDBObject::COL_SENDER_ID => $senderId,
			":".TicketDBObject::COL_RECEIVER_ID => $receiverId,
			":".TicketDBObject::COL_SUBJECT => $subject,
			":".TicketDBObject::COL_CATEGORY_ID => $categoryId,
			":".TicketDBObject::COL_DATA => $data,
			":".TicketDBObject::COL_STATUS_ID => $statusId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function updateTicket($id, $senderId, $receiverId, $subject, $categoryId, $data, $statusId) {
		// create sql params
		$sql_params = array(
			":".TicketDBObject::COL_ID => $id,
			":".TicketDBObject::COL_SENDER_ID => $senderId,
			":".TicketDBObject::COL_RECEIVER_ID => $receiverId,
			":".TicketDBObject::COL_SUBJECT => $subject,
			":".TicketDBObject::COL_CATEGORY_ID => $categoryId,
			":".TicketDBObject::COL_DATA => $data,
			":".TicketDBObject::COL_STATUS_ID => $statusId);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}	

	private function deleteTicket($id) {
		// create sql params
		$sql_params = array(":".TicketDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function archiveTicket($id) {
		// create sql params
		$sql_params = array(":".TicketDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetARCHIVEQuery(TicketDBObject::TABL_ARCHIV);
		// execute archive query
		$ok = false;
		if(parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
			// create delete query
			$sql = parent::getDBObject()->GetTable(TicketDBObject::TABL_TICKET)->GetDELETEQuery();
			// execute delete query
			if(parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
				$ok = true;
			}
		}
		return $ok;
	}

}

/**
 *	@brief Ticket object interface
 */
class TicketDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "ticket";
	// --- tables
	const TABL_TICKET = "dol_ticket";
	const TABL_CATEGO = "dol_ticket_category";
	const TABL_STATUS = "dol_ticket_status";
	const TABL_ARCHIV = "dol_ticket_archive";
	// --- columns
	const COL_ID = "id";
	const COL_SENDER_ID = "sender_id";
	const COL_RECEIVER_ID = "receiver_id";
	const COL_SUBJECT = "subject";
	const COL_CATEGORY_ID = "category_id";
	const COL_DATA = "data";
	const COL_STATUS_ID = "status_id";
	const COL_LABEL = "label";
	// -- attributes

	// -- functions

	public function __construct(&$dbmanager) {
		// -- construct parent
		parent::__construct($dbmanager,TicketDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_ticket table
		$dol_ticket = new DBTable(TicketDBObject::TABL_TICKET);
		$dol_ticket->AddColumn(TicketDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_ticket->AddColumn(TicketDBObject::COL_SENDER_ID, DBTable::DT_INT, 11, false);
		$dol_ticket->AddColumn(TicketDBObject::COL_RECEIVER_ID, DBTable::DT_INT, 11, false);
		$dol_ticket->AddColumn(TicketDBObject::COL_SUBJECT, DBTable::DT_VARCHAR, 255, false);
		$dol_ticket->AddColumn(TicketDBObject::COL_CATEGORY_ID, DBTable::DT_INT, 11, false);
		$dol_ticket->AddColumn(TicketDBObject::COL_DATA, DBTable::DT_TEXT, 11, false);
		$dol_ticket->AddColumn(TicketDBObject::COL_STATUS_ID, DBTable::DT_INT, 11, false);
		// --- dol_ticket_archive
		$dol_ticket_archive = new DBTable(TicketDBObject::TABL_ARCHIV);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_SENDER_ID, DBTable::DT_INT, 11, false);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_RECEIVER_ID, DBTable::DT_INT, 11, false);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_SUBJECT, DBTable::DT_VARCHAR, 255, false);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_CATEGORY_ID, DBTable::DT_INT, 11, false);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_DATA, DBTable::DT_TEXT, 11, false);
		$dol_ticket_archive->AddColumn(TicketDBObject::COL_STATUS_ID, DBTable::DT_INT, 11, false);
		// --- dol_ticket_category table
		$dol_ticket_category = new DBTable(TicketDBObject::TABL_CATEGO);
		$dol_ticket_category->AddColumn(TicketDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_ticket_category->AddColumn(TicketDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false);
		// --- dol_ticket_status table
		$dol_ticket_status = new DBTable(TicketDBObject::TABL_STATUS);
		$dol_ticket_status->AddColumn(TicketDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_ticket_status->AddColumn(TicketDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false);

		// -- add tables
		parent::addTable($dol_ticket);
		parent::addTable($dol_ticket_archive);
		parent::addTable($dol_ticket_category);
		parent::addTable($dol_ticket_status);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices() {
		return new TicketServices($this, $this->getDBConnection());
	}

}