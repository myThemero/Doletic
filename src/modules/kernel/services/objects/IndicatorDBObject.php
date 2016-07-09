<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php"; 

/**
 *	@brief The Indicator class
 */
abstract class Indicator implements \JsonSerializable {
	
	// -- consts
	const VALUE_TYPE = "value";
	const TABLE_TYPE = "table";
	const GRAPH_TYPE = "graph";

	// -- attributes
	// --- persistent
	private $id = null;
	protected $procedure = null;
	protected $module = null;
	protected $description = null;
	protected $type = null;
	protected $params = array();

	/**
	*	@brief Constructs an indicator
	*/
	public function __construct($id, $procedure, $module, $description, $type, $params = "") {
		$this->id = intval($id);
		$this->procedure = $procedure;
		$this->module = $module;
		$this->description = $description;
		$this->type = $type;
		if($params == "") {
			$this->params = array();
		} else {
			$this->params = explode(";", $params);
		}
	}

	public function jsonSerialize() {
		return [
			IndicatorDBObject::COL_ID => $this->id,
			IndicatorDBObject::COL_PROCEDURE => $this->procedure,
			IndicatorDBObject::COL_MODULE => $this->module,
			IndicatorDBObject::COL_DESCRIPTION => $this->description,
			IndicatorDBObject::COL_TYPE => $this->type,
			IndicatorDBObject::COL_PARAMS => $this->params
		];
	}

	/**
	*	@brief Returns indicator id
	*	@return string
	*/
	public function GetId() {
		return $this->id;
	}
	/**
	*	@brief Returns indicator procedure
	*	@return string
	*/
	public function GetProcedure() {
		return $this->procedure;
	}
	/**
	*	@brief Returns indicator module
	*	@return string
	*/
	public function GetModule() {
		return $this->module;
	}
	/**
	*	@brief Returns indicator description
	*	@return string
	*/
	public function GetDescription() {
		return $this->description;
	}

	/**
	*	@brief Returns indicator type
	*	@return string
	*/
	public function GetType() {
		return $this->type;
	}

	/**
	*	@brief Returns indicator params
	*	@return string
	*/
	public function GetParams() {
		return $this->params;
	}
}

class IndicatorValue extends Indicator {
	// -- consts

	// -- attributes
	// --- persistent
	protected $expected_result = null;
	protected $unit = null;
	protected $expected_greater = null;

	/**
	*	@brief Constructs an indicator with value
	*/
	public function __construct($id, $procedure, $module, $description, $params = "", $expected_result = "", $unit = "", $expected_greater = true) {
		parent::__construct($id, $procedure, $module, $description, Indicator::VALUE_TYPE, $params);
		$this->expected_result = $expected_result;
		$this->unit = $unit;
		$this->expected_greater = boolval($expected_greater);
	}

	public function jsonSerialize() {
		return array_merge(parent::jsonSerialize(),
			[
				IndicatorDBObject::COL_EXPECTED_RESULT => $this->expected_result,
				IndicatorDBObject::COL_UNIT => $this->unit,
				IndicatorDBObject::COL_EXPECTED_GREATER => $this->expected_greater
			]
		);
	}

	/**
	*	@brief Returns indicator expected result
	*	@return string
	*/
	public function GetExpectedResult() {
		return $this->expected_result;
	}
	/**
	*	@brief Returns indicator unit
	*	@return string
	*/
	public function GetUnit() {
		return $this->unit;
	}
	/**
	*	@brief Returns wether the indicator should be greater than expected result or not
	*	@return string
	*/
	public function GetExpectedGreater() {
		return $this->expected_greater;
	}
}

class IndicatorGraph extends Indicator {
	// -- consts

	// -- attributes
	// --- persistent
	protected $graph_type = null;
	protected $legend = array();

	/**
	*	@brief Constructs an indicator with graph
	*/
	public function __construct($id, $procedure, $module, $description, $params = "", $graphType = "scatter", $legend="") {
		parent::__construct($id, $procedure, $module, $description, Indicator::GRAPH_TYPE, $params);
		$this->graph_type = $graphType;
		if($legend == "") {
			$this->legend = array();
		} else {
			$this->legend = explode(";", $legend);
		}
	}

	public function jsonSerialize() {
		return array_merge(parent::jsonSerialize(),
			[
				IndicatorDBObject::COL_GRAPH_TYPE => $this->graph_type,
				IndicatorDBObject::COL_LEGEND => $this->legend
			]
		);
	}

	/**
	*	@brief Returns indicator plotly graph type
	*	@return string
	*/
	public function GetGraphType() {
		return $this->graph_type;
	}
	/**
	*	@brief Returns indicator legend 
	*	@return array
	*/
	public function GetLegend() {
		return $this->legend;
	}
}

class IndicatorTable extends Indicator {
	// -- consts

	// -- attributes
	// --- persistent
	protected $label_column = null;
	protected $result_column = null;

	/**
	*	@brief Constructs an indicator with graph
	*/
	public function __construct($id, $procedure, $module, $description, $params = "", $labelColumn = "", $resultColumn="") {
		parent::__construct($id, $procedure, $module, $description, Indicator::TABLE_TYPE, $params);
		$this->label_column = $labelColumn;
		$this->result_column = $resultColumn;
	}

	public function jsonSerialize() {
		return array_merge(parent::jsonSerialize(),
			[
				IndicatorDBObject::COL_LABEL_COLUMN => $this->label_column,
				IndicatorDBObject::COL_RESULT_COLUMN => $this->result_column
			]
		);
	}

	/**
	*	@brief Returns indicator plotly graph type
	*	@return string
	*/
	public function GetGraphType() {
		return $this->graph_type;
	}
	/**
	*	@brief Returns indicator legend 
	*	@return array
	*/
	public function GetLegend() {
		return $this->legend;
	}
}

class IndicatorServices extends AbstractObjectServices {

	// -- consts
	// --- params keys
	const PARAM_ID 					= "id";
	const PARAM_DESCRIPTION			= "description";
	const PARAM_MODULE				= "module";
	const PARAM_PROCEDURE			= "procedure";
	const PARAM_TYPE				= "type";
	const PARAM_PARAMS				= "params";
	const PARAM_EXPECTED_RESULT		= "expectedResult";
	const PARAM_UNIT				= "unit";
	const PARAM_EXPECTED_GREATER	= "expectedGreater";
	const PARAM_GRAPH_TYPE			= "graphType";
	const PARAM_LEGEND				= "legend";
	const PARAM_LABEL_COLUMN		= "labelColumn";
	const PARAM_RESULT_COLUMN		= "resultColumn";
	// --- internal services (actions)
	const GET_BY_ID			= "byid";
	const GET_ALL			= "all";
	const GET_ALL_VALUE		= "allval";
	const GET_ALL_TABLE		= "alltab";
	const GET_ALL_GRAPH		= "allgra";
	const PROCESS_BY_ID		= "procbyid";
	const PROCESS_ALL		= "procall";
	const PROCESS_ALL_VALUE	= "procval";
	const PROCESS_ALL_TABLE	= "proctab";
	const PROCESS_ALL_GRAPH	= "procgra";
	const INSERT_VALUE		= "insval";
	const INSERT_TABLE		= "instab";
	const INSERT_GRAPH		= "insgra";
	const UPDATE_VALUE		= "updval";
	const UPDATE_TABLE		= "updtab";
	const UPDATE_GRAPH		= "updgra";
	const DISABLE			= "disable";
	const ENABLE			= "enable";
	const DELETE			= "delete";
	// -- functions

	// -- construct
	public function __construct($currentUser, $dbObject, $dbConnection) {
		parent::__construct($currentUser, $dbObject, $dbConnection);
	}

	public function GetResponseData($action, $params) {
		$data = null;
		if(!strcmp($action, IndicatorServices::GET_BY_ID)) {
			$data = $this->__get_indicator_by_id($params[IndicatorServices::PARAM_ID]);
		} else if(!strcmp($action, IndicatorServices::GET_ALL)) {
			$data = $this->__get_all_indicators();
		} else if(!strcmp($action, IndicatorServices::GET_ALL_VALUE)) {
			$data = $this->__get_all_value_indicators();
		} else if(!strcmp($action, IndicatorServices::GET_ALL_GRAPH)) {
			$data = $this->__get_all_graph_indicators();
		} else if(!strcmp($action, IndicatorServices::GET_ALL_TABLE)) {
			$data = $this->__get_all_table_indicators();
		} else if(!strcmp($action, IndicatorServices::PROCESS_BY_ID)) {
			$data = $this->__process_indicator_by_id($params[IndicatorServices::PARAM_ID]);
		} else if(!strcmp($action, IndicatorServices::PROCESS_ALL)) {
			$data = $this->__process_all_indicators();
		} else if(!strcmp($action, IndicatorServices::PROCESS_ALL_VALUE)) {
			$data = $this->__process_all_value_indicators();
		} else if(!strcmp($action, IndicatorServices::PROCESS_ALL_GRAPH)) {
			$data = $this->__process_all_graph_indicators();
		} else if(!strcmp($action, IndicatorServices::PROCESS_ALL_TABLE)) {
			$data = $this->__process_all_table_indicators();
		} else if(!strcmp($action, IndicatorServices::INSERT_VALUE)) {
			$data = $this->__insert_value_indicator(
				$params[IndicatorServices::PARAM_PROCEDURE], 
				$params[IndicatorServices::PARAM_MODULE],
				$params[IndicatorServices::PARAM_DESCRIPTION],
				$params[IndicatorServices::PARAM_PARAMS],
				$params[IndicatorServices::PARAM_EXPECTED_RESULT],
				$params[IndicatorServices::PARAM_UNIT],
				$params[IndicatorServices::PARAM_EXPECTED_GREATER]);
		} else if(!strcmp($action, IndicatorServices::INSERT_GRAPH)) {
			$data = $this->__insert_graph_indicator(
				$params[IndicatorServices::PARAM_PROCEDURE], 
				$params[IndicatorServices::PARAM_MODULE],
				$params[IndicatorServices::PARAM_DESCRIPTION],
				$params[IndicatorServices::PARAM_PARAMS],
				$params[IndicatorServices::PARAM_GRAPH_TYPE],
				$params[IndicatorServices::PARAM_LEGEND]);
		} else if(!strcmp($action, IndicatorServices::INSERT_TABLE)) {
			$data = $this->__insert_table_indicator(
				$params[IndicatorServices::PARAM_PROCEDURE], 
				$params[IndicatorServices::PARAM_MODULE],
				$params[IndicatorServices::PARAM_DESCRIPTION],
				$params[IndicatorServices::PARAM_PARAMS],
				$params[IndicatorServices::PARAM_LABEL_COLUMN],
				$params[IndicatorServices::PARAM_RESULT_COLUMN]);
		} else if(!strcmp($action, IndicatorServices::UPDATE_VALUE)) {
			$data = $this->__update_value_indicator(
				$params[IndicatorServices::PARAM_ID],
				$params[IndicatorServices::PARAM_PROCEDURE],
				$params[IndicatorServices::PARAM_MODULE],
				$params[IndicatorServices::PARAM_DESCRIPTION],
				$params[IndicatorServices::PARAM_PARAMS],
				$params[IndicatorServices::PARAM_EXPECTED_RESULT],
				$params[IndicatorServices::PARAM_UNIT],
				$params[IndicatorServices::PARAM_EXPECTED_GREATER]);
		} else if(!strcmp($action, IndicatorServices::UPDATE_GRAPH)) {
			$data = $this->__update_value_indicator(
				$params[IndicatorServices::PARAM_ID],
				$params[IndicatorServices::PARAM_PROCEDURE],
				$params[IndicatorServices::PARAM_MODULE],
				$params[IndicatorServices::PARAM_DESCRIPTION],
				$params[IndicatorServices::PARAM_PARAMS],
				$params[IndicatorServices::PARAM_GRAPH_TYPE],
				$params[IndicatorServices::PARAM_LEGEND]);
		} else if(!strcmp($action, IndicatorServices::UPDATE_VALUE)) {
			$data = $this->__update_value_indicator(
				$params[IndicatorServices::PARAM_ID],
				$params[IndicatorServices::PARAM_PROCEDURE],
				$params[IndicatorServices::PARAM_MODULE],
				$params[IndicatorServices::PARAM_DESCRIPTION],
				$params[IndicatorServices::PARAM_PARAMS],
				$params[IndicatorServices::PARAM_LABEL_COLUMN],
				$params[IndicatorServices::PARAM_RESULT_COLUMN]);
		} else if(!strcmp($action, IndicatorServices::DELETE)) {
			$data = $this->__delete_indicator($params[IndicatorServices::PARAM_ID]);
		} else if(!strcmp($action, IndicatorServices::DISABLE)) {
			$data = $this->__disable_indicator($params[IndicatorServices::PARAM_ID]);
		} else if(!strcmp($action, IndicatorServices::ENABLE)) {
			$data = $this->__enable_indicator($params[IndicatorServices::PARAM_ID]);
		}
		return $data;
	}

# PROTECTED & PRIVATE ####################################################

	// -- consult

	private function __get_indicator_by_id($id) {
		// create sql params array
		$sql_params = array(":".IndicatorDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
			array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_ID));
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
		// create ticket var
		$indicator = null;
		if(isset($pdos)) {
			if( ($row = $pdos->fetch()) !== false) {
				switch($row[IndicatorDBObject::COL_TYPE]) {
					case Indicator::VALUE_TYPE:
						$indicator = new IndicatorValue(
							$row[IndicatorDBObject::COL_ID], 
							$row[IndicatorDBObject::COL_PROCEDURE], 
							$row[IndicatorDBObject::COL_MODULE],
							$row[IndicatorDBObject::COL_DESCRIPTION],
							$row[IndicatorDBObject::COL_PARAMS],
							$row[IndicatorDBObject::COL_EXPECTED_RESULT],
							$row[IndicatorDBObject::COL_UNIT],
							$row[IndicatorDBObject::COL_EXPECTED_GREATER]);
						break;
					case Indicator::GRAPH_TYPE:
						$indicator = new IndicatorGraph(
							$row[IndicatorDBObject::COL_ID], 
							$row[IndicatorDBObject::COL_PROCEDURE], 
							$row[IndicatorDBObject::COL_MODULE],
							$row[IndicatorDBObject::COL_DESCRIPTION],
							$row[IndicatorDBObject::COL_PARAMS],
							$row[IndicatorDBObject::COL_GRAPH_TYPE],
							$row[IndicatorDBObject::COL_LEGEND]);
						break;
					case Indicator::TABLE_TYPE:
						$indicator = new IndicatorTable(
							$row[IndicatorDBObject::COL_ID], 
							$row[IndicatorDBObject::COL_PROCEDURE], 
							$row[IndicatorDBObject::COL_MODULE],
							$row[IndicatorDBObject::COL_DESCRIPTION],
							$row[IndicatorDBObject::COL_PARAMS],
							$row[IndicatorDBObject::COL_LABEL_COLUMN],
							$row[IndicatorDBObject::COL_RESULT_COLUMN]);
						break;
				}
			}
		}
		return $indicator;
	}

	private function __get_all_indicators() {
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_COMMENT)->GetSELECTQuery();
		// execute SQL query and save result
		$pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
		// create an empty array for tickets and fill it
		$indicators = array(Indicator::VALUE_TYPE => array(),
							Indicator::GRAPH_TYPE => array(),
							Indicator::TABLE_TYPE => array());
		if(isset($pdos)) {
			while( ($row = $pdos->fetch()) !== false) {
				$indicator = null;
				switch($row[IndicatorDBObject::COL_TYPE]) {
					case Indicator::VALUE_TYPE:
						$indicator = new IndicatorValue(
							$row[IndicatorDBObject::COL_ID], 
							$row[IndicatorDBObject::COL_PROCEDURE], 
							$row[IndicatorDBObject::COL_MODULE],
							$row[IndicatorDBObject::COL_DESCRIPTION],
							$row[IndicatorDBObject::COL_PARAMS],
							$row[IndicatorDBObject::COL_EXPECTED_RESULT],
							$row[IndicatorDBObject::COL_UNIT],
							$row[IndicatorDBObject::COL_EXPECTED_GREATER]);
						break;
					case Indicator::GRAPH_TYPE:
						$indicator = new IndicatorGraph(
							$row[IndicatorDBObject::COL_ID], 
							$row[IndicatorDBObject::COL_PROCEDURE], 
							$row[IndicatorDBObject::COL_MODULE],
							$row[IndicatorDBObject::COL_DESCRIPTION],
							$row[IndicatorDBObject::COL_PARAMS],
							$row[IndicatorDBObject::COL_GRAPH_TYPE],
							$row[IndicatorDBObject::COL_LEGEND]);
						break;
					case Indicator::TABLE_TYPE:
						$indicator = new IndicatorTable(
							$row[IndicatorDBObject::COL_ID], 
							$row[IndicatorDBObject::COL_PROCEDURE], 
							$row[IndicatorDBObject::COL_MODULE],
							$row[IndicatorDBObject::COL_DESCRIPTION],
							$row[IndicatorDBObject::COL_PARAMS],
							$row[IndicatorDBObject::COL_LABEL_COLUMN],
							$row[IndicatorDBObject::COL_RESULT_COLUMN]);
						break;
				}
				array_push($indicators[IndicatorDBObject::COL_TYPE], $indicator);
			}
		}
		return $indicators;
	}

	// -- modify

	private function __insert_value_indicator($procedure, $module, $description, $params, $expectedResult, $unit, $expectedGreater) {
		// create sql params
		$sql_params = array(
			":".IndicatorDBObject::COL_ID => "NULL",
			":".IndicatorDBObject::COL_PROCEDURE		=> $procedure,
			":".IndicatorDBObject::COL_MODULE			=> $module,
			":".IndicatorDBObject::COL_DESCRIPTION		=> $description,
			":".IndicatorDBObject::COL_TYPE				=> Indicator::VALUE_TYPE,
			":".IndicatorDBObject::COL_PARAMS			=> $params,
			":".IndicatorDBObject::COL_EXPECTED_RESULT	=> $expectedResult,
			":".IndicatorDBObject::COL_UNIT				=> $unit,
			":".IndicatorDBObject::COL_EXPECTED_GREATER	=> $expectedGreater,
			":".IndicatorDBObject::COL_GRAPH_TYPE		=> NULL,
			":".IndicatorDBObject::COL_LEGEND			=> NULL,
			":".IndicatorDBObject::COL_LABEL_COLUMN		=> NULL,
			":".IndicatorDBObject::COL_RESULT_COLUMN	=> NULL);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __insert_graph_indicator($procedure, $module, $description, $params, $graphType, $legend) {
		// create sql params
		$sql_params = array(
			":".IndicatorDBObject::COL_ID => "NULL",
			":".IndicatorDBObject::COL_PROCEDURE		=> $procedure,
			":".IndicatorDBObject::COL_MODULE			=> $module,
			":".IndicatorDBObject::COL_DESCRIPTION		=> $description,
			":".IndicatorDBObject::COL_TYPE				=> Indicator::GRAPH_TYPE,
			":".IndicatorDBObject::COL_PARAMS			=> $params,
			":".IndicatorDBObject::COL_EXPECTED_RESULT	=> NULL,
			":".IndicatorDBObject::COL_UNIT				=> NULL,
			":".IndicatorDBObject::COL_EXPECTED_GREATER	=> NULL,
			":".IndicatorDBObject::COL_GRAPH_TYPE		=> $graphType,
			":".IndicatorDBObject::COL_LEGEND			=> $legend,
			":".IndicatorDBObject::COL_LABEL_COLUMN		=> NULL,
			":".IndicatorDBObject::COL_RESULT_COLUMN	=> NULL);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __insert_table_indicator($procedure, $module, $description, $params, $labelColumn, $resultColumn) {
		// create sql params
		$sql_params = array(
			":".IndicatorDBObject::COL_ID => "NULL",
			":".IndicatorDBObject::COL_PROCEDURE		=> $procedure,
			":".IndicatorDBObject::COL_MODULE			=> $module,
			":".IndicatorDBObject::COL_DESCRIPTION		=> $description,
			":".IndicatorDBObject::COL_TYPE				=> Indicator::TABLE_TYPE,
			":".IndicatorDBObject::COL_PARAMS			=> $params,
			":".IndicatorDBObject::COL_EXPECTED_RESULT	=> NULL,
			":".IndicatorDBObject::COL_UNIT				=> NULL,
			":".IndicatorDBObject::COL_EXPECTED_GREATER	=> NULL,
			":".IndicatorDBObject::COL_GRAPH_TYPE		=> NULL,
			":".IndicatorDBObject::COL_LEGEND			=> NULL,
			":".IndicatorDBObject::COL_LABEL_COLUMN		=> $labelColumn,
			":".IndicatorDBObject::COL_RESULT_COLUMN	=> $resultColumn);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetINSERTQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_value_indicator($id, $procedure, $module, $description, $params, $expectedResult, $unit, $expectedGreater) {
		// create sql params
		$sql_params = array(
			":".IndicatorDBObject::COL_ID => $id,
			":".IndicatorDBObject::COL_PROCEDURE		=> $procedure,
			":".IndicatorDBObject::COL_MODULE			=> $module,
			":".IndicatorDBObject::COL_DESCRIPTION		=> $description,
			":".IndicatorDBObject::COL_TYPE				=> Indicator::VALUE_TYPE,
			":".IndicatorDBObject::COL_PARAMS			=> $params,
			":".IndicatorDBObject::COL_EXPECTED_RESULT	=> $expectedResult,
			":".IndicatorDBObject::COL_UNIT				=> $unit,
			":".IndicatorDBObject::COL_EXPECTED_GREATER	=> $expectedGreater,
			":".IndicatorDBObject::COL_GRAPH_TYPE		=> NULL,
			":".IndicatorDBObject::COL_LEGEND			=> NULL,
			":".IndicatorDBObject::COL_LABEL_COLUMN		=> NULL,
			":".IndicatorDBObject::COL_RESULT_COLUMN	=> NULL);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_graph_indicator($id, $procedure, $module, $description, $params, $graphType, $legend) {
		// create sql params
		$sql_params = array(
			":".IndicatorDBObject::COL_ID => $id,
			":".IndicatorDBObject::COL_PROCEDURE		=> $procedure,
			":".IndicatorDBObject::COL_MODULE			=> $module,
			":".IndicatorDBObject::COL_DESCRIPTION		=> $description,
			":".IndicatorDBObject::COL_TYPE				=> Indicator::GRAPH_TYPE,
			":".IndicatorDBObject::COL_PARAMS			=> $params,
			":".IndicatorDBObject::COL_EXPECTED_RESULT	=> NULL,
			":".IndicatorDBObject::COL_UNIT				=> NULL,
			":".IndicatorDBObject::COL_EXPECTED_GREATER	=> NULL,
			":".IndicatorDBObject::COL_GRAPH_TYPE		=> $graphType,
			":".IndicatorDBObject::COL_LEGEND			=> $legend,
			":".IndicatorDBObject::COL_LABEL_COLUMN		=> NULL,
			":".IndicatorDBObject::COL_RESULT_COLUMN	=> NULL);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __update_table_indicator($id, $procedure, $module, $description, $params, $labelColumn, $resultColumn) {
		// create sql params
		$sql_params = array(
			":".IndicatorDBObject::COL_ID => $id,
			":".IndicatorDBObject::COL_PROCEDURE		=> $procedure,
			":".IndicatorDBObject::COL_MODULE			=> $module,
			":".IndicatorDBObject::COL_DESCRIPTION		=> $description,
			":".IndicatorDBObject::COL_TYPE				=> Indicator::TABLE_TYPE,
			":".IndicatorDBObject::COL_PARAMS			=> $params,
			":".IndicatorDBObject::COL_EXPECTED_RESULT	=> NULL,
			":".IndicatorDBObject::COL_UNIT				=> NULL,
			":".IndicatorDBObject::COL_EXPECTED_GREATER	=> NULL,
			":".IndicatorDBObject::COL_GRAPH_TYPE		=> NULL,
			":".IndicatorDBObject::COL_LEGEND			=> NULL,
			":".IndicatorDBObject::COL_LABEL_COLUMN		=> $labelColumn,
			":".IndicatorDBObject::COL_RESULT_COLUMN	=> $resultColumn);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetUPDATEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __delete_indicator($id) {
		// create sql params
		$sql_params = array(":".IndicatorDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetDELETEQuery();
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __disable_indicator($id) {
		// create sql params
		$sql_params = array(":".IndicatorDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetDISABLEQuery(IndicatorDBObject::TABL_INDICATOR_DISABLED);
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

	private function __enable_indicator($id) {
		// create sql params
		$sql_params = array(":".IndicatorDBObject::COL_ID => $id);
		// create sql request
		$sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetRESTOREQuery(IndicatorDBObject::TABL_INDICATOR_DISABLED);
		// execute query
		return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
	}

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

	public function ResetStaticData() {
		// no static data for this module
	}

}

/**
 *	@brief Indicator object interface
 */
class IndicatorDBObject extends AbstractDBObject {

	// -- consts
	// --- object name
	const OBJ_NAME = "indicator";
	// --- tables
	const TABL_INDICATOR = "dol_indicator";
	const TABL_INDICATOR_DISABLED = "dol_indicator_disabled";
	// --- columns
	const COL_ID = "id";
	const COL_PROCEDURE = "procedure";
	const COL_MODULE = "module";
	const COL_DESCRIPTION = "description";
	const COL_TYPE = "type";
	const COL_PARAMS = "params";
	const COL_EXPECTED_RESULT = "expected_result";
	const COL_UNIT = "unit";
	const COL_EXPECTED_GREATER = "expected_greater";
	const COL_GRAPH_TYPE = "graph_type";
	const COL_LEGEND = "legend";
	const COL_LABEL_COLUMN = "label_column";
	const COL_RESULT_COLUMN = "result_column";
	// -- attributes

	// -- functions

	public function __construct($module) {
		// -- construct parent
		parent::__construct($module, IndicatorDBObject::OBJ_NAME);
		// -- create tables
		// --- dol_indicator table
		$dol_indicator = new DBTable(IndicatorDBObject::TABL_INDICATOR);
		$dol_indicator
			->AddColumn(IndicatorDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
			->AddColumn(IndicatorDBObject::COL_PROCEDURE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_MODULE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_DESCRIPTION, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_TYPE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_PARAMS, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_EXPECTED_RESULT, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_UNIT, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_EXPECTED_GREATER, DBTable::DT_INT, 1, false) // boolean
			->AddColumn(IndicatorDBObject::COL_GRAPH_TYPE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_LEGEND, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_LABEL_COLUMN, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_RESULT_COLUMN, DBTable::DT_VARCHAR, 255, false);

		$dol_indicator_disabled = new DBTable(IndicatorDBObject::TABL_INDICATOR_DISABLED);
		$dol_indicator_disabled
			->AddColumn(IndicatorDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
			->AddColumn(IndicatorDBObject::COL_PROCEDURE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_MODULE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_DESCRIPTION, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_TYPE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_PARAMS, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_EXPECTED_RESULT, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_UNIT, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_EXPECTED_GREATER, DBTable::DT_INT, 1, false) // boolean
			->AddColumn(IndicatorDBObject::COL_GRAPH_TYPE, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_LEGEND, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_LABEL_COLUMN, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(IndicatorDBObject::COL_RESULT_COLUMN, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(DBTable::COL_DISABLE_TSMP, DBTable::DT_VARCHAR, 255, false)
			->AddColumn(DBTable::COL_RESTORE_TSMP, DBTable::DT_VARCHAR, 255, false);
		// -- add tables
		parent::addTable($dol_indicator);
		parent::addTable($dol_indicator_disabled);
	}

	/**
	 *	@brief Returns all services associated with this object
	 */
	public function GetServices($currentUser) {
		return new IndicatorServices($currentUser, $this, $this->getDBConnection());
	}

	/**
	 *	Initialize static data
	 */
	public function ResetStaticData() {
		$services = new IndicatorServices(null, $this, $this->getDBConnection());
		$services->ResetStaticData();
	}

}