<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBTable.php";
require_once "objects/DBProcedure.php";
require_once "objects/RightsMap.php";

/**
 * @brief The Task class
 */
class Task implements \JsonSerializable
{

    // -- consts

    // -- attributes
    private $id = null;
    private $project_number = null;
    private $number = null;
    private $name = null;
    private $description = null;
    private $jeh_amount = null;
    private $jeh_cost = null;
    private $start_date = null;
    private $end_date = null;
    private $ended = false;

    /**
     * Task constructor.
     */
    public function __construct($id, $projectNumber, $number, $name, $description, $jehAmount, $jehCost, $startDate,
                                $endDate, $ended)
    {
        $this->id = $id;
        $this->project_number = $projectNumber;
        $this->number = $number;
        $this->name = $name;
        $this->description = $description;
        $this->jeh_amount = $jehAmount;
        $this->jeh_cost = $jehCost;
        $this->start_date = $startDate;
        $this->end_date = $endDate;
        $this->ended = boolval($ended);
    }


    public function jsonSerialize()
    {
        return [
            TaskDBObject::COL_ID => $this->id,
            TaskDBObject::COL_PROJECT_NUMBER => $this->project_number,
            TaskDBObject::COL_NUMBER => $this->number,
            TaskDBObject::COL_NAME => $this->name,
            TaskDBObject::COL_DESCRIPTION => $this->description,
            TaskDBObject::COL_JEH_AMOUNT => $this->jeh_amount,
            TaskDBObject::COL_JEH_COST => $this->jeh_cost,
            TaskDBObject::COL_START_DATE => $this->start_date,
            TaskDBObject::COL_END_DATE => $this->end_date,
            TaskDBObject::COL_ENDED => $this->ended
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getProjectNumber()
    {
        return $this->project_number;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getJehAmount()
    {
        return $this->jeh_amount;
    }

    /**
     * @return int
     */
    public function getJehCost()
    {
        return $this->jeh_cost;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @return boolean
     */
    public function hasEnded()
    {
        return $this->ended;
    }
}


class TaskServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_ID = "id";
    const PARAM_ID_BIS = "idBis";
    const PARAM_PROJECT_NUMBER = "projectNumber";
    const PARAM_NUMBER = "number";
    const PARAM_NAME = "name";
    const PARAM_DESCRIPTION = "description";
    const PARAM_JEH_AMOUNT = "jehAmount";
    const PARAM_JEH_COST = "jehCost";
    const PARAM_START_DATE = "startDate";
    const PARAM_END_DATE = "endDate";
    const PARAM_BILLED = "billed";
    const PARAM_PAYMENT_DATE = "paymentDate";
    const PARAM_TASK_ID = "taskId";
    const PARAM_CONTENT = "content";
    const PARAM_DELIVERY_DATE = "deliveryDate";

    // --- internal services (actions)
    const GET_ALL = "getall";
    const GET_BY_ID = "getbyid";
    const GET_BY_PROJECT = "getbypro";
    const GET_BY_PROJECT_AND_NUMBER = "getbypronum";
    const GET_BILLED = "getbill";
    const GET_BILLED_BY_PROJECT = "getbilbypro";
    const GET_ALL_WITH_DELIVERY = "allwdel";
    const GET_BY_PROJECT_WITH_DELIVERY = "allwdelbypro";
    const GET_ALL_DELIVERY = "alldel";
    const GET_DELIVERY_BY_ID = "delbyid";
    const GET_DELIVERY_BY_PROJECT = "delbypro";
    const GET_DELIVERY_BY_TASK = "delbytask";
    const END_TASK = "endtask";
    const UNEND_TASK = "unendtask";
    const PAY_DELIVERY = "paydel";
    const UNPAY_DELIVERY = "unpaydel";
    const DELIVER_DELIVERY = "deldel";
    const UNDELIVER_DELIVERY = "undeldel";
    const INSERT = "insert";
    const INSERT_DELIVERY = "insertdel";
    const UPDATE = "update";
    const UPDATE_DELIVERY = "updatedel";
    const DELETE = "delete";
    const DELETE_DELIVERY = "deletedel";
    const SWITCH_TASKS_NUMBER = "switchnum";

    // -- services that should not be used except for data migration
    const FORCE_INSERT = "forins";
    const FORCE_POSITION = "forpos";
    // -- functions

    // -- construct
    public function __construct($currentUser, $dbObject, $dbConnection)
    {
        parent::__construct($currentUser, $dbObject, $dbConnection);
    }

    public function GetResponseData($action, $params)
    {
        $data = null;
        if (!strcmp($action, TaskServices::GET_ALL)) {
            $data = $this->__get_all_tasks();
        } else if (!strcmp($action, TaskServices::GET_BY_PROJECT)) {
            $data = $this->__get_tasks_by_project($params[TaskServices::PARAM_PROJECT_NUMBER]);
        } else if (!strcmp($action, TaskServices::GET_BY_ID)) {
            $data = $this->__get_task_by_id($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::GET_BY_PROJECT_AND_NUMBER)) {
            $data = $this->__get_task_by_project_and_number(
                $params[TaskServices::PARAM_PROJECT_NUMBER],
                $params[TaskServices::PARAM_NUMBER]
            );
        }/* else if (!strcmp($action, TaskServices::GET_BILLED)) {
            $data = $this->__get_billed_tasks();
        } else if (!strcmp($action, TaskServices::GET_BILLED_BY_PROJECT)) {
            $data = $this->__get_billed_tasks_by_project($params[TaskServices::PARAM_PROJECT_NUMBER]);
        }*/ else if (!strcmp($action, TaskServices::GET_ALL_WITH_DELIVERY)) {
            $data = $this->__get_all_tasks_with_delivery();
        } else if (!strcmp($action, TaskServices::GET_BY_PROJECT_WITH_DELIVERY)) {
            $data = $this->__get_tasks_by_project_with_delivery($params[TaskServices::PARAM_PROJECT_NUMBER]);
        } else if (!strcmp($action, TaskServices::GET_ALL_DELIVERY)) {
            $data = $this->__get_all_delivery();
        } else if (!strcmp($action, TaskServices::GET_DELIVERY_BY_ID)) {
            $data = $this->__get_delivery_by_id($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::GET_DELIVERY_BY_TASK)) {
            $data = $this->__get_delivery_by_task($params[TaskServices::PARAM_TASK_ID]);
        } else if (!strcmp($action, TaskServices::END_TASK)) {
            $data = $this->__end_task($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::UNEND_TASK)) {
            $data = $this->__unend_task($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::PAY_DELIVERY)) {
            $data = $this->__pay_delivery(
                $params[TaskServices::PARAM_ID],
                $params[TaskServices::PARAM_PAYMENT_DATE]
            );
        } else if (!strcmp($action, TaskServices::UNPAY_DELIVERY)) {
            $data = $this->__unpay_delivery($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::INSERT)) {
            $data = $this->__insert_task(
                $params[TaskServices::PARAM_PROJECT_NUMBER],
                $params[TaskServices::PARAM_NAME],
                $params[TaskServices::PARAM_DESCRIPTION],
                $params[TaskServices::PARAM_JEH_AMOUNT],
                $params[TaskServices::PARAM_JEH_COST],
                $params[TaskServices::PARAM_START_DATE],
                $params[TaskServices::PARAM_END_DATE]
            );
        } else if (!strcmp($action, TaskServices::INSERT_DELIVERY)) {
            $data = $this->__insert_delivery(
                $params[TaskServices::PARAM_TASK_ID],
                $params[TaskServices::PARAM_NUMBER],
                $params[TaskServices::PARAM_CONTENT],
                $params[TaskServices::PARAM_BILLED]
            );
        } else if (!strcmp($action, TaskServices::UPDATE)) {
            $data = $this->__update_task(
                $params[TaskServices::PARAM_ID],
                $params[TaskServices::PARAM_NAME],
                $params[TaskServices::PARAM_DESCRIPTION],
                $params[TaskServices::PARAM_JEH_AMOUNT],
                $params[TaskServices::PARAM_JEH_COST],
                $params[TaskServices::PARAM_START_DATE],
                $params[TaskServices::PARAM_END_DATE]
            );
        } else if (!strcmp($action, TaskServices::UPDATE_DELIVERY)) {
            $data = $this->__update_delivery(
                $params[TaskServices::PARAM_ID],
                $params[TaskServices::PARAM_NUMBER],
                $params[TaskServices::PARAM_CONTENT],
                $params[TaskServices::PARAM_BILLED]
            );
        } else if (!strcmp($action, TaskServices::DELIVER_DELIVERY)) {
            $data = $this->__deliver_delivery(
                $params[TaskServices::PARAM_ID],
                $params[TaskServices::PARAM_DELIVERY_DATE]
            );
        } else if (!strcmp($action, TaskServices::UNDELIVER_DELIVERY)) {
            $data = $this->__undeliver_delivery(
                $params[TaskServices::PARAM_ID]
            );
        } else if (!strcmp($action, TaskServices::DELETE)) {
            $data = $this->__delete_task($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::DELETE_DELIVERY)) {
            $data = $this->__delete_delivery($params[TaskServices::PARAM_ID]);
        } else if (!strcmp($action, TaskServices::SWITCH_TASKS_NUMBER)) {
            $data = $this->__switch_task_numbers(
                $params[TaskServices::PARAM_ID],
                $params[TaskServices::PARAM_ID_BIS]
            );
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult
    private function __get_all_tasks()
    {
        // create sql params array
        $sql_params = array();
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data, new Task(
                        $row[TaskDBObject::COL_ID],
                        $row[TaskDBObject::COL_PROJECT_NUMBER],
                        $row[TaskDBObject::COL_NUMBER],
                        $row[TaskDBObject::COL_NAME],
                        $row[TaskDBObject::COL_DESCRIPTION],
                        $row[TaskDBObject::COL_JEH_AMOUNT],
                        $row[TaskDBObject::COL_JEH_COST],
                        $row[TaskDBObject::COL_START_DATE],
                        $row[TaskDBObject::COL_END_DATE],
                        $row[TaskDBObject::COL_ENDED]
                    )
                );
            }
        }
        return $data;
    }

    private function __get_task_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . TaskDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(TaskDBObject::COL_ID)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $data = new Task(
                    $row[TaskDBObject::COL_ID],
                    $row[TaskDBObject::COL_PROJECT_NUMBER],
                    $row[TaskDBObject::COL_NUMBER],
                    $row[TaskDBObject::COL_NAME],
                    $row[TaskDBObject::COL_DESCRIPTION],
                    $row[TaskDBObject::COL_JEH_AMOUNT],
                    $row[TaskDBObject::COL_JEH_COST],
                    $row[TaskDBObject::COL_START_DATE],
                    $row[TaskDBObject::COL_END_DATE],
                    $row[TaskDBObject::COL_ENDED]
                );
            }
        }
        return $data;
    }

    private function __get_tasks_by_project($projectNumber)
    {
        // create sql params array
        $sql_params = array(":" . TaskDBObject::COL_PROJECT_NUMBER => $projectNumber);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(DBTable::SELECT_ALL),
            array(TaskDBObject::COL_PROJECT_NUMBER),
            array(),
            array(TaskDBObject::COL_NUMBER => DBTable::ORDER_ASC)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                $data[intval($row[TaskDBObject::COL_NUMBER]) - 1] = new Task(
                    $row[TaskDBObject::COL_ID],
                    $row[TaskDBObject::COL_PROJECT_NUMBER],
                    $row[TaskDBObject::COL_NUMBER],
                    $row[TaskDBObject::COL_NAME],
                    $row[TaskDBObject::COL_DESCRIPTION],
                    $row[TaskDBObject::COL_JEH_AMOUNT],
                    $row[TaskDBObject::COL_JEH_COST],
                    $row[TaskDBObject::COL_START_DATE],
                    $row[TaskDBObject::COL_END_DATE],
                    $row[TaskDBObject::COL_ENDED]
                );
            }
        }
        return $data;
    }

    private function __get_task_by_project_and_number($projectNumber, $number)
    {
        // create sql params array
        $sql_params = array(
            ":" . TaskDBObject::COL_PROJECT_NUMBER => $projectNumber,
            ":" . TaskDBObject::COL_NUMBER => $number
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(TaskDBObject::COL_PROJECT_NUMBER, TaskDBObject::COL_NUMBER)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $data = new Task(
                    $row[TaskDBObject::COL_ID],
                    $row[TaskDBObject::COL_PROJECT_NUMBER],
                    $row[TaskDBObject::COL_NUMBER],
                    $row[TaskDBObject::COL_NAME],
                    $row[TaskDBObject::COL_DESCRIPTION],
                    $row[TaskDBObject::COL_JEH_AMOUNT],
                    $row[TaskDBObject::COL_JEH_COST],
                    $row[TaskDBObject::COL_START_DATE],
                    $row[TaskDBObject::COL_END_DATE],
                    $row[TaskDBObject::COL_ENDED]
                );
            }
        }
        return $data;
    }

    private function __get_delivery_by_id($id)
    {
        // create sql params array
        $sql_params = array(":" . TaskDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(TaskDBObject::COL_ID)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $data = [
                    TaskDBObject::COL_ID => $row[TaskDBObject::COL_ID],
                    TaskDBObject::COL_TASK_ID => $row[TaskDBObject::COL_TASK_ID],
                    TaskDBObject::COL_NUMBER => $row[TaskDBObject::COL_NUMBER],
                    TaskDBObject::COL_CONTENT => $row[TaskDBObject::COL_CONTENT],
                    TaskDBObject::COL_BILLED => boolval($row[TaskDBObject::COL_BILLED]),
                    TaskDBObject::COL_DELIVERED => boolval($row[TaskDBObject::COL_DELIVERED]),
                    TaskDBObject::COL_DELIVERY_DATE => $row[TaskDBObject::COL_DELIVERY_DATE],
                    TaskDBObject::COL_PAID => boolval($row[TaskDBObject::COL_PAID]),
                    TaskDBObject::COL_PAYMENT_DATE => $row[TaskDBObject::COL_PAYMENT_DATE]
                ];
            }
        }
        return $data;
    }

    /*private function __get_billed_tasks()
    {
        // create sql params array
        $sql_params = array(":" . TaskDBObject::COL_BILLED => 1);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(TaskDBObject::COL_BILLED)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data, new Task(
                        $row[TaskDBObject::COL_ID],
                        $row[TaskDBObject::COL_PROJECT_NUMBER],
                        $row[TaskDBObject::COL_NUMBER],
                        $row[TaskDBObject::COL_NAME],
                        $row[TaskDBObject::COL_DESCRIPTION],
                        $row[TaskDBObject::COL_JEH_AMOUNT],
                        $row[TaskDBObject::COL_JEH_AMOUNT],
                        $row[TaskDBObject::COL_START_DATE],
                        $row[TaskDBObject::COL_END_DATE],
                        $row[TaskDBObject::COL_ENDED]
                    )
                );
            }
        }
        return $data;
    }

    private function __get_billed_tasks_by_project($projectNumber)
    {
        // create sql params array
        $sql_params = array(
            ":" . TaskDBObject::COL_BILLED => 1,
            ":" . TaskDBObject::COL_PROJECT_NUMBER => $projectNumber
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(TaskDBObject::COL_BILLED, TaskDBObject::COL_PROJECT_NUMBER)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data, new Task(
                        $row[TaskDBObject::COL_ID],
                        $row[TaskDBObject::COL_PROJECT_NUMBER],
                        $row[TaskDBObject::COL_NUMBER],
                        $row[TaskDBObject::COL_NAME],
                        $row[TaskDBObject::COL_DESCRIPTION],
                        $row[TaskDBObject::COL_JEH_AMOUNT],
                        $row[TaskDBObject::COL_JEH_AMOUNT],
                        $row[TaskDBObject::COL_START_DATE],
                        $row[TaskDBObject::COL_END_DATE],
                        $row[TaskDBObject::COL_ENDED]
                    )
                );
            }
        }
        return $data;
    }*/

    private function __get_all_tasks_with_delivery()
    {
        // create sql params array
        $sql_params = array();
        // create sql request
        $sql1 = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery();
        $sql2 = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetSELECTQuery();
        $sql = DBTable::getJOINQuery($sql1, $sql2, [TaskDBObject::COL_ID, TaskDBObject::COL_TASK_ID], DBTable::DT_LEFT);
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data, new Task(
                        $row[TaskDBObject::COL_ID],
                        $row[TaskDBObject::COL_PROJECT_NUMBER],
                        $row[TaskDBObject::COL_NUMBER],
                        $row[TaskDBObject::COL_NAME],
                        $row[TaskDBObject::COL_DESCRIPTION],
                        $row[TaskDBObject::COL_JEH_AMOUNT],
                        $row[TaskDBObject::COL_JEH_COST],
                        $row[TaskDBObject::COL_START_DATE],
                        $row[TaskDBObject::COL_END_DATE],
                        $row[TaskDBObject::COL_ENDED]
                    )
                );
            }
        }
        return $data;
    }

    private function __get_tasks_by_project_with_delivery($projectNumber)
    {
        // create sql params array
        $sql_params = array(
            ":" . TaskDBObject::COL_PROJECT_NUMBER => $projectNumber
        );
        // create sql request
        $sql1 = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(DBTable::SELECT_ALL),
            array(TaskDBObject::COL_PROJECT_NUMBER),
            array(),
            array(TaskDBObject::COL_NUMBER => DBTable::ORDER_ASC)
        );
        $sql2 = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetSELECTQuery();
        $sql = DBTable::getJOINQuery($sql1, $sql2, [TaskDBObject::COL_ID, TaskDBObject::COL_TASK_ID], DBTable::DT_LEFT);
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                $data[intval($row[TaskDBObject::COL_NUMBER]) - 1] = new Task(
                    $row[TaskDBObject::COL_ID],
                    $row[TaskDBObject::COL_PROJECT_NUMBER],
                    $row[TaskDBObject::COL_NUMBER],
                    $row[TaskDBObject::COL_NAME],
                    $row[TaskDBObject::COL_DESCRIPTION],
                    $row[TaskDBObject::COL_JEH_AMOUNT],
                    $row[TaskDBObject::COL_JEH_COST],
                    $row[TaskDBObject::COL_START_DATE],
                    $row[TaskDBObject::COL_END_DATE],
                    $row[TaskDBObject::COL_ENDED]
                );
            }
        }
        return $data;
    }

    private function __get_all_delivery()
    {
        // create sql params array
        $sql_params = array();
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data,
                    [
                        TaskDBObject::COL_ID => $row[TaskDBObject::COL_ID],
                        TaskDBObject::COL_TASK_ID => $row[TaskDBObject::COL_TASK_ID],
                        TaskDBObject::COL_NUMBER => $row[TaskDBObject::COL_NUMBER],
                        TaskDBObject::COL_CONTENT => $row[TaskDBObject::COL_CONTENT],
                        TaskDBObject::COL_BILLED => boolval($row[TaskDBObject::COL_BILLED]),
                        TaskDBObject::COL_DELIVERED => boolval($row[TaskDBObject::COL_DELIVERED]),
                        TaskDBObject::COL_DELIVERY_DATE => $row[TaskDBObject::COL_DELIVERY_DATE],
                        TaskDBObject::COL_PAID => boolval($row[TaskDBObject::COL_PAID]),
                        TaskDBObject::COL_PAYMENT_DATE => $row[TaskDBObject::COL_PAYMENT_DATE]
                    ]
                );
            }
        }
        return $data;
    }

    private function __get_delivery_by_task($taskId)
    {
        // create sql params array
        $sql_params = array(":" . TaskDBObject::COL_TASK_ID => $taskId);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(TaskDBObject::COL_TASK_ID)
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($data,
                    [
                        TaskDBObject::COL_ID => $row[TaskDBObject::COL_ID],
                        TaskDBObject::COL_TASK_ID => $row[TaskDBObject::COL_TASK_ID],
                        TaskDBObject::COL_NUMBER => $row[TaskDBObject::COL_NUMBER],
                        TaskDBObject::COL_CONTENT => $row[TaskDBObject::COL_CONTENT],
                        TaskDBObject::COL_BILLED => boolval($row[TaskDBObject::COL_BILLED]),
                        TaskDBObject::COL_DELIVERED => boolval($row[TaskDBObject::COL_DELIVERED]),
                        TaskDBObject::COL_DELIVERY_DATE => $row[TaskDBObject::COL_DELIVERY_DATE],
                        TaskDBObject::COL_PAID => boolval($row[TaskDBObject::COL_PAID]),
                        TaskDBObject::COL_PAYMENT_DATE => $row[TaskDBObject::COL_PAYMENT_DATE]
                    ]
                );
            }
        }
        return $data;
    }


    // -- modify

    private function __insert_task($projectNumber, $name, $description, $jehAmount, $jehCost, $startDate,
                                   $endDate)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => null,
            ":" . TaskDBObject::COL_PROJECT_NUMBER => $projectNumber,
            ":" . TaskDBObject::COL_NUMBER => $this->__get_next_task_number($projectNumber),
            ":" . TaskDBObject::COL_NAME => $name,
            ":" . TaskDBObject::COL_DESCRIPTION => $description,
            ":" . TaskDBObject::COL_JEH_AMOUNT => $jehAmount,
            ":" . TaskDBObject::COL_JEH_COST => $jehCost,
            ":" . TaskDBObject::COL_START_DATE => $startDate,
            ":" . TaskDBObject::COL_END_DATE => $endDate,
            ":" . TaskDBObject::COL_ENDED => 0
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __insert_delivery($taskId, $number, $content, $billed)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => null,
            ":" . TaskDBObject::COL_TASK_ID => $taskId,
            ":" . TaskDBObject::COL_NUMBER => $number,
            ":" . TaskDBObject::COL_CONTENT => $content,
            ":" . TaskDBObject::COL_DELIVERED => 0,
            ":" . TaskDBObject::COL_DELIVERY_DATE => null,
            ":" . TaskDBObject::COL_BILLED => $billed,
            ":" . TaskDBObject::COL_PAID => 0,
            ":" . TaskDBObject::COL_PAYMENT_DATE => null,
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_task($id, $name, $description, $jehAmount, $jehCost, $startDate,
                                   $endDate)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_NAME => $name,
            ":" . TaskDBObject::COL_DESCRIPTION => $description,
            ":" . TaskDBObject::COL_JEH_AMOUNT => $jehAmount,
            ":" . TaskDBObject::COL_JEH_COST => $jehCost,
            ":" . TaskDBObject::COL_START_DATE => $startDate,
            ":" . TaskDBObject::COL_END_DATE => $endDate
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetUPDATEQuery(array(
                TaskDBObject::COL_NAME,
                TaskDBObject::COL_DESCRIPTION,
                TaskDBObject::COL_JEH_AMOUNT,
                TaskDBObject::COL_JEH_COST,
                TaskDBObject::COL_START_DATE,
                TaskDBObject::COL_END_DATE
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __end_task($id)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_ENDED => 1
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetUPDATEQuery(array(
                TaskDBObject::COL_ENDED
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __unend_task($id)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_ENDED => 0
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetUPDATEQuery(array(
                TaskDBObject::COL_ENDED
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_delivery($id, $number, $content, $billed)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_NUMBER => $number,
            ":" . TaskDBObject::COL_CONTENT => $content,
            ":" . TaskDBObject::COL_BILLED => $billed
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetUPDATEQuery(array(
                TaskDBObject::COL_NUMBER,
                TaskDBObject::COL_CONTENT,
                TaskDBObject::COL_BILLED
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __deliver_delivery($id, $deliveryDate)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_DELIVERED => 1,
            ":" . TaskDBObject::COL_DELIVERY_DATE => $deliveryDate
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetUPDATEQuery(array(
                TaskDBObject::COL_DELIVERED,
                TaskDBObject::COL_DELIVERY_DATE
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __undeliver_delivery($id)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_DELIVERED => 0,
            ":" . TaskDBObject::COL_DELIVERY_DATE => null
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetUPDATEQuery(array(
                TaskDBObject::COL_DELIVERED,
                TaskDBObject::COL_DELIVERY_DATE
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __pay_delivery($id, $paymentDate)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_PAID => 1,
            ":" . TaskDBObject::COL_PAYMENT_DATE => $paymentDate
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetUPDATEQuery(array(
                TaskDBObject::COL_PAID,
                TaskDBObject::COL_PAYMENT_DATE
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __unpay_delivery($id)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_PAID => 0,
            ":" . TaskDBObject::COL_PAYMENT_DATE => null
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetUPDATEQuery(array(
                TaskDBObject::COL_PAID,
                TaskDBObject::COL_PAYMENT_DATE
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_task($id)
    {
        $projectNumber = $this->__get_task_by_id($id)->getProjectNumber();
        // create sql params
        $sql_params = array(":" . TaskDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetDELETEQuery();
        // execute query
        if (parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
            return $this->__fix_task_numbers($projectNumber);
        }
    }

    private function __delete_delivery($id)
    {
        // create sql params
        $sql_params = array(":" . TaskDBObject::COL_ID => $id);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_DELIVERY)->GetDELETEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __switch_task_numbers($id, $idBis)
    {
        $taskNum = $this->__get_task_by_id($id)->getNumber();
        $taskNumBis = $this->__get_task_by_id($idBis)->getNumber();

        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_NUMBER => $taskNumBis
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetUPDATEQuery(array(
                TaskDBObject::COL_NUMBER
            )
        );
        // execute query
        if (parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params)) {
            // create sql params
            $sql_params = array(
                ":" . TaskDBObject::COL_ID => $idBis,
                ":" . TaskDBObject::COL_NUMBER => $taskNum
            );
            // create sql request
            $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetUPDATEQuery(array(
                    TaskDBObject::COL_NUMBER
                )
            );
            // execute query
            return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
        }
        return false;
    }

    private function __get_next_task_number($projectNumber)
    {
        // create sql params array
        $sql_params = array(":" . TaskDBObject::COL_PROJECT_NUMBER => $projectNumber);
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetSELECTQuery(
            array(TaskDBObject::COL_NUMBER),
            array(TaskDBObject::COL_PROJECT_NUMBER),
            array(),
            array(TaskDBObject::COL_NUMBER => DBTable::ORDER_DESC),
            1
        );
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create udata var
        $data = 0;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $data = intval($row[TaskDBObject::COL_NUMBER]);
            }
        }
        return $data + 1;
    }

    private function __update_task_number($id, $number)
    {
        // create sql params
        $sql_params = array(
            ":" . TaskDBObject::COL_ID => $id,
            ":" . TaskDBObject::COL_NUMBER => $number
        );
        // create sql request
        $sql = parent::getDBObject()->GetTable(TaskDBObject::TABL_TASK)->GetUPDATEQuery(array(
                TaskDBObject::COL_NUMBER
            )
        );
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __fix_task_numbers($projectNumber)
    {
        $tasks = $this->__get_tasks_by_project($projectNumber);
        $n = 1;
        foreach ($tasks as $task) {
            if ($task->getNumber() != $n) {
                if (!$this->__update_task_number($task->getId(), $n)) {
                    return false;
                }
            }
            $n++;
        }
        return true;
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
 * @brief Task object interface
 */
class TaskDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "task";
    // --- tables
    const TABL_TASK = "dol_task";
    const TABL_DELIVERY = "dol_delivery";
    // --- columns
    const COL_ID = "id";
    const COL_PROJECT_NUMBER = "project_number";
    const COL_NUMBER = "number";
    const COL_NAME = "name";
    const COL_DESCRIPTION = "description";
    const COL_JEH_AMOUNT = "jeh_amount";
    const COL_JEH_COST = "jeh_cost";
    const COL_START_DATE = "start_date";
    const COL_END_DATE = "end_date";
    const COL_ENDED = "ended";
    const COL_BILLED = "billed";
    const COL_PAYMENT_DATE = "payment_date";
    const COL_TASK_ID = "task_id";
    const COL_CONTENT = "content";
    const COL_DELIVERED = "delivered";
    const COL_DELIVERY_DATE = "delivery_date";
    const COL_PAID = "paid";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, TaskDBObject::OBJ_NAME);

        // -- create tables

        // --- dol_task table
        $dol_task = new DBTable(TaskDBObject::TABL_TASK);
        $dol_task
            ->AddColumn(TaskDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(TaskDBObject::COL_PROJECT_NUMBER, DBTable::DT_INT, 11, false, "")
            ->AddColumn(TaskDBObject::COL_NUMBER, DBTable::DT_INT, 11, false, "")
            ->AddColumn(TaskDBObject::COL_NAME, DBTable::DT_VARCHAR, 50, false, "")
            ->AddColumn(TaskDBObject::COL_DESCRIPTION, DBTable::DT_TEXT, -1, false, "")
            ->AddColumn(TaskDBObject::COL_JEH_AMOUNT, DBTable::DT_INT, 11, false, 0)
            ->AddColumn(TaskDBObject::COL_JEH_COST, DBTable::DT_INT, 11, false, 0)
            ->AddColumn(TaskDBObject::COL_START_DATE, DBTable::DT_DATE, -1, false, "")
            ->AddColumn(TaskDBObject::COL_END_DATE, DBTable::DT_DATE, -1, true, "")
            ->AddColumn(TaskDBObject::COL_ENDED, DBTable::DT_INT, 1, false, 0)
            ->AddForeignKey(TaskDBObject::TABL_TASK . '_fk1', TaskDBObject::COL_PROJECT_NUMBER, ProjectDBObject::TABL_PROJECT, ProjectDBObject::COL_NUMBER, DBTable::DT_RESTRICT, DBTable::DT_CASCADE);

        $dol_delivery = new DBTable(TaskDBObject::TABL_DELIVERY);
        $dol_delivery
            ->AddColumn(TaskDBObject::COL_ID, DBTable::DT_INT, 11, false, "", true, true)
            ->AddColumn(TaskDBObject::COL_TASK_ID, DBTable::DT_INT, 11, false, "")
            ->AddColumn(TaskDBObject::COL_NUMBER, DBTable::DT_INT, 11, false, "")
            ->AddColumn(TaskDBObject::COL_CONTENT, DBTable::DT_VARCHAR, 255, false, "")
            ->AddColumn(TaskDBObject::COL_DELIVERED, DBTable::DT_INT, 1, false, 0)
            ->AddColumn(TaskDBObject::COL_DELIVERY_DATE, DBTable::DT_VARCHAR, 255, true, null)
            ->AddColumn(TaskDBObject::COL_BILLED, DBTable::DT_INT, 1, false, 0)
            ->AddColumn(TaskDBObject::COL_PAID, DBTable::DT_INT, 1, false, 0)
            ->AddColumn(TaskDBObject::COL_PAYMENT_DATE, DBTable::DT_VARCHAR, 255, true, null)
            ->AddForeignKey(TaskDBObject::TABL_DELIVERY . '_fk1', TaskDBObject::COL_TASK_ID, TaskDBObject::TABL_TASK, TaskDBObject::COL_ID, DBTable::DT_RESTRICT, DBTable::DT_CASCADE)
            ->AddUniqueColumns(array(TaskDBObject::COL_TASK_ID, TaskDBObject::COL_NUMBER));

        // -- add tables
        parent::addTable($dol_task);
        parent::addTable($dol_delivery);

    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new TaskServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new TaskServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
