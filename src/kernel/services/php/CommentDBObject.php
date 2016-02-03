<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 *	@brief The Comment class
 */
class Comment implements \JsonSerializable {
	
	// -- consts

	// -- attributes
	// --- persistent
	private $id;
	private $user_id;
	private $date;
	private $data;


	/**
	*	@brief Constructs a comment
	*/
	public function __construct($id, $date, $userId, $data) {
		$this->id = $id;
		$this->user_id = $userId;
		$this->date = $date;
		$this->data = $data;
	}

	public function jsonSerialize() {
		return [
			CommentDBObject::COL_ID => $this->id,
			CommentDBObject::COL_USER_ID => $this->user_id,
			CommentDBObject::COL_DATE => $this->date,
			CommentDBObject::COL_DATA => $this->data
		];
	}

	/**
	*	@brief Returns comment id
	*	@return string
	*/
	public function GetId() {
		return $this->id;
	}
	/**
	*	@brief Returns comment user id
	*	@return string
	*/
	public function GetUserId() {
		return $this->user_id;
	}
	/**
	*	@brief Returns comment date
	*	@return string
	*/
	public function GetDate() {
		return $this->date;
	}
	/**
	*	@brief Returns comment data
	*	@return string
	*/
	public function GetData() {
		return $this->data;
	}
}


class CommentServices extends AbstractObjectServices {

	// -- consts
	// --- params keys
	const PARAM_ID 			= "id";
	const PARAM_USER_ID		= "userId";
	const PARAM_DATA		= "data";
	// --- internal services (actions)
	const GET_COMMENT_BY_ID	= "byid";
	const GET_ALL_COMMENTS 	= "all";
	const INSERT			= "insert";
	const UPDATE			= "update";
	const DELETE			= "delete";
	// -- functions

	// -- construct
	public function __construct($dbObject, $dbConnection) {
		parent::__construct($dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, CommentServices::GET_COMMENT_BY_ID)) {
			$data = $this->__get_comment_by_id($params[CommentServices::PARAM_ID]);
		} else if(!strcmp($action, CommentServices::GET_ALL_COMMENTS)) {
			$data = $this->__get_all_comments();
		} else if(!strcmp($action, CommentServices::INSERT)) {
			$data = $this->__insert_comment(
				$params[CommentServices::PARAM_USER_ID], 
				$params[CommentServices::PARAM_DATA]);
		} else if(!strcmp($action, CommentServices::UPDATE)) {
			$data = $this->__update_comment(
				$params[CommentServices::PARAM_ID],  
				$params[CommentServices::PARAM_DATA]);
		} else if(!strcmp($action, CommentServices::DELETE)) {
			$data = $this->__delete_comment($params[CommentServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	// -- consult

	private function __get_comment_by_id($id) {
		// create sql params array
		$sql_params = array(":".CommentDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(CommentDBObject::TABL_COMMENT)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(CommentDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create ticket var
		$comment = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$comment = new Comment(
					$row[CommentDBObject::COL_ID], 
					$row[CommentDBObject::COL_USER_ID], 
					$row[CommentDBObject::COL_DATE],
					$row[CommentDBObject::COL_DATA]);
			}
		}
		return $comment;
	}

	private function __get_all_comments() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(CommentDBObject::TABL_COMMENT)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$comments = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($comments, new Comment(
					$row[CommentDBObject::COL_ID],  
					$row[CommentDBObject::COL_USER_ID],
					$row[CommentDBObject::COL_DATE], 
					$row[CommentDBObject::COL_DATA]));
			}
		}
		return $comments;
	}

	// -- modify

	private function __insert_comment($userId, $data) {
		// create sql params
		$sql_params = array(
			":".CommentDBObject::COL_ID => "NULL",
			":".CommentDBObject::COL_USER_ID => $userId,
			":".CommentDBObject::COL_DATE => date(DateTime::ISO8601),
			":".CommentDBObject::COL_DATA => $data);
		// create sql request
		$sql = parent::getDBObject()->GetTable(CommentDBObject::TABL_COMMENT)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	} 

	private function __update_comment($id, $data) {
		// create sql params
		$sql_params = array(
			":".CommentDBObject::COL_ID => $id,
			":".CommentDBObject::COL_DATA => $data);
		// create sql request
		$sql = parent::getDBObject()->GetTable(CommentDBObject::TABL_COMMENT)->GetUPDATEQuery(array(CommentDBObject::COL_DATA));
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __delete_comment($id) {
		// create sql params
		$sql_params = array(":".CommentDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(CommentDBObject::TABL_COMMENT)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

	public function ResetStaticData() {
		// no static data for this module
	}

}

/**
 *	@brief Comment object interface
 */
class CommentDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "comment";
	// --- tables
	const TABL_COMMENT = "dol_comment";
	// --- columns
	const COL_ID = "id";
	const COL_USER_ID = "user_id";
	const COL_DATE = "date";
	const COL_DATA = "data";
	// -- attributes

	// -- functions

	public function __construct() {
		// -- construct parent
		parent::__construct(CommentDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_comment table
		$dol_comment = new DBTable(CommentDBObject::TABL_COMMENT);
		$dol_comment->AddColumn(CommentDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_comment->AddColumn(CommentDBObject::COL_USER_ID, DBTable::DT_INT, 11, false);
		$dol_comment->AddColumn(CommentDBObject::COL_DATE, DBTable::DT_VARCHAR, 255, false);
		$dol_comment->AddColumn(CommentDBObject::COL_DATA, DBTable::DT_TEXT, -1, false);
		// -- add tables
		parent::addTable($dol_comment);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices() {
		return new CommentServices($this, $this->getDBConnection());
	}

	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new CommentServices($this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}