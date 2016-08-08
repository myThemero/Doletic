<?php

require_once "objects/DBTable.php";
require_once "managers/DBManager.php";
require_once "managers/SettingsManager.php";

/**
 * @brief
 */
abstract class AbstractDBService
{

    // -- attributes
    private $module = null;
    private $db_connection = null;
    private $name = null;
    private $current_user = null;

    // -- functions

    public function setCurrentUser($currentUser)
    {
        $this->current_user = $currentUser;
        return $this;
    }

    /**
     * @brief Returns DBService name
     */
    public function GetName()
    {
        return $this->name;
    }

    public function GetModule()
    {
        return $this->module;
    }

    abstract public function GetResponseData($action, $params);

    public function SetDBConnection($dbConnection)
    {
        $this->db_connection = $dbConnection;
    }

# PROTECTED & PRIVATE #########################################################

    protected function __construct($module, $name)
    {
        $this->module = $module;
        $this->name = $name;
    }

    protected function getCurrentUser()
    {
        return $this->current_user;
    }

    protected function getDBConnection()
    {
        return $this->db_connection;
    }

    protected function getDBObjectResponseData($object, $action, $params)
    {
        return $this->module->GetDBObject($object)
            ->GetServices($this->current_user)
            ->GetResponseData($action, $params);
    }

}