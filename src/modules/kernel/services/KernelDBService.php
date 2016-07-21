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
	const GET_ALL_UDATA_WITH_MEM	= "alluwmem";

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, KernelDBService::SERV_NAME);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, KernelDBService::GET_ALL_UDATA_WITH_MEM)) {
			$data = $this->__get_all_user_data_with_memberships();
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	private function __get_all_user_data_with_memberships() {
		// create sql request
		$sql1 = parent::GetTable(UserDataDBObject::TABL_USER_DATA)->GetSELECTQuery();
		//$sql2 = parent::GetTable()
		$sql = $sql1;
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$udata = array();
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				//array_push($udata, );
			}
		}
		return $udata;
	}

}