<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief IntMembership object
 */
class IntMembership implements \JsonSerializable
{

    // -- consts
    const AUTO_ID = -1;

    // -- attributes
    private $id;
    private $user_id;
    private $start_date;
    private $fee;
    private $form;
    private $certif;
    private $rib;
    private $identity;
    private $secu_number;

    /**
     * @brief Constructs a int_membership
     * @param int $userId
     *        User's ID
     * @param string $startDate
     *        Membership start date
     * @param bool $fee
     *        Membership fee
     * @param bool $form
     *        Member registration form
     * @param bool $certif
     *        School certificate
     * @param bool $rib
     *        User's rib
     * @param bool $identity
     *        User's id card
     */
    public function __construct($id, $userId, $startDate, $fee, $form, $certif, $rib, $identity, $secuNumber)
    {
        $this->id = intval($id);
        $this->user_id = intval($userId);
        $this->start_date = $startDate;
        $this->fee = (bool)$fee;
        $this->form = (bool)$form;
        $this->certif = (bool)$certif;
        $this->rib = (bool)$rib;
        $this->identity = (bool)$identity;
        $this->secu_number = $secuNumber;
    }

    public function jsonSerialize()
    {
        return [
            IntMembershipDBObject::COL_ID => $this->id,
            IntMembershipDBObject::COL_USER_ID => $this->user_id,
            IntMembershipDBObject::COL_START_DATE => $this->start_date,
            IntMembershipDBObject::COL_FEE => $this->fee,
            IntMembershipDBObject::COL_FORM => $this->form,
            IntMembershipDBObject::COL_CERTIF => $this->certif,
            IntMembershipDBObject::COL_RIB => $this->rib,
            IntMembershipDBObject::COL_IDENTITY => $this->identity,
            IntMembershipDBObject::COL_SECU_NUMBER => $this->secu_number
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
    public function GetUserId()
    {
        return $this->user_id;
    }

    /**
     * @brief
     */
    public function GetStartDate()
    {
        return $this->start_date;
    }

    /**
     * @brief
     */
    public function GetFee()
    {
        return $this->fee;
    }

    /**
     * @brief
     */
    public function GetForm()
    {
        return $this->form;
    }

    /**
     * @brief
     */
    public function GetCertif()
    {
        return $this->certif;
    }

    /**
     * @brief
     */
    public function GetRib()
    {
        return $this->rib;
    }

    /**
     * @brief
     */
    public function GetIdentity()
    {
        return $this->identity;
    }

    /**
     * @brief
     */
    public function GetSecuNumber()
    {
        return $this->secu_number;
    }

    /**
     * @brief
     */
    public function IsValid()
    {
        return ($this->fee && $this->form && $this->certif && $this->rib && $this->identity);
    }
}

/**
 * @brief IntMembership object related services
 */
class IntMembershipServices extends AbstractObjectServices
{

    // -- consts
    // --- params
    const PARAM_ID = "id";
    const PARAM_USER = "userId";
    const PARAM_START = "startDate";
    const PARAM_FEE = "fee";
    const PARAM_FORM = "form";
    const PARAM_CERTIF = "certif";
    const PARAM_RIB = "rib";
    const PARAM_IDENTITY = "identity";
    const PARAM_SECU_NUMBER = "secuNumber";
    // --- actions
    const GET_INT_MEMBERSHIP_BY_ID = "byidim";
    const GET_ALL_INT_MEMBERSHIPS = "allim";
    const GET_USER_INT_MEMBERSHIPS = "alluim";
    const GET_ALL_CURRENT_INT_MEMBERSHIPS = "allcurintm";
    const INSERT = "insert";
    const UPDATE = "update";
    const DELETE = "delete";

    // -- functions

    // --- construct
    public function __construct($currentUser, $dbObject, $dbConnection)
    {
        parent::__construct($currentUser, $dbObject, $dbConnection);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        if (!strcmp($action, IntMembershipServices::GET_INT_MEMBERSHIP_BY_ID)) {
            $data = $this->__get_int_membership_by_id($params[IntMembershipServices::PARAM_ID]);
        } else if (!strcmp($action, IntMembershipServices::GET_ALL_INT_MEMBERSHIPS)) {
            $data = $this->__get_all_int_memberships();
        } else if (!strcmp($action, IntMembershipServices::GET_ALL_CURRENT_INT_MEMBERSHIPS)) {
            $data = $this->__get_all_current_int_memberships();
        } else if (!strcmp($action, IntMembershipServices::GET_USER_INT_MEMBERSHIPS)) {
            $data = $this->__get_user_int_memberships($params[IntMembershipServices::PARAM_USER]);
        } else if (!strcmp($action, IntMembershipServices::INSERT)) {
            $data = $this->__insert_int_membership(
                $params[IntMembershipServices::PARAM_USER],
                $params[IntMembershipServices::PARAM_START],
                $params[IntMembershipServices::PARAM_FEE],
                $params[IntMembershipServices::PARAM_FORM],
                $params[IntMembershipServices::PARAM_CERTIF],
                $params[IntMembershipServices::PARAM_RIB],
                $params[IntMembershipServices::PARAM_IDENTITY],
                $params[IntMembershipServices::PARAM_SECU_NUMBER]);
        } else if (!strcmp($action, IntMembershipServices::UPDATE)) {
            $data = $this->__update_int_membership(
                $params[IntMembershipServices::PARAM_ID],
                $params[IntMembershipServices::PARAM_USER],
                $params[IntMembershipServices::PARAM_START],
                $params[IntMembershipServices::PARAM_FEE],
                $params[IntMembershipServices::PARAM_FORM],
                $params[IntMembershipServices::PARAM_CERTIF],
                $params[IntMembershipServices::PARAM_RIB],
                $params[IntMembershipServices::PARAM_IDENTITY],
                $params[IntMembershipServices::PARAM_SECU_NUMBER]);
        } else if (!strcmp($action, IntMembershipServices::DELETE)) {
            $data = $this->__delete_int_membership($params[IntMembershipServices::PARAM_ID]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ###############################################################

    // --- consult

    private function __get_int_membership_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . IntMembershipDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IntMembershipDBObject::COL_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create int_membership var
        $int_membership = null;
        if ($pdos != null) {
            if (($row = $pdos->fetch()) !== false) {
                $int_membership = new IntMembership(
                    $row[IntMembershipDBObject::COL_ID],
                    $row[IntMembershipDBObject::COL_USER_ID],
                    $row[IntMembershipDBObject::COL_START_DATE],
                    $row[IntMembershipDBObject::COL_FEE],
                    $row[IntMembershipDBObject::COL_FORM],
                    $row[IntMembershipDBObject::COL_CERTIF],
                    $row[IntMembershipDBObject::COL_RIB],
                    $row[IntMembershipDBObject::COL_IDENTITY],
                    $row[IntMembershipDBObject::COL_SECU_NUMBER]);
            }
        }
        return $int_membership;
    }

    private function __get_all_int_memberships()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for int_memberships and fill it
        $int_memberships = array();
        if ($pdos != null) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($int_memberships, new IntMembership(
                    $row[IntMembershipDBObject::COL_ID],
                    $row[IntMembershipDBObject::COL_USER_ID],
                    $row[IntMembershipDBObject::COL_START_DATE],
                    $row[IntMembershipDBObject::COL_FEE],
                    $row[IntMembershipDBObject::COL_FORM],
                    $row[IntMembershipDBObject::COL_CERTIF],
                    $row[IntMembershipDBObject::COL_RIB],
                    $row[IntMembershipDBObject::COL_IDENTITY],
                    $row[IntMembershipDBObject::COL_SECU_NUMBER]));
            }
        }
        return $int_memberships;
    }

    private function __get_all_current_int_memberships()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetLastOfEachQuery(
            IntMembershipDBObject::COL_ID,
            IntMembershipDBObject::COL_START_DATE
        );

        $sql_params = array();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create an empty array for adm_memberships and fill it
        $int_memberships = array();
        if ($pdos != null) {
            while (($row = $pdos->fetch()) !== false) {
                $int_memberships[$row[IntMembershipDBObject::COL_USER_ID]] = new IntMembership(
                    $row[IntMembershipDBObject::COL_ID],
                    $row[IntMembershipDBObject::COL_USER_ID],
                    $row[IntMembershipDBObject::COL_START_DATE],
                    $row[IntMembershipDBObject::COL_FEE],
                    $row[IntMembershipDBObject::COL_FORM],
                    $row[IntMembershipDBObject::COL_CERTIF],
                    $row[IntMembershipDBObject::COL_RIB],
                    $row[IntMembershipDBObject::COL_IDENTITY],
                    $row[IntMembershipDBObject::COL_SECU_NUMBER]
                );
            }
        }
        return $int_memberships;
    }

    private function __get_user_int_memberships($userId)
    {
        // create sql params array
        $sql_params = array(":" . IntMembershipDBObject::COL_USER_ID => $userId);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(IntMembershipDBObject::COL_USER_ID));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create an empty array for int_memberships and fill it
        $int_memberships = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($int_memberships, new IntMembership(
                    $row[IntMembershipDBObject::COL_ID],
                    $row[IntMembershipDBObject::COL_USER_ID],
                    $row[IntMembershipDBObject::COL_START_DATE],
                    $row[IntMembershipDBObject::COL_FEE],
                    $row[IntMembershipDBObject::COL_FORM],
                    $row[IntMembershipDBObject::COL_CERTIF],
                    $row[IntMembershipDBObject::COL_RIB],
                    $row[IntMembershipDBObject::COL_IDENTITY],
                    $row[IntMembershipDBObject::COL_SECU_NUMBER]));
            }
        }
        return $int_memberships;
    }

    // --- modify

    private function __insert_int_membership($userId, $startDate, $fee, $form, $certif, $rib, $identity, $secuNumber)
    {
        // create sql params
        $sql_params = array(
            ":" . IntMembershipDBObject::COL_ID => null,
            ":" . IntMembershipDBObject::COL_USER_ID => $userId,
            ":" . IntMembershipDBObject::COL_START_DATE => $startDate,
            ":" . IntMembershipDBObject::COL_FEE => (int)($fee === 'true'),
            ":" . IntMembershipDBObject::COL_FORM => (int)($form === 'true'),
            ":" . IntMembershipDBObject::COL_CERTIF => (int)($certif === 'true'),
            ":" . IntMembershipDBObject::COL_RIB => (int)($rib === 'true'),
            ":" . IntMembershipDBObject::COL_IDENTITY => (int)($identity === 'true'),
            ":" . IntMembershipDBObject::COL_SECU_NUMBER => $secuNumber);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_int_membership($id, $userId, $startDate, $fee, $form, $certif, $rib, $identity, $secuNumber)
    {
        // create sql params
        $sql_params = array(
            ":" . IntMembershipDBObject::COL_ID => $id,
            ":" . IntMembershipDBObject::COL_USER_ID => $userId,
            ":" . IntMembershipDBObject::COL_START_DATE => $startDate,
            ":" . IntMembershipDBObject::COL_FEE => (int)($fee === 'true'),
            ":" . IntMembershipDBObject::COL_FORM => (int)($form === 'true'),
            ":" . IntMembershipDBObject::COL_CERTIF => (int)($certif === 'true'),
            ":" . IntMembershipDBObject::COL_RIB => (int)($rib === 'true'),
            ":" . IntMembershipDBObject::COL_IDENTITY => (int)($identity === 'true'),
            ":" . IntMembershipDBObject::COL_SECU_NUMBER => $secuNumber);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetUPDATEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_int_membership($id)
    {
        // create sql params
        $sql_params = array(":" . IntMembershipDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP)->GetDELETEQuery();
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

    }

}

/**
 * @brief IntMembership object interface
 */
class IntMembershipDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "int_membership";
    // --- tables
    const TABL_INT_MEMBERSHIP = "dol_int_membership";
    // --- columns
    const COL_ID = "id";
    const COL_USER_ID = "user_id";
    const COL_START_DATE = "start_date";
    const COL_FEE = "fee";
    const COL_FORM = "form";
    const COL_CERTIF = "certif";
    const COL_RIB = "rib";
    const COL_IDENTITY = "identity";
    const COL_SECU_NUMBER = "secu_number";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, IntMembershipDBObject::OBJ_NAME);
        // -- create tables
        // --- dol_int_membership table
        $dol_int_membership = new DBTable(IntMembershipDBObject::TABL_INT_MEMBERSHIP);
        $dol_int_membership
            ->AddColumn(IntMembershipDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(IntMembershipDBObject::COL_USER_ID, DBTable::DT_INT, 11, false)
            ->AddColumn(IntMembershipDBObject::COL_START_DATE, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(IntMembershipDBObject::COL_FEE, DBTable::DT_INT, 1, false, 0)// boolean
            ->AddColumn(IntMembershipDBObject::COL_FORM, DBTable::DT_INT, 1, false, 0)// boolean
            ->AddColumn(IntMembershipDBObject::COL_CERTIF, DBTable::DT_INT, 1, false, 0)// boolean
            ->AddColumn(IntMembershipDBObject::COL_RIB, DBTable::DT_INT, 1, false, 0)// boolean
            ->AddColumn(IntMembershipDBObject::COL_IDENTITY, DBTable::DT_INT, 1, false, 0)// boolean
            ->AddColumn(IntMembershipDBObject::COL_SECU_NUMBER, DBTable::DT_VARCHAR, 20, true, NULL)// boolean
            ->AddForeignKey(IntMembershipDBObject::TABL_INT_MEMBERSHIP . '_fk1', IntMembershipDBObject::COL_USER_ID, UserDataDBObject::TABL_USER_DATA, UserDataDBObject::COL_USER_ID, DBTable::DT_CASCADE, DBTable::DT_CASCADE);

        // -- add tables
        parent::addTable($dol_int_membership);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new IntMembershipServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new IntMembershipServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
