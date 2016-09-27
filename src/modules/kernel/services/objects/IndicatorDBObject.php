<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief The Indicator class
 */
abstract class Indicator implements \JsonSerializable
{

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
    protected $results = array();

    /**
     * @brief Constructs an indicator
     */
    public function __construct($id, $procedure, $module, $description, $type, $params = "")
    {
        $this->id = intval($id);
        $this->procedure = $procedure;
        $this->module = $module;
        $this->description = $description;
        $this->type = $type;
        if ($params == "") {
            $this->params = array();
        } else {
            $this->params = explode(";", $params);
        }
    }

    public function jsonSerialize()
    {
        return [
            IndicatorDBObject::COL_ID => $this->id,
            IndicatorDBObject::COL_PROCEDURE => $this->procedure,
            IndicatorDBObject::COL_MODULE => $this->module,
            IndicatorDBObject::COL_DESCRIPTION => $this->description,
            IndicatorDBObject::COL_TYPE => $this->type,
            IndicatorDBObject::COL_PARAMS => $this->params,
            IndicatorDBObject::COL_RESULTS => $this->results
        ];
    }

    /**
     * @brief Returns indicator id
     * @return string
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @brief Returns indicator procedure
     * @return string
     */
    public function GetProcedure()
    {
        return $this->procedure;
    }

    /**
     * @brief Returns indicator module
     * @return string
     */
    public function GetModule()
    {
        return $this->module;
    }

    /**
     * @brief Returns indicator description
     * @return string
     */
    public function GetDescription()
    {
        return $this->description;
    }

    /**
     * @brief Returns indicator type
     * @return string
     */
    public function GetType()
    {
        return $this->type;
    }

    /**
     * @brief Returns indicator params
     * @return string
     */
    public function GetParams()
    {
        return $this->params;
    }

    /**
     * @brief Returns indicator results
     * @return string
     */
    public function GetResults()
    {
        return $this->results;
    }

    /**
     * @brief Add a result row
     * @return Indicator
     */
    public function AddResult($result)
    {
        foreach ($result as $key => $r) {
            if (!isset($this->results[$key])) {
                $this->results[$key] = array();
            }
            array_push($this->results[$key], $r);
        }
        return $this;
    }

    /**
     * @brief Unset procedure param for security
     * @return Indicator
     */
    public function UnsetProcedure()
    {
        $this->procedure = null;
        return $this;
    }

    /**
     * @brief Unset id param for security
     * @return Indicator
     */
    public function UnsetId()
    {
        $this->id = null;
        return $this;
    }

    /**
     * @brief Unset params param for security
     * @return Indicator
     */
    public function UnsetParams()
    {
        $this->params = null;
        return $this;
    }
}

class IndicatorValue extends Indicator
{
    // -- consts

    // -- attributes
    // --- persistent
    protected $expected_result = null;
    protected $unit = null;
    protected $expected_greater = null;

    /**
     * @brief Constructs an indicator with value
     */
    public function __construct($id, $procedure, $module, $description, $params = "", $expected_result = "", $unit = "", $expected_greater = true)
    {
        parent::__construct($id, $procedure, $module, $description, Indicator::VALUE_TYPE, $params);
        $this->expected_result = $expected_result;
        $this->unit = $unit;
        $this->expected_greater = boolval($expected_greater);
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(),
            [
                IndicatorDBObject::COL_EXPECTED_RESULT => $this->expected_result,
                IndicatorDBObject::COL_UNIT => $this->unit,
                IndicatorDBObject::COL_EXPECTED_GREATER => $this->expected_greater
            ]
        );
    }

    /**
     * @brief Returns indicator expected result
     * @return string
     */
    public function GetExpectedResult()
    {
        return $this->expected_result;
    }

    /**
     * @brief Returns indicator unit
     * @return string
     */
    public function GetUnit()
    {
        return $this->unit;
    }

    /**
     * @brief Returns wether the indicator should be greater than expected result or not
     * @return string
     */
    public function GetExpectedGreater()
    {
        return $this->expected_greater;
    }
}

class IndicatorGraph extends Indicator
{
    // -- consts

    // -- attributes
    // --- persistent
    protected $graph_type = null;
    protected $legend = array();
    protected $graph_name = null;

    /**
     * @brief Constructs an indicator with graph
     */
    public function __construct($id, $procedure, $module, $description, $params = "", $graphType = "scatter", $legend = "", $graphName = "")
    {
        parent::__construct($id, $procedure, $module, $description, Indicator::GRAPH_TYPE, $params);
        $this->graph_type = $graphType;
        if ($legend == "") {
            $this->legend = array();
        } else {
            $this->legend = explode(";", $legend);
        }
        $this->graph_name = $graphName;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(),
            [
                IndicatorDBObject::COL_GRAPH_TYPE => $this->graph_type,
                IndicatorDBObject::COL_LEGEND => $this->legend,
                IndicatorDBObject::COL_GRAPH_NAME => $this->graph_name
            ]
        );
    }

    /**
     * @brief Returns indicator plotly graph type
     * @return string
     */
    public function GetGraphType()
    {
        return $this->graph_type;
    }

    /**
     * @brief Returns indicator legend
     * @return array
     */
    public function GetLegend()
    {
        return $this->legend;
    }

    /**
     * @brief Returns indicator graph div name
     * @return string
     */
    public function GetGraphName()
    {
        return $this->graph_name;
    }
}

class IndicatorTable extends Indicator
{
    // -- consts

    // -- attributes
    // --- persistent
    protected $label_column = null;
    protected $result_column = null;

    /**
     * @brief Constructs an indicator with graph
     */
    public function __construct($id, $procedure, $module, $description, $params = "", $labelColumn = "", $resultColumn = "")
    {
        parent::__construct($id, $procedure, $module, $description, Indicator::TABLE_TYPE, $params);
        $this->label_column = $labelColumn;
        $this->result_column = $resultColumn;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(),
            [
                IndicatorDBObject::COL_LABEL_COLUMN => $this->label_column,
                IndicatorDBObject::COL_RESULT_COLUMN => $this->result_column
            ]
        );
    }

    /**
     * @brief Returns indicator plotly graph type
     * @return string
     */
    public function GetGraphType()
    {
        return $this->graph_type;
    }

    /**
     * @brief Returns indicator legend
     * @return array
     */
    public function GetLegend()
    {
        return $this->legend;
    }
}

class IndicatorServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_DESCRIPTION = "description";
    const PARAM_MODULE = "module";
    const PARAM_PROCEDURE = "procedure";
    const PARAM_TYPE = "type";
    const PARAM_PARAMS = "params";
    const PARAM_EXPECTED_RESULT = "expectedResult";
    const PARAM_UNIT = "unit";
    const PARAM_EXPECTED_GREATER = "expectedGreater";
    const PARAM_GRAPH_TYPE = "graphType";
    const PARAM_LEGEND = "legend";
    const PARAM_GRAPH_NAME = "graphName";
    const PARAM_LABEL_COLUMN = "labelColumn";
    const PARAM_RESULT_COLUMN = "resultColumn";
    // --- internal services (actions)
    const GET_BY_ID = "byid";
    const GET_ALL = "all";
    const GET_ALL_BY_MODULE = "allbymod";
    const GET_ALL_VALUE = "allval";
    const GET_ALL_TABLE = "alltab";
    const GET_ALL_GRAPH = "allgra";
    const GET_ALL_VALUE_BY_MODULE = "allvalbymod";
    const GET_ALL_TABLE_BY_MODULE = "alltabbymod";
    const GET_ALL_GRAPH_BY_MODULE = "allgrabymod";
    const PROCESS_BY_ID = "procbyid";
    const PROCESS_ALL = "procall";
    const PROCESS_ALL_BY_MODULE = "procallbymod";
    const PROCESS_ALL_VALUE = "procval";
    const PROCESS_ALL_TABLE = "proctab";
    const PROCESS_ALL_GRAPH = "procgra";
    const PROCESS_ALL_VALUE_BY_MODULE = "procvalbymod";
    const PROCESS_ALL_TABLE_BY_MODULE = "proctabbymod";
    const PROCESS_ALL_GRAPH_BY_MODULE = "procgrabymod";
    const INSERT_VALUE = "insval";
    const INSERT_TABLE = "instab";
    const INSERT_GRAPH = "insgra";
    const UPDATE_VALUE = "updval";
    const UPDATE_TABLE = "updtab";
    const UPDATE_GRAPH = "updgra";
    const DISABLE = "disable";
    const ENABLE = "enable";
    const DELETE = "delete";
    // -- functions

    // -- construct
    public function __construct($currentUser, $dbObject, $dbConnection)
    {
        parent::__construct($currentUser, $dbObject, $dbConnection);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        if (!strcmp($action, IndicatorServices::GET_BY_ID)) {
            $data = $this->__get_indicator_by_id($params[IndicatorServices::PARAM_ID]);
        } else if (!strcmp($action, IndicatorServices::GET_ALL)) {
            $data = $this->__get_all_indicators();
        } else if (!strcmp($action, IndicatorServices::GET_ALL_BY_MODULE)) {
            $data = $this->__get_all_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::GET_ALL_VALUE)) {
            $data = $this->__get_all_value_indicators();
        } else if (!strcmp($action, IndicatorServices::GET_ALL_GRAPH)) {
            $data = $this->__get_all_graph_indicators();
        } else if (!strcmp($action, IndicatorServices::GET_ALL_TABLE)) {
            $data = $this->__get_all_table_indicators();
        } else if (!strcmp($action, IndicatorServices::GET_ALL_VALUE_BY_MODULE)) {
            $data = $this->__get_all_value_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::GET_ALL_GRAPH_BY_MODULE)) {
            $data = $this->__get_all_graph_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::GET_ALL_TABLE_BY_MODULE)) {
            $data = $this->__get_all_table_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::PROCESS_BY_ID)) {
            $data = $this->__process_indicator_by_id($params[IndicatorServices::PARAM_ID]);
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL)) {
            $data = $this->__process_all_indicators();
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_BY_MODULE)) {
            $data = $this->__process_all_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_VALUE)) {
            $data = $this->__process_all_value_indicators();
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_GRAPH)) {
            $data = $this->__process_all_graph_indicators();
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_TABLE)) {
            $data = $this->__process_all_table_indicators();
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_VALUE_BY_MODULE)) {
            $data = $this->__process_all_value_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_GRAPH_BY_MODULE)) {
            $data = $this->__process_all_graph_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::PROCESS_ALL_TABLE_BY_MODULE)) {
            $data = $this->__process_all_table_indicators_by_module($params[IndicatorServices::PARAM_MODULE]);
        } else if (!strcmp($action, IndicatorServices::INSERT_VALUE)) {
            $data = $this->__insert_value_indicator(
                $params[IndicatorServices::PARAM_PROCEDURE],
                $params[IndicatorServices::PARAM_MODULE],
                $params[IndicatorServices::PARAM_DESCRIPTION],
                $params[IndicatorServices::PARAM_PARAMS],
                $params[IndicatorServices::PARAM_EXPECTED_RESULT],
                $params[IndicatorServices::PARAM_UNIT],
                $params[IndicatorServices::PARAM_EXPECTED_GREATER]);
        } else if (!strcmp($action, IndicatorServices::INSERT_GRAPH)) {
            $data = $this->__insert_graph_indicator(
                $params[IndicatorServices::PARAM_PROCEDURE],
                $params[IndicatorServices::PARAM_MODULE],
                $params[IndicatorServices::PARAM_DESCRIPTION],
                $params[IndicatorServices::PARAM_PARAMS],
                $params[IndicatorServices::PARAM_GRAPH_TYPE],
                $params[IndicatorServices::PARAM_LEGEND],
                $params[IndicatorServices::PARAM_GRAPH_NAME]);
        } else if (!strcmp($action, IndicatorServices::INSERT_TABLE)) {
            $data = $this->__insert_table_indicator(
                $params[IndicatorServices::PARAM_PROCEDURE],
                $params[IndicatorServices::PARAM_MODULE],
                $params[IndicatorServices::PARAM_DESCRIPTION],
                $params[IndicatorServices::PARAM_PARAMS],
                $params[IndicatorServices::PARAM_LABEL_COLUMN],
                $params[IndicatorServices::PARAM_RESULT_COLUMN]);
        } else if (!strcmp($action, IndicatorServices::UPDATE_VALUE)) {
            $data = $this->__update_value_indicator(
                $params[IndicatorServices::PARAM_ID],
                $params[IndicatorServices::PARAM_PROCEDURE],
                $params[IndicatorServices::PARAM_MODULE],
                $params[IndicatorServices::PARAM_DESCRIPTION],
                $params[IndicatorServices::PARAM_PARAMS],
                $params[IndicatorServices::PARAM_EXPECTED_RESULT],
                $params[IndicatorServices::PARAM_UNIT],
                $params[IndicatorServices::PARAM_EXPECTED_GREATER]);
        } else if (!strcmp($action, IndicatorServices::UPDATE_GRAPH)) {
            $data = $this->__update_value_indicator(
                $params[IndicatorServices::PARAM_ID],
                $params[IndicatorServices::PARAM_PROCEDURE],
                $params[IndicatorServices::PARAM_MODULE],
                $params[IndicatorServices::PARAM_DESCRIPTION],
                $params[IndicatorServices::PARAM_PARAMS],
                $params[IndicatorServices::PARAM_GRAPH_TYPE],
                $params[IndicatorServices::PARAM_LEGEND],
                $params[IndicatorServices::PARAM_GRAPH_NAME]);
        } else if (!strcmp($action, IndicatorServices::UPDATE_VALUE)) {
            $data = $this->__update_value_indicator(
                $params[IndicatorServices::PARAM_ID],
                $params[IndicatorServices::PARAM_PROCEDURE],
                $params[IndicatorServices::PARAM_MODULE],
                $params[IndicatorServices::PARAM_DESCRIPTION],
                $params[IndicatorServices::PARAM_PARAMS],
                $params[IndicatorServices::PARAM_LABEL_COLUMN],
                $params[IndicatorServices::PARAM_RESULT_COLUMN]);
        } else if (!strcmp($action, IndicatorServices::DELETE)) {
            $data = $this->__delete_indicator($params[IndicatorServices::PARAM_ID]);
        } else if (!strcmp($action, IndicatorServices::DISABLE)) {
            $data = $this->__disable_indicator($params[IndicatorServices::PARAM_ID]);
        } else if (!strcmp($action, IndicatorServices::ENABLE)) {
            $data = $this->__enable_indicator($params[IndicatorServices::PARAM_ID]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult

    private function __get_indicator_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create ticket var
        $indicator = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                switch ($row[IndicatorDBObject::COL_TYPE]) {
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
                            $row[IndicatorDBObject::COL_LEGEND],
                            $row[IndicatorDBObject::COL_GRAPH_NAME]);
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

    private function __get_all_indicators()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for tickets and fill it
        $indicators = array(Indicator::VALUE_TYPE => array(),
            Indicator::GRAPH_TYPE => array(),
            Indicator::TABLE_TYPE => array());
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                $indicator = null;
                switch ($row[IndicatorDBObject::COL_TYPE]) {
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
                            $row[IndicatorDBObject::COL_LEGEND],
                            $row[IndicatorDBObject::COL_GRAPH_NAME]);
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
                array_push($indicators[$indicator->GetType()], $indicator);
            }
        }
        return $indicators;
    }

    private function __process_all_indicators()
    {
        $indicators = $this->__get_all_indicators();
        foreach ($indicators as $type => $indics) {
            foreach ($indics as $indicator) {
                $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
                $result = parent::getDBConnection()->RawQuery($sql);
                if ($result != null && !empty($result)) {
                    foreach ($result as $r) {
                        $indicator->AddResult($r);
                    }
                    $indicator
                        ->UnsetParams()
                        ->UnsetId()
                        ->UnsetProcedure();
                }
            }
        }
        return $indicators;
    }

    private function __get_all_indicators_by_module($module)
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_MODULE => $module);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_MODULE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create an empty array for tickets and fill it
        $indicators = array(Indicator::VALUE_TYPE => array(),
            Indicator::GRAPH_TYPE => array(),
            Indicator::TABLE_TYPE => array());
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                $indicator = null;
                switch ($row[IndicatorDBObject::COL_TYPE]) {
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
                            $row[IndicatorDBObject::COL_LEGEND],
                            $row[IndicatorDBObject::COL_GRAPH_NAME]);
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
                array_push($indicators[$indicator->GetType()], $indicator);
            }
        }
        return $indicators;
    }

    private function __process_all_indicators_by_module($module)
    {
        $indicators = $this->__get_all_indicators_by_module($module);
        foreach ($indicators as $type => $indics) {
            foreach ($indics as $indicator) {
                $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
                $result = parent::getDBConnection()->RawQuery($sql);
                if ($result != null && !empty($result)) {
                    foreach ($result as $r) {
                        $indicator->AddResult($r);
                    }
                    $indicator
                        ->UnsetParams()
                        ->UnsetId()
                        ->UnsetProcedure();
                }
            }
        }
        return $indicators;
    }

    private function __get_all_value_indicators()
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_TYPE => Indicator::VALUE_TYPE);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_TYPE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        $indicators = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($indicators, new IndicatorValue(
                        $row[IndicatorDBObject::COL_ID],
                        $row[IndicatorDBObject::COL_PROCEDURE],
                        $row[IndicatorDBObject::COL_MODULE],
                        $row[IndicatorDBObject::COL_DESCRIPTION],
                        $row[IndicatorDBObject::COL_PARAMS],
                        $row[IndicatorDBObject::COL_EXPECTED_RESULT],
                        $row[IndicatorDBObject::COL_UNIT],
                        $row[IndicatorDBObject::COL_EXPECTED_GREATER])
                );
            }
        }
        return $indicators;
    }

    private function __process_all_value_indicators()
    {
        $indicators = $this->__get_all_value_indicators();
        foreach ($indicators as $indicator) {
            $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
            $result = parent::getDBConnection()->RawQuery($sql);
            if ($result != null && !empty($result)) {
                foreach ($result as $r) {
                    $indicator->AddResult($r);
                }
                $indicator
                    ->UnsetParams()
                    ->UnsetId()
                    ->UnsetProcedure();
            }
        }
        return $indicators;
    }

    private function __get_all_graph_indicators()
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_TYPE => Indicator::GRAPH_TYPE);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_TYPE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        $indicators = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($indicators, new IndicatorGraph(
                        $row[IndicatorDBObject::COL_ID],
                        $row[IndicatorDBObject::COL_PROCEDURE],
                        $row[IndicatorDBObject::COL_MODULE],
                        $row[IndicatorDBObject::COL_DESCRIPTION],
                        $row[IndicatorDBObject::COL_PARAMS],
                        $row[IndicatorDBObject::COL_GRAPH_TYPE],
                        $row[IndicatorDBObject::COL_LEGEND],
                        $row[IndicatorDBObject::COL_GRAPH_NAME])
                );
            }
        }
        return $indicators;
    }

    private function __process_all_graph_indicators()
    {
        $indicators = $this->__get_all_graph_indicators();
        foreach ($indicators as $indicator) {
            $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
            $result = parent::getDBConnection()->RawQuery($sql);
            if ($result != null && !empty($result)) {
                foreach ($result as $r) {
                    $indicator->AddResult($r);
                }
                $indicator
                    ->UnsetParams()
                    ->UnsetId()
                    ->UnsetProcedure();
            }
        }
        return $indicators;
    }

    private function __get_all_table_indicators()
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_TYPE => Indicator::TABLE_TYPE);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_TYPE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        $indicators = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($indicators, new IndicatorTable(
                        $row[IndicatorDBObject::COL_ID],
                        $row[IndicatorDBObject::COL_PROCEDURE],
                        $row[IndicatorDBObject::COL_MODULE],
                        $row[IndicatorDBObject::COL_DESCRIPTION],
                        $row[IndicatorDBObject::COL_PARAMS],
                        $row[IndicatorDBObject::COL_LABEL_COLUMN],
                        $row[IndicatorDBObject::COL_RESULT_COLUMN])
                );
            }
        }
        return $indicators;
    }

    private function __process_all_table_indicators()
    {
        $indicators = $this->__get_all_table_indicators();
        foreach ($indicators as $indicator) {
            $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
            $result = parent::getDBConnection()->RawQuery($sql);
            if ($result != null && !empty($result)) {
                foreach ($result as $r) {
                    $indicator->AddResult($r);
                }
                $indicator
                    ->UnsetParams()
                    ->UnsetId()
                    ->UnsetProcedure();
            }
        }
        return $indicators;
    }

    private function __get_all_value_indicators_by_module($module)
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::VALUE_TYPE);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_MODULE, IndicatorDBObject::COL_TYPE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        $indicators = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($indicators, new IndicatorValue(
                        $row[IndicatorDBObject::COL_ID],
                        $row[IndicatorDBObject::COL_PROCEDURE],
                        $row[IndicatorDBObject::COL_MODULE],
                        $row[IndicatorDBObject::COL_DESCRIPTION],
                        $row[IndicatorDBObject::COL_PARAMS],
                        $row[IndicatorDBObject::COL_EXPECTED_RESULT],
                        $row[IndicatorDBObject::COL_UNIT],
                        $row[IndicatorDBObject::COL_EXPECTED_GREATER])
                );
            }
        }
        return $indicators;
    }

    private function __process_all_value_indicators_by_module($module)
    {
        $indicators = $this->__get_all_value_indicators_by_module($module);
        foreach ($indicators as $indicator) {
            $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
            $result = parent::getDBConnection()->RawQuery($sql);
            if ($result != null && !empty($result)) {
                foreach ($result as $r) {
                    $indicator->AddResult($r);
                }
                $indicator
                    ->UnsetParams()
                    ->UnsetId()
                    ->UnsetProcedure();
            }
        }
        return $indicators;
    }

    private function __get_all_graph_indicators_by_module($module)
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::GRAPH_TYPE);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_MODULE, IndicatorDBObject::COL_TYPE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        $indicators = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($indicators, new IndicatorGraph(
                        $row[IndicatorDBObject::COL_ID],
                        $row[IndicatorDBObject::COL_PROCEDURE],
                        $row[IndicatorDBObject::COL_MODULE],
                        $row[IndicatorDBObject::COL_DESCRIPTION],
                        $row[IndicatorDBObject::COL_PARAMS],
                        $row[IndicatorDBObject::COL_GRAPH_TYPE],
                        $row[IndicatorDBObject::COL_LEGEND],
                        $row[IndicatorDBObject::COL_GRAPH_NAME])
                );
            }
        }
        return $indicators;
    }

    private function __process_all_graph_indicators_by_module($module)
    {
        $indicators = $this->__get_all_graph_indicators_by_module($module);
        foreach ($indicators as $indicator) {
            $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
            $result = parent::getDBConnection()->RawQuery($sql);
            if ($result != null && !empty($result)) {
                foreach ($result as $r) {
                    $indicator->AddResult($r);
                }
                $indicator
                    ->UnsetParams()
                    ->UnsetId()
                    ->UnsetProcedure();
            }
        }
        return $indicators;
    }

    private function __get_all_table_indicators_by_module($module)
    {
        // create sql params array
        $sql_params = array(":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::TABLE_TYPE);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IndicatorDBObject::COL_MODULE, IndicatorDBObject::COL_TYPE));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        $indicators = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($indicators, new IndicatorTable(
                        $row[IndicatorDBObject::COL_ID],
                        $row[IndicatorDBObject::COL_PROCEDURE],
                        $row[IndicatorDBObject::COL_MODULE],
                        $row[IndicatorDBObject::COL_DESCRIPTION],
                        $row[IndicatorDBObject::COL_PARAMS],
                        $row[IndicatorDBObject::COL_LABEL_COLUMN],
                        $row[IndicatorDBObject::COL_RESULT_COLUMN])
                );
            }
        }
        return $indicators;
    }

    private function __process_all_table_indicators_by_module($module)
    {
        $indicators = $this->__get_all_table_indicators_by_module($module);
        foreach ($indicators as $indicator) {
            $sql = DBProcedure::GetCALLQueryByName($indicator->GetProcedure());
            $result = parent::getDBConnection()->RawQuery($sql);
            if ($result != null && !empty($result)) {
                foreach ($result as $r) {
                    $indicator->AddResult($r);
                }
                $indicator
                    ->UnsetParams()
                    ->UnsetId()
                    ->UnsetProcedure();
            }
        }
        return $indicators;
    }

    // -- modify

    private function __insert_value_indicator($procedure, $module, $description, $params, $expectedResult, $unit, $expectedGreater)
    {
        // create sql params
        $sql_params = array(
            ":" . IndicatorDBObject::COL_ID => null,
            ":" . IndicatorDBObject::COL_PROCEDURE => $procedure,
            ":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_DESCRIPTION => $description,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::VALUE_TYPE,
            ":" . IndicatorDBObject::COL_PARAMS => $params,
            ":" . IndicatorDBObject::COL_EXPECTED_RESULT => $expectedResult,
            ":" . IndicatorDBObject::COL_UNIT => $unit,
            ":" . IndicatorDBObject::COL_EXPECTED_GREATER => $expectedGreater,
            ":" . IndicatorDBObject::COL_GRAPH_TYPE => NULL,
            ":" . IndicatorDBObject::COL_LEGEND => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_NAME => NULL,
            ":" . IndicatorDBObject::COL_LABEL_COLUMN => NULL,
            ":" . IndicatorDBObject::COL_RESULT_COLUMN => NULL);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __insert_graph_indicator($procedure, $module, $description, $params, $graphType, $legend, $graphName)
    {
        // create sql params
        $sql_params = array(
            ":" . IndicatorDBObject::COL_ID => null,
            ":" . IndicatorDBObject::COL_PROCEDURE => $procedure,
            ":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_DESCRIPTION => $description,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::GRAPH_TYPE,
            ":" . IndicatorDBObject::COL_PARAMS => $params,
            ":" . IndicatorDBObject::COL_EXPECTED_RESULT => NULL,
            ":" . IndicatorDBObject::COL_UNIT => NULL,
            ":" . IndicatorDBObject::COL_EXPECTED_GREATER => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_TYPE => $graphType,
            ":" . IndicatorDBObject::COL_LEGEND => $legend,
            ":" . IndicatorDBObject::COL_GRAPH_NAME => $graphName,
            ":" . IndicatorDBObject::COL_LABEL_COLUMN => NULL,
            ":" . IndicatorDBObject::COL_RESULT_COLUMN => NULL);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __insert_table_indicator($procedure, $module, $description, $params, $labelColumn, $resultColumn)
    {
        // create sql params
        $sql_params = array(
            ":" . IndicatorDBObject::COL_ID => null,
            ":" . IndicatorDBObject::COL_PROCEDURE => $procedure,
            ":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_DESCRIPTION => $description,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::TABLE_TYPE,
            ":" . IndicatorDBObject::COL_PARAMS => $params,
            ":" . IndicatorDBObject::COL_EXPECTED_RESULT => NULL,
            ":" . IndicatorDBObject::COL_UNIT => NULL,
            ":" . IndicatorDBObject::COL_EXPECTED_GREATER => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_TYPE => NULL,
            ":" . IndicatorDBObject::COL_LEGEND => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_NAME => NULL,
            ":" . IndicatorDBObject::COL_LABEL_COLUMN => $labelColumn,
            ":" . IndicatorDBObject::COL_RESULT_COLUMN => $resultColumn);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_value_indicator($id, $procedure, $module, $description, $params, $expectedResult, $unit, $expectedGreater)
    {
        // create sql params
        $sql_params = array(
            ":" . IndicatorDBObject::COL_ID => $id,
            ":" . IndicatorDBObject::COL_PROCEDURE => $procedure,
            ":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_DESCRIPTION => $description,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::VALUE_TYPE,
            ":" . IndicatorDBObject::COL_PARAMS => $params,
            ":" . IndicatorDBObject::COL_EXPECTED_RESULT => $expectedResult,
            ":" . IndicatorDBObject::COL_UNIT => $unit,
            ":" . IndicatorDBObject::COL_EXPECTED_GREATER => $expectedGreater,
            ":" . IndicatorDBObject::COL_GRAPH_TYPE => NULL,
            ":" . IndicatorDBObject::COL_LEGEND => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_NAME => NULL,
            ":" . IndicatorDBObject::COL_LABEL_COLUMN => NULL,
            ":" . IndicatorDBObject::COL_RESULT_COLUMN => NULL);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetUPDATEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_graph_indicator($id, $procedure, $module, $description, $params, $graphType, $legend, $graphName)
    {
        // create sql params
        $sql_params = array(
            ":" . IndicatorDBObject::COL_ID => $id,
            ":" . IndicatorDBObject::COL_PROCEDURE => $procedure,
            ":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_DESCRIPTION => $description,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::GRAPH_TYPE,
            ":" . IndicatorDBObject::COL_PARAMS => $params,
            ":" . IndicatorDBObject::COL_EXPECTED_RESULT => NULL,
            ":" . IndicatorDBObject::COL_UNIT => NULL,
            ":" . IndicatorDBObject::COL_EXPECTED_GREATER => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_TYPE => $graphType,
            ":" . IndicatorDBObject::COL_LEGEND => $legend,
            ":" . IndicatorDBObject::COL_GRAPH_NAME => $graphName,
            ":" . IndicatorDBObject::COL_LABEL_COLUMN => NULL,
            ":" . IndicatorDBObject::COL_RESULT_COLUMN => NULL);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetUPDATEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_table_indicator($id, $procedure, $module, $description, $params, $labelColumn, $resultColumn)
    {
        // create sql params
        $sql_params = array(
            ":" . IndicatorDBObject::COL_ID => $id,
            ":" . IndicatorDBObject::COL_PROCEDURE => $procedure,
            ":" . IndicatorDBObject::COL_MODULE => $module,
            ":" . IndicatorDBObject::COL_DESCRIPTION => $description,
            ":" . IndicatorDBObject::COL_TYPE => Indicator::TABLE_TYPE,
            ":" . IndicatorDBObject::COL_PARAMS => $params,
            ":" . IndicatorDBObject::COL_EXPECTED_RESULT => NULL,
            ":" . IndicatorDBObject::COL_UNIT => NULL,
            ":" . IndicatorDBObject::COL_EXPECTED_GREATER => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_TYPE => NULL,
            ":" . IndicatorDBObject::COL_LEGEND => NULL,
            ":" . IndicatorDBObject::COL_GRAPH_NAME => NULL,
            ":" . IndicatorDBObject::COL_LABEL_COLUMN => $labelColumn,
            ":" . IndicatorDBObject::COL_RESULT_COLUMN => $resultColumn);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetUPDATEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_indicator($id)
    {
        // create sql params
        $sql_params = array(":" . IndicatorDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetDELETEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __disable_indicator($id)
    {
        // create sql params
        $sql_params = array(":" . IndicatorDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetDISABLEQuery(IndicatorDBObject::TABL_INDICATOR_DISABLED);
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __enable_indicator($id)
    {
        // create sql params
        $sql_params = array(":" . IndicatorDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IndicatorDBObject::TABL_INDICATOR)->GetRESTOREQuery(IndicatorDBObject::TABL_INDICATOR_DISABLED);
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    public function ResetStaticData()
    {
        // -- init ETIC indicators table --------------------------------------------------------------------
        $indicators = array(//definition: RightsMap::x_R  | RightsMap::x_G (| RightsMap::x_G)*
            array(IndicatorDBObject::PROC_STATS_UDATA_DIVISION, "hr", "Répartition des membres par pôle", Indicator::GRAPH_TYPE, NULL, 'pie', NULL, "division_graph"),
            array(IndicatorDBObject::PROC_STATS_INT_DEPT, "hr", "Répartition des consultants par département", Indicator::GRAPH_TYPE, NULL, 'pie', NULL, "dept_graph"),
            array(IndicatorDBObject::PROC_STATS_AG_PRESENCE, "hr", "Présences aux AG sur membres recrutés", Indicator::GRAPH_TYPE, NULL, 'bar', "Recrutés;Présents", "ag_graph"),
            array(IndicatorDBObject::PROC_STATS_PC_RATE, "hr", "Taux de membres au PC", Indicator::VALUE_TYPE, NULL, 30, '%', 1),
            array(IndicatorDBObject::PROC_STATS_ADMIN, "hr", "Nombre de membres actifs", Indicator::VALUE_TYPE, NULL, 50, '', 1),
            array(IndicatorDBObject::PROC_STATS_INTER, "hr", "Nombre de consultants", Indicator::VALUE_TYPE, NULL, 100, '', 1),
            array(IndicatorDBObject::PROC_STATS_INSCRIPTIONS, "hr", "Inscriptions par mois", Indicator::GRAPH_TYPE, NULL, 'bar', "Recrutés", "insc_graph"),
            //array(IndicatorDBObject::PROC_STATS_MEMBERS, "hr", "Evolution des membres", Indicator::GRAPH_TYPE, NULL, "scatter", "Cumul", "members_graph"),
            array(IndicatorDBObject::PROC_INVALID_ADMM, "hr", "Adhésions administrateur invalides", Indicator::TABLE_TYPE, NULL, "Pôle", "Nombre"),
            array(IndicatorDBObject::PROC_INVALID_INTM, "hr", "Adhésions Consultant invalides", Indicator::TABLE_TYPE, NULL, "Département INSA", "Nombre")
        );
        foreach ($indicators as $attr) {
            switch ($attr[3]) {
                case Indicator::VALUE_TYPE:
                    $this->__insert_value_indicator(
                        $attr[0],
                        $attr[1],
                        $attr[2],
                        $attr[4],
                        $attr[5],
                        $attr[6],
                        $attr[7]
                    );
                    break;
                case Indicator::GRAPH_TYPE:
                    $this->__insert_graph_indicator(
                        $attr[0],
                        $attr[1],
                        $attr[2],
                        $attr[4],
                        $attr[5],
                        $attr[6],
                        $attr[7]
                    );
                    break;
                case Indicator::TABLE_TYPE:
                    $this->__insert_table_indicator(
                        $attr[0],
                        $attr[1],
                        $attr[2],
                        $attr[4],
                        $attr[5],
                        $attr[6]
                    );
                    break;
            }
        }
    }

}

/**
 * @brief Indicator object interface
 */
class IndicatorDBObject extends AbstractDBObject
{

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
    const COL_GRAPH_NAME = "graph_name";
    const COL_LABEL_COLUMN = "label_column";
    const COL_RESULT_COLUMN = "result_column";
    const COL_RESULTS = "results";
    // -- attributes
    // -- procedures
    const PROC_STATS_UDATA_DIVISION = "stats_udata_division";
    const PROC_STATS_INT_DEPT = "stats_int_dept";
    const PROC_STATS_AG_PRESENCE = "stats_udata_ag";
    const PROC_STATS_PC_RATE = "stats_udata_pc";
    const PROC_STATS_ADMIN = "stats_admin";
    const PROC_STATS_INTER = "stats_inter";
    const PROC_STATS_INSCRIPTIONS = "stats_udata_inscription";
    const PROC_STATS_MEMBERS = "stats_udata_members";
    const PROC_INVALID_ADMM = "stats_invalid_admm";
    const PROC_INVALID_INTM = "stats_invalid_intm";

    // -- functions

    public function __construct($module)
    {
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
            ->AddColumn(IndicatorDBObject::COL_PARAMS, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_EXPECTED_RESULT, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_UNIT, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_EXPECTED_GREATER, DBTable::DT_INT, 1, true)// boolean
            ->AddColumn(IndicatorDBObject::COL_GRAPH_TYPE, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_LEGEND, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_GRAPH_NAME, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_LABEL_COLUMN, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_RESULT_COLUMN, DBTable::DT_VARCHAR, 255, true);

        $dol_indicator_disabled = new DBTable(IndicatorDBObject::TABL_INDICATOR_DISABLED);
        $dol_indicator_disabled
            ->AddColumn(IndicatorDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(IndicatorDBObject::COL_PROCEDURE, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(IndicatorDBObject::COL_MODULE, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(IndicatorDBObject::COL_DESCRIPTION, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(IndicatorDBObject::COL_TYPE, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(IndicatorDBObject::COL_PARAMS, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_EXPECTED_RESULT, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_UNIT, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_EXPECTED_GREATER, DBTable::DT_INT, 1, true)// boolean
            ->AddColumn(IndicatorDBObject::COL_GRAPH_TYPE, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_LEGEND, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_GRAPH_NAME, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_LABEL_COLUMN, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(IndicatorDBObject::COL_RESULT_COLUMN, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(DBTable::COL_DISABLE_TSMP, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(DBTable::COL_RESTORE_TSMP, DBTable::DT_VARCHAR, 255, false);
        // -- add tables
        parent::addTable($dol_indicator);
        parent::addTable($dol_indicator_disabled);

        // -- create procedures
        // -- UserData by division
        $stats_udata_division = new DBProcedure(IndicatorDBObject::PROC_STATS_UDATA_DIVISION, " 
		SELECT division, COUNT(*) AS count FROM (SELECT * FROM `com_position` AS a  INNER JOIN (SELECT m1.*
			FROM `dol_udata_position` m1 LEFT JOIN `dol_udata_position` m2
			 ON (m1.user_id = m2.user_id AND m1.since < m2.since)
			WHERE m2.id IS NULL
			) AS b ON (a.label=b.position)) AS a  INNER JOIN (SELECT * FROM `dol_udata` WHERE `disabled`=0 AND `old`=0) AS b 
			ON a.user_id = b.user_id 
			WHERE `division` NOT LIKE 'Consultant'
			GROUP BY `division`;
			 ");

        // -- Consultants by INSA dept
        $stats_int_dept = new DBProcedure(IndicatorDBObject::PROC_STATS_INT_DEPT, "
            SELECT a.insa_dept, COUNT(*) AS count FROM (
              SELECT user_id, insa_dept FROM `dol_udata` WHERE disabled=0) AS a 
              JOIN (
              SELECT user_id FROM `dol_int_membership` ) AS b 
              ON (a.user_id = b.user_id) GROUP BY a.insa_dept;
        ");

        // -- UserData by AG
        $stats_udata_ag = new DBProcedure(IndicatorDBObject::PROC_STATS_AG_PRESENCE,
            "SELECT b.ag, count, presence FROM(
				SELECT 
					ag, 
				    COUNT(*) AS count  
				FROM `dol_udata`
				GROUP BY ag) AS a
				RIGHT OUTER JOIN (SELECT *
				      FROM `com_ag`) AS b
				ON a.ag = b.ag;"
        );

        $stats_udata_pc = new DBProcedure(IndicatorDBObject::PROC_STATS_PC_RATE,
            "DECLARE count_pc INT DEFAULT 0;
			DECLARE count_total INT DEFAULT 0;

			SELECT COUNT(*) INTO count_pc
			FROM `dol_udata`
			WHERE insa_dept='PC' AND disabled=0;

			SELECT COUNT(*) INTO count_total
			FROM `dol_udata`
			WHERE disabled=0 AND old=0;

			SELECT 100*count_pc/count_total AS `result`;"
        );

        $stats_udata_admin = new DBProcedure(IndicatorDBObject::PROC_STATS_ADMIN, "
            SELECT COUNT( * ) 
            FROM (
            
            SELECT user_id
            FROM  `dol_udata` 
            WHERE disabled =0 AND old=0
            ) AS a
            JOIN (
            
            SELECT user_id
            FROM  `dol_adm_membership`
            ) AS b ON ( a.user_id = b.user_id );
            ");

        $stats_udata_inter = new DBProcedure(IndicatorDBObject::PROC_STATS_INTER, "
            SELECT COUNT( * ) 
            FROM (
            
            SELECT user_id
            FROM  `dol_udata` 
            WHERE disabled =0 AND old=0
            ) AS a
            JOIN (
            
            SELECT user_id
            FROM  `dol_int_membership`
            ) AS b ON ( a.user_id = b.user_id );
            ");

        $stats_udata_inscription = new DBProcedure(IndicatorDBObject::PROC_STATS_INSCRIPTIONS,
            "SELECT DATE_FORMAT(creation_date, '%Y-%m') AS month, COUNT(*) as count
				FROM `dol_udata`
				GROUP BY DATE_FORMAT(creation_date, '%Y-%m'), id
				ORDER BY creation_date
				LIMIT 12;"
        );

        $stats_udata_members = new DBProcedure(IndicatorDBObject::PROC_STATS_MEMBERS,
            "SET @runtot:=0;
				SELECT
				   q1.date,
				   q1.count,
				   (@runtot := @runtot + q1.count) AS total
				FROM
				   (SELECT
				       `creation_date` AS date,
				       COUNT(*) AS count
				    FROM  `dol_udata`
				    WHERE  disabled=0 AND old=0
				    GROUP  BY date
				    ORDER  BY date) AS q1;"
        );

        $stats_invalid_admm = new DBProcedure(IndicatorDBObject::PROC_INVALID_ADMM,
            "SELECT division, COUNT(*) as invalid_count
			FROM

			(SELECT a.user_id, division, end_date FROM

			(SELECT user_id, division FROM
			(SELECT m1.*
			FROM `dol_udata_position` m1 LEFT JOIN `dol_udata_position` m2
			 ON (m1.user_id = m2.user_id AND m1.since < m2.since)
			WHERE m2.id IS NULL
			) AS a
			JOIN (SELECT label, division FROM `com_position`) AS b ON b.label=a.position) AS a

			LEFT OUTER JOIN

			(SELECT user_id, end_date
			FROM
			(SELECT m1.*
			FROM `dol_adm_membership` m1 LEFT JOIN `dol_adm_membership` m2
			 ON (m1.user_id = m2.user_id AND m1.end_date < m2.end_date)
			WHERE m2.id IS NULL) AS a
			WHERE a.end_date > NOW() AND fee=1 AND form=1 AND certif=1) AS b

			ON a.user_id = b.user_id) AS a
			WHERE end_date IS NULL AND division NOT LIKE 'Ancien' AND division NOT LIKE 'Consultant'
			GROUP BY division;"
        );

        $stats_invalid_intm = new DBProcedure(IndicatorDBObject::PROC_INVALID_INTM,
            "SELECT insa_dept, COUNT(*) AS count FROM(
			    SELECT user_id, insa_dept
			    FROM dol_udata
				WHERE disabled=0 AND old=0) AS a

			NATURAL JOIN

			(SELECT user_id, start_date
			FROM dol_int_membership
			WHERE fee=0 OR form=0 OR certif=0 OR rib=0 OR identity=0) as b

			GROUP BY insa_dept;"
        );


        // -- add procedures
        parent::addProcedure($stats_udata_division);
        parent::addProcedure($stats_int_dept);
        parent::addProcedure($stats_udata_ag);
        parent::addProcedure($stats_udata_pc);
        parent::addProcedure($stats_udata_admin);
        parent::addProcedure($stats_udata_inter);
        parent::addProcedure($stats_udata_inscription);
        parent::addProcedure($stats_udata_members);
        parent::addProcedure($stats_invalid_admm);
        parent::addProcedure($stats_invalid_intm);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new IndicatorServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new IndicatorServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
