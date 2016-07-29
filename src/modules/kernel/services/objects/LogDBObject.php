<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief The Log class
 */
class Log implements \JsonSerializable
{

    // -- consts
    const CRIT_INFO = "INFO";
    const CRIT_WARN = "WARNING";
    const CRIT_ERRO = "ERROR";
    const CRIT_FATA = "FATAL";

    // -- attributes
    // --- persistent
    private $id = null;
    private $criticity = null;
    private $timestamp = null;
    private $script = null;
    private $message = null;

    /**
     * @brief Constructs a log
     */
    public function __construct($id, $criticity, $timestamp, $script, $message)
    {
        $this->id = intval($id);
        $this->criticity = $criticity;
        $this->timestamp = $timestamp;
        $this->script = $script;
        $this->message = $message;
    }

    public function jsonSerialize()
    {
        return [
            LogDBObject::COL_ID => $this->id,
            LogDBObject::COL_CRITICITY => $this->criticity,
            LogDBObject::COL_TIMESTAMP => $this->timestamp,
            LogDBObject::COL_SCRIPT => $this->script,
            LogDBObject::COL_MESSAGE => $this->message
        ];
    }

    /**
     * @brief Returns log id
     * @return string
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @brief Returns log criticity
     * @return string
     */
    public function GetCriticity()
    {
        return $this->criticity;
    }

    /**
     * @brief Returns log timestamp
     * @return string
     */
    public function GetTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @brief Returns log script
     * @return string
     */
    public function GetScript()
    {
        return $this->script;
    }

    /**
     * @brief Returns log message
     * @return string
     */
    public function GetMessage()
    {
        return $this->message;
    }
}


class LogServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_CRITICITY = "criticity";
    const PARAM_SCRIPT = "script";
    const PARAM_MESSAGE = "message";

    // --- internal services (actions)
    const GET_LOG_BY_ID = "byid";
    const GET_LOG_BY_CRIT = "bycrit";
    const GET_ALL_LOGS = "all";
    const GET_ALL_CRITS = "allcrit";
    const INSERT = "insert";
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
        if (!strcmp($action, LogServices::GET_LOG_BY_ID)) {
            $data = $this->__get_log_by_id($params[LogServices::PARAM_ID]);
        } else if (!strcmp($action, LogServices::GET_LOG_BY_ID)) {
            $data = $this->__get_log_by_criticity($params[LogServices::PARAM_CRITICITY]);
        } else if (!strcmp($action, LogServices::GET_ALL_LOGS)) {
            $data = $this->__get_all_logs();
        } else if (!strcmp($action, LogServices::GET_ALL_LOGS)) {
            $data = array(
                Log::CRIT_INFO,
                Log::CRIT_WARN,
                Log::CRIT_ERRO,
                Log::CRIT_FATA
            );
        } else if (!strcmp($action, LogServices::INSERT)) {
            $data = $this->__insert_log(
                $params[LogServices::PARAM_CRITICITY],
                $params[LogServices::PARAM_SCRIPT],
                $params[LogServices::PARAM_MESSAGE]);
        } else if (!strcmp($action, LogServices::DELETE)) {
            $data = $this->__delete_log($params[LogServices::PARAM_ID]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult

    private function __get_log_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . LogDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(LogDBObject::TABL_LOG)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(LogDBObject::COL_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create ticket var
        $log = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $log = new Log(
                    $row[LogDBObject::COL_ID],
                    $row[LogDBObject::COL_CRITICITY],
                    $row[LogDBObject::COL_TIMESTAMP],
                    $row[LogDBObject::COL_SCRIPT],
                    $row[LogDBObject::COL_MESSAGE]);
            }
        }
        return $log;
    }

    private function __get_log_by_criticity($criticity)
    {
        // create sql params array
        $sql_params = array(":" . LogDBObject::COL_CRITICITY => $criticity);
        // create sql request
        $sql = parent::getDBObject()->GetTable(LogDBObject::TABL_LOG)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(LogDBObject::COL_CRITICITY));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create ticket var
        $log = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $log = new Log(
                    $row[LogDBObject::COL_ID],
                    $row[LogDBObject::COL_CRITICITY],
                    $row[LogDBObject::COL_TIMESTAMP],
                    $row[LogDBObject::COL_SCRIPT],
                    $row[LogDBObject::COL_MESSAGE]);
            }
        }
        return $log;
    }

    private function __get_all_logs()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(LogDBObject::TABL_LOG)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for tickets and fill it
        $logs = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($logs, new Log(
                    $row[LogDBObject::COL_ID],
                    $row[LogDBObject::COL_CRITICITY],
                    $row[LogDBObject::COL_TIMESTAMP],
                    $row[LogDBObject::COL_SCRIPT],
                    $row[LogDBObject::COL_MESSAGE]));
            }
        }
        return $logs;
    }

    // -- modify

    private function __insert_log($criticity, $script, $message)
    {
        // create sql params
        $sql_params = array(
            ":" . LogDBObject::COL_ID => null,
            ":" . LogDBObject::COL_CRITICITY => $criticity,
            ":" . LogDBObject::COL_TIMESTAMP => date(DateTime::ISO8601),
            ":" . LogDBObject::COL_SCRIPT => $script,
            ":" . LogDBObject::COL_MESSAGE => $message);
        // create sql request
        $sql = parent::getDBObject()->GetTable(LogDBObject::TABL_LOG)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_log($id)
    {
        // create sql params
        $sql_params = array(":" . LogDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(LogDBObject::TABL_LOG)->GetDELETEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    public function ResetStaticData()
    {
        // no static data to insert
    }

}

/**
 * @brief Log object interface
 */
class LogDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "log";
    // --- tables
    const TABL_LOG = "dol_log";
    // --- columns
    const COL_ID = "id";
    const COL_CRITICITY = "criticity";
    const COL_TIMESTAMP = "timestamp";
    const COL_SCRIPT = "script";
    const COL_MESSAGE = "message";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, LogDBObject::OBJ_NAME);
        // -- create tables
        // --- dol_log table
        $dol_log = new DBTable(LogDBObject::TABL_LOG);
        $dol_log
            ->AddColumn(LogDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(LogDBObject::COL_CRITICITY, DBTable::DT_VARCHAR, 25, false)
            ->AddColumn(LogDBObject::COL_TIMESTAMP, DBTable::DT_VARCHAR, 50, false)
            ->AddColumn(LogDBObject::COL_SCRIPT, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(LogDBObject::COL_MESSAGE, DBTable::DT_TEXT, -1, false);
        // -- add tables
        parent::addTable($dol_log);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new LogServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new LogServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
