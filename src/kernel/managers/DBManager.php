<?php

require_once "interfaces/AbstractManager.php";
require_once "objects/DB.php";

/**
 *    This manager takes care of interaction with databases
 */
class DBManager extends AbstractManager
{

    // -- attributes
    private $databases = null;

    // -- function
    /**
     *
     */
    public function __construct(&$kernel)
    {
        parent::__construct($kernel);
        $this->databases = array();
    }

    /**
     *
     */
    public function Init()
    {
        // create main doletic database
        $db = new DB(
            $this,
            parent::kernel()->SettingValue(SettingsManager::KEY_DBENGINE),
            parent::kernel()->SettingValue(SettingsManager::KEY_DBHOST),
            parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME),
            parent::kernel()->SettingValue(SettingsManager::KEY_DBUSER),
            parent::kernel()->SettingValue(SettingsManager::KEY_DBPWD));
        // register main doletic database
        $this->RegisterDatabase($db);
    }

    /**
     *
     */
    public function InitAllConnections()
    {
        // connect all databases
        foreach ($this->databases as $dbname => $db) {
            $db->Connect();
        }
    }

    /**
     *
     */
    public function CloseAllConnections()
    {
        // disconnect all databases
        foreach ($this->databases as $dbname => $db) {
            $db->Disconnect();
        }
    }

    /**
     *
     */
    public function RegisterDatabase($db)
    {
        $ok = false;
        if (!array_key_exists($db->GetName(), $this->databases)) {
            $this->databases[$db->GetName()] = $db;
            $ok = true;
        }
        return $ok;
    }

    /**
     *
     */
    public function GetOpenConnectionTo($name)
    {
        return $this->databases[$name];
    }

    /**
     *
     */
    public function DebuggingModeEnabled()
    {
        return parent::kernel()->SettingValue(SettingsManager::KEY_DBDEBUG);
    }
}