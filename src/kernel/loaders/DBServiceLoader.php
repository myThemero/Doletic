<?php

require_once "interfaces/AbstractLoader.php";

/**
 * @brief
 */
class DBServiceLoader extends AbstractLoader
{

    // -- consts

    // -- attributes
    private $services = null;

    // -- functions

    public function __construct(&$kernel, &$manager)
    {
        parent::__construct($kernel, $manager);
        $this->services = array();
    }

    public function Init($modulesDBServices)
    {
        // -- add modules db objects
        foreach ($modulesDBServices as $dbsname => $dbs) {
            $this->services[$dbsname] = $dbs;
        }
        // -- init db objects connections
        $this->__connect_db_services();
    }

    public function GetDBService($key)
    {
        $service = null;
        if (array_key_exists($key, $this->services)) {
            $service = $this->services[$key];
        }
        $service->setCurrentUser($this->kernel()->GetCurrentUser());
        return $service;
    }

# PROTECTED & PRIVATE ######################################################

    private function __connect_db_services()
    {
        foreach ($this->services as $dbs) {
            $dbs->SetDBConnection(
                $this->manager()->getOpenConnectionTo(
                    parent::kernel()->SettingValue(SettingsManager::KEY_DBNAME)));
        }
    }

}