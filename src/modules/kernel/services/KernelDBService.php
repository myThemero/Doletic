<?php

require_once "interfaces/AbstractDBService.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php"; 

class KernelDBService extends AbstractDBService {

	// -- consts
	// --- service name
	const SERV_NAME = "modkernel";
	// --- params keys
	const PARAM_ID 			= "id";
	const PARAM_USER_ID		= "userId";
	// --- internal services (actions)
	const GET_ALL_UDATA_WITH_STATUS		= "alluwsta";
	const GET_ALL_INTERVENANTS			= "allint";
	const GET_ALL_VALID_INTERVENANTS	= "allvalint";

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, KernelDBService::SERV_NAME);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, KernelDBService::GET_ALL_UDATA_WITH_STATUS)) {
			$data = $this->__get_all_user_data_with_status();
		} else if(!strcmp($action, KernelDBService::GET_ALL_INTERVENANTS)) {
			$data = $this->__get_all_intervenants();
		} else if(!strcmp($action, KernelDBService::GET_ALL_VALID_INTERVENANTS)) {
			$data = $this->__get_all_valid_intervenants();
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	private function __get_all_user_data_with_status() {
		$data = parent::GetModule()
			->GetDBObject(UserDataDBObject::OBJ_NAME)
			->GetServices($this->GetCurrentUser())
			->GetResponseData(UserDataServices::GET_ALL_USER_DATA, array());
		foreach($data as $udata) {
			//$udata->SetAdmmStatus();
			//$udata->SetIntmStatus();
		}
		return $data;
	}

	private function __get_all_user_positions($userId) {
		// create sql params array
		$sql_params = array(":".UserDataDBObject::COL_USER_ID => $userId);
		// create sql request
		$sql1 = parent::GetTable(UserDataDBObject::TABL_USER_POSITION)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(UserDataDBObject::COL_USER_ID));
		$sql2 = parent::GetTable(UserDataDBObject::TABL_COM_POSITION)->GetSELECTQuery(
						array(DBTable::SELECT_ALL));
		$sql = DBTable::GetJOINQuery($sql1, $sql2, array(UserDataDBObject::COL_POSITION, UserDataDBObject::COL_LABEL), DBTable::DT_INNER, "", false, null, array(), array(UserDataDBObject::COL_SINCE => DBTable::ORDER_DESC));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create an empty array for udata and fill it
		$positions = array();
		while( ($row = $pdos->fetch()) !== false) {
			array_push($positions, array(
					UserDataDBObject::COL_LABEL => $row[UserDataDBObject::COL_LABEL],
					UserDataDBObject::COL_SINCE => $row[UserDataDBObject::COL_SINCE],
					UserDataDBObject::COL_RG_CODE => $row[UserDataDBObject::COL_RG_CODE],
					UserDataDBObject::COL_DIVISION => $row[UserDataDBObject::COL_DIVISION]
				));
		}
		return $positions;
	}

	private function __get_all_intervenants() {
		$udata = array();
		return $udata;
	}

	private function __get_all_valid_intervenants() {
		$udata = array();
		return $udata;
	}

}