<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief Firm object
 */
class Firm implements \JsonSerializable
{

    // -- consts

    // -- attributes
    private $id = null;
    private $siret = null;
    private $name = null;
    private $address = null;
    private $postal_code = null;
    private $city = null;
    private $country = null;
    private $type = null;
    private $last_contact = null;


    public function __construct($id, $siret, $name, $address, $postalCode, $city, $country, $type, $lastContact)
    {
        $this->id = intval($id);
        $this->siret = $siret;
        $this->name = $name;
        $this->address = $address;
        $this->postal_code = $postalCode;
        $this->city = $city;
        $this->country = $country;
        $this->type = $type;
        $this->last_contact = $lastContact;
    }

    public function jsonSerialize()
    {
        return [
            FirmDBObject::COL_ID => $this->id,
            FirmDBObject::COL_SIRET => $this->siret,
            FirmDBObject::COL_NAME => $this->name,
            FirmDBObject::COL_ADDRESS => $this->address,
            FirmDBObject::COL_POSTAL_CODE => $this->postal_code,
            FirmDBObject::COL_CITY => $this->city,
            FirmDBObject::COL_COUNTRY => $this->country,
            FirmDBObject::COL_TYPE => $this->type,
            FirmDBObject::COL_LAST_CONTACT => $this->last_contact
        ];
    }

    /**
     * @brief Returns object's id
     * @return int
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @brief
     */
    public function GetSIRET()
    {
        return $this->siret;
    }

    /**
     * @brief
     */
    public function GetName()
    {
        return $this->name;
    }

    /**
     * @brief
     */
    public function GetAddress()
    {
        return $this->address;
    }

    /**
     * @brief
     */
    public function GetPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @brief
     */
    public function GetCity()
    {
        return $this->city;
    }

    /**
     * @brief
     */
    public function GetCountry()
    {
        return $this->country;
    }

    /**
     * @brief
     */
    public function GetType()
    {
        return $this->type;
    }

    /**
     * @brief
     */
    public function GetLastContact()
    {
        return $this->last_contact;
    }
}

/**
 * @brief Firm object related services
 */
class FirmServices extends AbstractObjectServices
{

    // -- consts
    // --- params
    const PARAM_ID = "id";
    const PARAM_SIRET = "siret";
    const PARAM_NAME = "name";
    const PARAM_ADDRESS = "address";
    const PARAM_POSTAL_CODE = "postalCode";
    const PARAM_CITY = "city";
    const PARAM_COUNTRY = "country";
    const PARAM_TYPE = "type";
    const PARAM_LAST_CONTACT = "lastContact";
    // --- actions
    const GET_FIRM_BY_ID = "byid";
    const GET_ALL_FIRMS = "all";
    const GET_ALL_FIRM_TYPES = "alltypes";
    const INSERT = "insert";
    const UPDATE = "update";
    const DELETE = "delete";

    const FORCE_INSERT = "forins";

    // -- functions

    // --- construct
    public function __construct($currentUser, $dbObject, $dbConnection)
    {
        parent::__construct($currentUser, $dbObject, $dbConnection);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        if (!strcmp($action, FirmServices::GET_FIRM_BY_ID)) {
            $data = $this->__get_firm_by_id($params[FirmServices::PARAM_ID]);
        } else if (!strcmp($action, FirmServices::GET_ALL_FIRMS)) {
            $data = $this->__get_all_firms();
        } else if (!strcmp($action, FirmServices::GET_ALL_FIRM_TYPES)) {
            $data = $this->__get_all_firm_types();
        } else if (!strcmp($action, FirmServices::INSERT)) {
            $data = $this->__insert_firm(
                $params[FirmServices::PARAM_SIRET],
                $params[FirmServices::PARAM_NAME],
                $params[FirmServices::PARAM_ADDRESS],
                $params[FirmServices::PARAM_POSTAL_CODE],
                $params[FirmServices::PARAM_CITY],
                $params[FirmServices::PARAM_COUNTRY],
                $params[FirmServices::PARAM_TYPE]);
        } else if (!strcmp($action, FirmServices::FORCE_INSERT)) {
            $data = $this->__force_insert_firm(
                $params[FirmServices::PARAM_ID],
                $params[FirmServices::PARAM_SIRET],
                $params[FirmServices::PARAM_NAME],
                $params[FirmServices::PARAM_ADDRESS],
                $params[FirmServices::PARAM_POSTAL_CODE],
                $params[FirmServices::PARAM_CITY],
                $params[FirmServices::PARAM_COUNTRY],
                $params[FirmServices::PARAM_TYPE]);
        } else if (!strcmp($action, FirmServices::UPDATE)) {
            $data = $this->__update_firm(
                $params[FirmServices::PARAM_ID],
                $params[FirmServices::PARAM_SIRET],
                $params[FirmServices::PARAM_NAME],
                $params[FirmServices::PARAM_ADDRESS],
                $params[FirmServices::PARAM_POSTAL_CODE],
                $params[FirmServices::PARAM_CITY],
                $params[FirmServices::PARAM_COUNTRY],
                $params[FirmServices::PARAM_TYPE]);
        } else if (!strcmp($action, FirmServices::DELETE)) {
            $data = $this->__delete_firm($params[FirmServices::PARAM_ID]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ###############################################################

    // --- consult

    private function __get_firm_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . FirmDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(FirmDBObject::COL_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create firm var
        $firm = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $firm = new Firm(
                    $row[FirmDBObject::COL_ID],
                    $row[FirmDBObject::COL_SIRET],
                    $row[FirmDBObject::COL_NAME],
                    $row[FirmDBObject::COL_ADDRESS],
                    $row[FirmDBObject::COL_POSTAL_CODE],
                    $row[FirmDBObject::COL_CITY],
                    $row[FirmDBObject::COL_COUNTRY],
                    $row[FirmDBObject::COL_TYPE],
                    $row[FirmDBObject::COL_LAST_CONTACT]);
            }
        }
        return $firm;
    }

    private function __get_all_firms()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for firms and fill it
        $firms = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($firms, new Firm(
                    $row[FirmDBObject::COL_ID],
                    $row[FirmDBObject::COL_SIRET],
                    $row[FirmDBObject::COL_NAME],
                    $row[FirmDBObject::COL_ADDRESS],
                    $row[FirmDBObject::COL_POSTAL_CODE],
                    $row[FirmDBObject::COL_CITY],
                    $row[FirmDBObject::COL_COUNTRY],
                    $row[FirmDBObject::COL_TYPE],
                    $row[FirmDBObject::COL_LAST_CONTACT]));
            }
        }
        return $firms;
    }

    private function __get_all_firm_types()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM_TYPE)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for firms and fill it
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data, $row[FirmDBObject::COL_LABEL]);
            }
        }
        return $data;
    }

    // --- modify

    private function __insert_firm($siret, $name, $address, $postalCode, $city, $country, $type)
    {
        // create sql params
        $sql_params = array(
            ":" . FirmDBObject::COL_ID => null,
            ":" . FirmDBObject::COL_SIRET => $siret,
            ":" . FirmDBObject::COL_NAME => $name,
            ":" . FirmDBObject::COL_ADDRESS => $address,
            ":" . FirmDBObject::COL_POSTAL_CODE => $postalCode,
            ":" . FirmDBObject::COL_CITY => $city,
            ":" . FirmDBObject::COL_COUNTRY => $country,
            ":" . FirmDBObject::COL_TYPE => $type,
            ":" . FirmDBObject::COL_LAST_CONTACT => null
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __force_insert_firm($id, $siret, $name, $address, $postalCode, $city, $country, $type)
    {
        // create sql params
        $sql_params = array(
            ":" . FirmDBObject::COL_ID => $id,
            ":" . FirmDBObject::COL_SIRET => $siret,
            ":" . FirmDBObject::COL_NAME => $name,
            ":" . FirmDBObject::COL_ADDRESS => $address,
            ":" . FirmDBObject::COL_POSTAL_CODE => $postalCode,
            ":" . FirmDBObject::COL_CITY => $city,
            ":" . FirmDBObject::COL_COUNTRY => $country,
            ":" . FirmDBObject::COL_TYPE => $type,
            ":" . FirmDBObject::COL_LAST_CONTACT => null
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_firm($id, $siret, $name, $address, $postalCode, $city, $country, $type)
    {
        // create sql params
        $sql_params = array(
            ":" . FirmDBObject::COL_ID => $id,
            ":" . FirmDBObject::COL_SIRET => $siret,
            ":" . FirmDBObject::COL_NAME => $name,
            ":" . FirmDBObject::COL_ADDRESS => $address,
            ":" . FirmDBObject::COL_POSTAL_CODE => $postalCode,
            ":" . FirmDBObject::COL_CITY => $city,
            ":" . FirmDBObject::COL_COUNTRY => $country,
            ":" . FirmDBObject::COL_TYPE => $type,
            ":" . FirmDBObject::COL_LAST_CONTACT => null
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM)->GetUPDATEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_firm($id)
    {
        // create sql params
        $sql_params = array(":" . FirmDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM)->GetDELETEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    /**
     *    ---------!---------!---------!---------!---------!---------!---------!---------!---------
     *  !                            DATABASE CONSISTENCY WARNING                                !
     *  !                                                                                        !
     *  !  Please respect the following points :                                                !
     *    !  - When adding static data to existing data => always add at the end of the list      !
     *  !  - Never remove data (or ensure that no database element use one as a foreign key)    !
     *    ---------!---------!---------!---------!---------!---------!---------!---------!---------
     */
    public function ResetStaticData()
    {
        // --- retrieve SQL query
        $types = [
            'Start-up',
            'Grand groupe',
            'PME/PMI',
            'TPE',
            'Administration',
            'Grossiste',
            'Revendeur',
            'Particulier',
            'Autres'
        ];
        $sql = parent::getDBObject()->GetTable(FirmDBObject::TABL_FIRM_TYPE)->GetINSERTQuery();
        foreach ($types as $type) {
            // --- create param array
            $sql_params = array(
                ":" . FirmDBObject::COL_LABEL => $type
            );
            // --- execute SQL query
            parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
        }
    }

}

/**
 * @brief Firm object interface
 */
class FirmDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "firm";
    // --- tables
    const TABL_FIRM = "dol_firm";
    const TABL_FIRM_TYPE = "dol_firm_type";
    // --- columns
    const COL_LABEL = "label";
    const COL_ID = "id";
    const COL_SIRET = "siret";
    const COL_NAME = "name";
    const COL_ADDRESS = "address";
    const COL_POSTAL_CODE = "postal_code";
    const COL_CITY = "city";
    const COL_COUNTRY = "country";
    const COL_TYPE = "type";
    const COL_LAST_CONTACT = "last_contact";

    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, FirmDBObject::OBJ_NAME);
        // -- create tables
        // --- dol_firm_type table
        $dol_firm_type = new DBTable(FirmDBObject::TABL_FIRM_TYPE);
        $dol_firm_type
            ->AddColumn(FirmDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true);

        // --- dol_firm table
        $dol_firm = new DBTable(FirmDBObject::TABL_FIRM);
        $dol_firm
            ->AddColumn(FirmDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(FirmDBObject::COL_SIRET, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(FirmDBObject::COL_NAME, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(FirmDBObject::COL_ADDRESS, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(FirmDBObject::COL_POSTAL_CODE, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(FirmDBObject::COL_CITY, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(FirmDBObject::COL_COUNTRY, DBTable::DT_VARCHAR, 255, true)
            ->AddColumn(FirmDBObject::COL_TYPE, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(FirmDBObject::COL_LAST_CONTACT, DBTable::DT_VARCHAR, 255, true)
            ->AddForeignKey(
                FirmDBObject::TABL_FIRM . '_fk1',
                FirmDBObject::COL_TYPE,
                FirmDBObject::TABL_FIRM_TYPE,
                FirmDBObject::COL_LABEL, DBTable::DT_CASCADE, DBTable::DT_CASCADE
            )
            ->AddForeignKey(
                FirmDBObject::TABL_FIRM . '_fk2',
                FirmDBObject::COL_COUNTRY,
                UserDataDBObject::TABL_COM_COUNTRY,
                UserDataDBObject::COL_LABEL, DBTable::DT_CASCADE, DBTable::DT_CASCADE
            );

        // -- add tables
        parent::addTable($dol_firm_type);
        parent::addTable($dol_firm);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new FirmServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new FirmServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
