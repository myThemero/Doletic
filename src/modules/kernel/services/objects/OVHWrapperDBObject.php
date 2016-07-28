<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief The OVHWrapper class
 */
class OVHWrapper implements \JsonSerializable
{

    // -- consts

    // -- attributes
    // --- persistent
    private $id = null;
    private $name = null;
    private $endpoint = null;
    private $app_key = null;
    private $app_sec = null;
    private $con_key = null;

    /**
     * @brief Constructs a wrapper
     */
    public function __construct($id, $name, $endpoint, $appKey, $appSec, $conKey)
    {
        $this->id = intval($id);
        $this->name = $name;
        $this->endpoint = $endpoint;
        $this->app_key = $appKey;
        $this->app_sec = $appSec;
        $this->con_key = $conKey;
    }

    public function jsonSerialize()
    {
        return [
            OVHWrapperDBObject::COL_ID => $this->id,
            OVHWrapperDBObject::COL_NAME => $this->name,
            OVHWrapperDBObject::COL_ENDPOINT => $this->endpoint // no keys in serialized value !
        ];
    }

    /**
     * @brief Returns id
     * @return string
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @brief Returns name
     * @return string
     */
    public function GetName()
    {
        return $this->name;
    }

    /**
     * @brief Returns endpoint
     * @return string
     */
    public function GetEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @brief Returns application key
     * @return string
     */
    public function GetApplicationKey()
    {
        return $this->app_key;
    }

    /**
     * @brief Returns application secret
     * @return string
     */
    public function GetApplicationSecret()
    {
        return $this->app_sec;
    }

    /**
     * @brief Returns consumer key
     * @return string
     */
    public function GetConsumerKey()
    {
        return $this->con_key;
    }
}


class OVHWrapperServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_NAME = "name";
    // --- internal services (actions)
    const GET_BY_ID = "byid";
    const GET_BY_NAME = "byname";
    // -- functions

    // -- construct
    public function __construct($currentUser, $dbObject, $dbConnection)
    {
        parent::__construct($currentUser, $dbObject, $dbConnection);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        if (!strcmp($action, OVHWrapperServices::GET_BY_ID)) {
            $data = $this->__get_by_id($params[OVHWrapperServices::PARAM_ID]);
        } else if (!strcmp($action, OVHWrapperServices::GET_BY_NAME)) {
            $data = $this->__get_by_name($params[OVHWrapperServices::PARAM_NAME]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult

    private function __get_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . OVHWrapperDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(OVHWrapperDBObject::TABL_OVH_WRAPPER)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(OVHWrapperDBObject::COL_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create wrapper var
        $wrapper = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $wrapper = new OVHWrapper(
                    $row[OVHWrapperDBObject::COL_ID],
                    $row[OVHWrapperDBObject::COL_NAME],
                    $row[OVHWrapperDBObject::COL_ENDPOINT],
                    $row[OVHWrapperDBObject::COL_APP_KEY],
                    $row[OVHWrapperDBObject::COL_APP_SEC],
                    $row[OVHWrapperDBObject::COL_CON_KEY]);
            }
        }
        return $wrapper;
    }

    private function __get_by_name($name)
    {
        // create sql params array
        $sql_params = array(":" . OVHWrapperDBObject::COL_NAME => $name);
        // create sql request
        $sql = parent::getDBObject()->GetTable(OVHWrapperDBObject::TABL_OVH_WRAPPER)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(OVHWrapperDBObject::COL_NAME));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create wrapper var
        $wrapper = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $wrapper = new OVHWrapper(
                    $row[OVHWrapperDBObject::COL_ID],
                    $row[OVHWrapperDBObject::COL_NAME],
                    $row[OVHWrapperDBObject::COL_ENDPOINT],
                    $row[OVHWrapperDBObject::COL_APP_KEY],
                    $row[OVHWrapperDBObject::COL_APP_SEC],
                    $row[OVHWrapperDBObject::COL_CON_KEY]);
            }
        }
        return $wrapper;
    }

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    public function ResetStaticData()
    {
        // nothing to init here !
    }
}

/**
 * @brief OVHWrapper db object interface
 */
class OVHWrapperDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "ovh_wrapper";
    // --- tables
    const TABL_OVH_WRAPPER = "dol_ovh_wrapper";
    // --- columns
    const COL_ID = "id";
    const COL_NAME = "name";
    const COL_ENDPOINT = "endpoint";
    const COL_APP_KEY = "app_key";
    const COL_APP_SEC = "app_sec";
    const COL_CON_KEY = "con_key";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, OVHWrapperDBObject::OBJ_NAME);
        // -- create tables
        // --- dol_ovh_wrapper table
        $dol_ovh_wrapper = new DBTable(OVHWrapperDBObject::TABL_OVH_WRAPPER);
        $dol_ovh_wrapper
            ->AddColumn(OVHWrapperDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(OVHWrapperDBObject::COL_NAME, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(OVHWrapperDBObject::COL_ENDPOINT, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(OVHWrapperDBObject::COL_APP_KEY, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(OVHWrapperDBObject::COL_APP_SEC, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(OVHWrapperDBObject::COL_CON_KEY, DBTable::DT_VARCHAR, 255, false);

        // -- add tables
        parent::addTable($dol_ovh_wrapper);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new OVHWrapperServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new OVHWrapperServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}