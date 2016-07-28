<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief The Setting class
 */
class Setting implements \JsonSerializable
{

    // -- consts

    // -- attributes
    // --- persistent
    private $id = null;
    private $key = null;
    private $value = null;

    /**
     * @brief Constructs a setting
     */
    public function __construct($id, $key, $value)
    {
        $this->id = intval($id);
        $this->key = $key;
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return [
            SettingDBObject::COL_ID => $this->id,
            SettingDBObject::COL_KEY => $this->key,
            SettingDBObject::COL_VALUE => $this->value
        ];
    }

    /**
     * @brief Returns setting id
     * @return string
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @brief Returns setting key
     * @return string
     */
    public function GetKey()
    {
        return $this->key;
    }

    /**
     * @brief Returns setting value
     * @return string
     */
    public function GetValue()
    {
        return $this->value;
    }
}


class SettingServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_KEY = "key";
    const PARAM_VALUE = "value";
    // --- internal services (actions)
    const GET_SETTING_BY_ID = "byid";
    const GET_SETTING_BY_KEY = "bykey";
    const GET_ALL_SETTINGS = "all";
    const INSERT = "insert";
    const UPDATE = "update";
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
        if (!strcmp($action, SettingServices::GET_SETTING_BY_ID)) {
            $data = $this->__get_setting_by_id($params[SettingServices::PARAM_ID]);
        } else if (!strcmp($action, SettingServices::GET_SETTING_BY_KEY)) {
            $data = $this->__get_setting_by_key($params[SettingServices::PARAM_KEY]);
        } else if (!strcmp($action, SettingServices::GET_ALL_SETTINGS)) {
            $data = $this->__get_all_settings();
        } else if (!strcmp($action, SettingServices::INSERT)) {
            $data = $this->__insert_setting($params[SettingServices::PARAM_KEY], $params[SettingServices::PARAM_VALUE]);
        } else if (!strcmp($action, SettingServices::UPDATE)) {
            $data = $this->__update_setting($params[SettingServices::PARAM_KEY], $params[SettingServices::PARAM_VALUE]);
        } else if (!strcmp($action, SettingServices::DELETE)) {
            $data = $this->__delete_setting($params[SettingServices::PARAM_KEY]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult

    private function __get_setting_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . SettingDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(SettingDBObject::TABL_SETTING)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(SettingDBObject::COL_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create setting var
        $setting = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $setting = new Setting(
                    $row[SettingDBObject::COL_ID],
                    $row[SettingDBObject::COL_KEY],
                    $row[SettingDBObject::COL_VALUE]);
            }
        }
        return $setting;
    }

    private function __get_setting_by_key($key)
    {
        // create sql params array
        $sql_params = array(":" . SettingDBObject::COL_KEY => $key);
        // create sql request
        $sql = parent::getDBObject()->GetTable(SettingDBObject::TABL_SETTING)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(SettingDBObject::COL_KEY));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create setting var
        $setting = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $setting = new Setting(
                    $row[SettingDBObject::COL_ID],
                    $row[SettingDBObject::COL_KEY],
                    $row[SettingDBObject::COL_VALUE]);
            }
        }
        return $setting;
    }

    private function __get_all_settings()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(SettingDBObject::TABL_SETTING)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for settings and fill it
        $settings = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($settings, new Setting(
                    $row[SettingDBObject::COL_ID],
                    $row[SettingDBObject::COL_KEY],
                    $row[SettingDBObject::COL_VALUE]));
            }
        }
        return $settings;
    }

    // -- modify

    private function __insert_setting($key, $value)
    {
        // create sql params
        $sql_params = array(
            ":" . SettingDBObject::COL_ID => "NULL",
            ":" . SettingDBObject::COL_KEY => $key,
            ":" . SettingDBObject::COL_VALUE => $value);
        // create sql request
        $sql = parent::getDBObject()->GetTable(SettingDBObject::TABL_SETTING)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_setting($key, $value)
    {
        // create sql params
        $sql_params = array(
            ":" . SettingDBObject::COL_KEY => $key,
            ":" . SettingDBObject::COL_VALUE => $value);
        // create sql request
        $sql = parent::getDBObject()->GetTable(SettingDBObject::TABL_SETTING)->GetUPDATEQuery(
            array(SettingDBObject::COL_VALUE), array(SettingDBObject::COL_KEY));
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_setting($key)
    {
        // create sql params
        $sql_params = array(":" . SettingDBObject::COL_KEY => $key);
        // create sql request
        $sql = parent::getDBObject()->GetTable(SettingDBObject::TABL_SETTING)->GetDELETEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    public function ResetStaticData()
    {
        // -- init gender table --------------------------------------------------------------------
        $settings = SettingsManager::DB_DEFAULT_SETTINGS;
        // --- insert
        foreach ($settings as $key => $value) {
            $this->__insert_setting($key, $value);
        }
    }
}

/**
 * @brief Setting object interface
 */
class SettingDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "setting";
    // --- tables
    const TABL_SETTING = "dol_setting";
    // --- columns
    const COL_ID = "id";
    const COL_KEY = "key";
    const COL_VALUE = "value";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, SettingDBObject::OBJ_NAME);
        // -- create tables
        // --- dol_setting table
        $dol_setting = new DBTable(SettingDBObject::TABL_SETTING);
        $dol_setting
            ->AddColumn(SettingDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(SettingDBObject::COL_KEY, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(SettingDBObject::COL_VALUE, DBTable::DT_VARCHAR, 255, false);

        // -- add tables
        parent::addTable($dol_setting);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new SettingServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new SettingServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}