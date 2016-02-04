<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php"; 

/**
 * @brief Upload object
 */
class Upload implements \JsonSerializable {
	
	// -- consts
	// -- attributes
	private $id;
	private $user_id;			// user who uploaded the file
	private $timestamp;
	private $filename;			// can be displayed to user
	private $storage_filename; // storage filename : it's sensless to display it

	/**
	*	@brief Constructs an upload
	*	@param int $userId
	*		Uploader's ID 
	*	@param string $uploadDate
	*		Upload timestamp
	*	@param string $filename
	*		Upload displayable filename
	*	@param string $storageFilename
	*		Upload storage filename
	*/
	public function __construct($id, $userId, $timestamp, $filename, $storageFilename) {
		$this->id = $id;
		$this->user_id = $userId;
		$this->timestamp = $timestamp;
		$this->filename = $filename;
		$this->storage_filename = $storageFilename;
	}

public function jsonSerialize() {
		return [
			UploadDBObject::COL_ID => $this->id,
			UploadDBObject::COL_USER_ID => $this->userId,
			UploadDBObject::COL_TIMESTAMP => $this->timestamp,
			UploadDBObject::COL_FILENAME => $this->filename,
			UploadDBObject::COL_STOR_FNAME => $this->storageFilename
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
	public function GetTimestamp() {
		return $this->timestamp;
	}
	/**
	 * @brief
	 */
	public function GetFilename() {
		return $this->filename;
	}
	/**
	 * @brief
	 */
	public function GetStorageFilename() {
		return $this->storage_filename;
	}
}

/**
 * @brief Upload object related services
 */
class UploadServices extends AbstractObjectServices {
	
	// -- consts
	// --- params
	const PARAM_ID 			= "id";
	const PARAM_USER_ID 	= "userId";
	const PARAM_FNAME 		= "filename";
	const PARAM_STOR_FNAME 	= "storageFilename";
	// --- actions
	const GET_UPLOAD_BY_ID 			= "byid";
	const GET_UPLOAD_BY_STOR_FNAME 	= "bystfn";
	const GET_ALL_UPLOADS  			= "all";
	const INSERT 		   			= "insert";
	const UPDATE           			= "update";
	const DELETE           			= "delete";

	// -- functions

	// --- construct
	public function __construct($currentUser, $dbObject, $dbConnection) {
		parent::__construct($currentUser, $dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, UploadServices::GET_UPLOAD_BY_ID)) {
			$data = $this->__get_upload_by_id($params[UploadServices::PARAM_ID]);
		} else if(!strcmp($action, UploadServices::GET_UPLOAD_BY_STOR_FNAME)) {
			$data = $this->__get_upload_by_storage_filename($params[UploadServices::PARAM_STOR_FNAME]);
		} else if(!strcmp($action, UploadServices::GET_ALL_UPLOADS)) {
			$data = $this->__get_all_uploads();
		} else if(!strcmp($action, UploadServices::INSERT)) {
			$data = $this->__insert_upload(
				$params[UploadServices::PARAM_USER_ID],
				$params[UploadServices::PARAM_FNAME],
				$params[UploadServices::PARAM_STOR_FNAME]);
		} else if(!strcmp($action, UploadServices::UPDATE)) {
			$data = $this->__update_upload(
				$params[UploadServices::PARAM_ID],
				$params[UploadServices::PARAM_FNAME]);
		} else if(!strcmp($action, UploadServices::DELETE)) {
			$data = $this->__delete_upload($params[UploadServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ###############################################################

	// --- consult

	private function __get_upload_by_id($id) {
		// create sql params array
		$sql_params = array(":".UploadDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UploadDBObject::TABL_UPLOAD)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UploadDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create upload var
		$upload = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$upload = new Upload(
					$row[UploadDBObject::COL_ID], 
					$row[UploadDBObject::COL_USER_ID], 
					$row[UploadDBObject::COL_TIMESTAMP], 
					$row[UploadDBObject::COL_FILENAME], 
					$row[UploadDBObject::COL_STOR_FNAME]);
			}
		}
		return $upload;
	}

	private function __get_upload_by_storage_filename($storageFilename) {
		// create sql params array
		$sql_params = array(":".UploadDBObject::COL_STOR_FNAME => $storageFilename);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UploadDBObject::TABL_UPLOAD)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UploadDBObject::COL_STOR_FNAME));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create upload var
		$upload = null;
		if($pdos != null) {
			if( ($row = $pdos->fetch()) !== false) {
				$upload = new Upload(
					$row[UploadDBObject::COL_ID], 
					$row[UploadDBObject::COL_USER_ID], 
					$row[UploadDBObject::COL_TIMESTAMP], 
					$row[UploadDBObject::COL_FILENAME], 
					$row[UploadDBObject::COL_STOR_FNAME]);
			}
		}
		return $upload;
	}

	private function __get_all_uploads() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(UploadDBObject::TABL_UPLOAD)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for uploads and fill it
		$uploads = array();
		if($pdos != null) {
			while( ($row = $pdos->fetch()) !== false) {
				array_push($uploads, new Upload(
					$row[UploadDBObject::COL_ID], 
					$row[UploadDBObject::COL_USER_ID], 
					$row[UploadDBObject::COL_TIMESTAMP], 
					$row[UploadDBObject::COL_FILENAME], 
					$row[UploadDBObject::COL_STOR_FNAME]));
			}
		}
		return $uploads;
	}

	// --- modify

	private function __insert_upload($userId, $filename, $storageFilename) {
		// create sql params
		$sql_params = array(
			":".UploadDBObject::COL_ID => "NULL",
			":".UploadDBObject::COL_USER_ID => $userId,
			":".UploadDBObject::COL_TIMESTAMP => date(DateTime::ISO8601),
			":".UploadDBObject::COL_FILENAME => $filename,
			":".UploadDBObject::COL_STOR_FNAME => $storageFilename);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UploadDBObject::TABL_UPLOAD)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_upload($id, $filename) {
		// create sql params
		$sql_params = array(
			":".UploadDBObject::COL_ID => $id,
			":".UploadDBObject::COL_FILENAME => $filename);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UploadDBObject::TABL_UPLOAD)->GetUPDATEQuery(array(UploadDBObject::COL_FILENAME));
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}	

	private function __delete_upload($id) {
		// create sql params
		$sql_params = array(":".UploadDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(UploadDBObject::TABL_UPLOAD)->GetDELETEQuery();
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
		// no static data inside this block
	}

}

/**
 *	@brief Upload object interface
 */
class UploadDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "upload";
	// --- tables
	const TABL_UPLOAD = "dol_upload";
	// --- columns
	const COL_ID = "id";
	const COL_USER_ID = "user_id";
	const COL_TIMESTAMP = "timestamp";
	const COL_FILENAME = "filename";
	const COL_STOR_FNAME = "storage_filename";
	// -- attributes

	// -- functions

	public function __construct() {
		// -- construct parent
		parent::__construct(UploadDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_upload table
		$dol_upload = new DBTable(UploadDBObject::TABL_UPLOAD);
		$dol_upload->AddColumn(UploadDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true);
		$dol_upload->AddColumn(UploadDBObject::COL_USER_ID, DBTable::DT_INT, 11, false);
		$dol_upload->AddColumn(UploadDBObject::COL_TIMESTAMP, DBTable::DT_VARCHAR, 255, false);
		$dol_upload->AddColumn(UploadDBObject::COL_FILENAME, DBTable::DT_VARCHAR, 255, false);
		$dol_upload->AddColumn(UploadDBObject::COL_STOR_FNAME, DBTable::DT_VARCHAR, 255, false);

		// -- add tables
		parent::addTable($dol_upload);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices($currentUser) {
		return new UploadServices($currentUser, $this, $this->getDBConnection());
	}

	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new UploadServices(null, $this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}