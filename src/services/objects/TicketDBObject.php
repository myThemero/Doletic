<?php

require_once "interfaces/AbstractDBObject.php";
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
class TicketServices {
	
	// -- consult

	public function GetTicketById($id) {
		/// \todo implement here
	}

	public function GetStatusId($id) {
		/// \todo implement here
	}

	public function GetCategoryId($id) {
		/// \todo implement here
	}

	public function GetAllTickets() {
		/// \todo implement here
	}

	public function GetAllTicketStatuses() {
		/// \todo implement here
	}

	public function GetAllTicketCategories() {
		/// \todo implement here
	}

	// -- modify

	public function InsertTicket($id, $senderId, $receiverId, $subject, $categoryId, $data, $statusId) {
		/// \todo implement here
	}

	public function UpdateTicket($id, $senderId, $receiverId, $subject, $categoryId, $data, $statusId) {
		/// \todo implement here
	}	

	public function DeleteTicket($id) {
		/// \todo implement here
	}

	public function ArchiveTicket($id) {
		/// \todo implement here
	}

}

/**
 *	@brief Ticket object interface
 */
class TicketDBObject extends AbstractDBObject {

	// -- consts
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

	public function __construct() {
		// -- construct parent
		parent::__construct("ticket", new TicketServices());
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
		$this->addTable($dol_ticket);
		$this->addTable($dol_ticket_archive);
		$this->addTable($dol_ticket_category);
		$this->addTable($dol_ticket_status);
	}

}