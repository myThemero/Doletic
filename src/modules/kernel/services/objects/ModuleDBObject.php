<?php

require_once "interfaces/AbstractDBObject.php";
require_once "interfaces/AbstractObjectServices.php";
require_once "objects/DBProcedure.php";
require_once "objects/DBTable.php";

/**
 * @brief The Module class
 */
class Module implements \JsonSerializable
{

    // -- consts
    const NO_RIGHTS = 0;
    const G_RIGHTS = 1;
    const U_RIGHTS = 2;
    const A_RIGHTS = 3;
    const SA_RIGHTS = 4;

    // -- attributes
    // --- persistent
    private $label = null;
    private $name = null;
    private $version = null;
    private $authors = null;
    private $dependencies = null;
    private $enabled = null;

    /**
     * @brief Constructs a module
     */
    public function __construct($label, $name, $version, $authors, $dependencies, $enabled)
    {
        $this->label = $label;
        $this->name = $name;
        $this->version = $version;
        $this->authors = $authors;
        $this->dependencies = $dependencies;
        $this->enabled = $enabled;
    }

    public function jsonSerialize()
    {
        return [
            ModuleDBObject::COL_LABEL => $this->label,
            ModuleDBObject::COL_NAME => $this->name,
            ModuleDBObject::COL_VERSION => $this->version,
            ModuleDBObject::COL_AUTHORS => $this->authors,
            ModuleDBObject::COL_DEPEND => $this->dependencies,
            ModuleDBObject::COL_ENABLED => $this->enabled
        ];
    }

    /**
     * @brief Returns module id
     * @return string
     */
    public function GetId()
    {
        return $this->label;
    }

    /**
     * @brief Returns module name
     * @return string
     */
    public function GetName()
    {
        return $this->name;
    }

    /**
     * @brief Returns module version
     * @return string
     */
    public function GetVersion()
    {
        return $this->version;
    }

    /**
     * @brief Returns module authors
     * @return string
     */
    public function GetAuthors()
    {
        return $this->authors;
    }

    /**
     * @brief Returns module authors
     * @return string
     */
    public function GetDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @brief Returns module authors
     * @return string
     */
    public function IsEnabled()
    {
        $str = var_export($this->enabled, true);
        return ($str !== "false" && $str !== "0");
    }
}


class ModuleServices extends AbstractObjectServices
{

    // -- consts
    // --- params keys
    const PARAM_LABEL = "label";
    const PARAM_NAME = "name";
    const PARAM_VERSION = "version";
    const PARAM_AUTHORS = "authors";
    const PARAM_DEPEND = "dependencies";
    const PARAM_ENABLED = "enabled";
    // --- internal services (actions)
    const GET_MODULE_BY_LABEL = "bylabel";
    const GET_MODULE_BY_NAME = "byname";
    const GET_ALL_MODULES = "all";
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
        if (!strcmp($action, ModuleServices::GET_MODULE_BY_LABEL)) {
            $data = $this->__get_module_by_label($params[ModuleServices::PARAM_LABEL]);
        } else if (!strcmp($action, ModuleServices::GET_ALL_MODULES)) {
            $data = $this->__get_all_modules();
        } else if (!strcmp($action, ModuleServices::INSERT)) {
            $data = $this->__insert_module(
                $params[ModuleServices::PARAM_LABEL],
                $params[ModuleServices::PARAM_NAME],
                $params[ModuleServices::PARAM_VERSION],
                $params[ModuleServices::PARAM_AUTHORS],
                $params[ModuleServices::PARAM_DEPEND],
                $params[ModuleServices::PARAM_ENABLED]);
        } else if (!strcmp($action, ModuleServices::UPDATE)) {
            $data = $this->__update_module(
                $params[ModuleServices::PARAM_LABEL],
                $params[ModuleServices::PARAM_NAME],
                $params[ModuleServices::PARAM_VERSION],
                $params[ModuleServices::PARAM_AUTHORS],
                $params[ModuleServices::PARAM_DEPEND],
                $params[ModuleServices::PARAM_ENABLED]);
        } else if (!strcmp($action, ModuleServices::DELETE)) {
            $data = $this->__delete_module($params[ModuleServices::PARAM_LABEL]);
        }
        return $data;
    }

# PROTECTED & PRIVATE ####################################################

    // -- consult

    private function __get_module_by_label($label)
    {
        // create sql params array
        $sql_params = array(":" . ModuleDBObject::COL_LABEL => $label);
        // create sql request
        $sql = parent::getDBObject()->GetTable(ModuleDBObject::TABL_MODULE)->GetSELECTQuery(
            array(DBTable::SELECT_ALL), array(ModuleDBObject::COL_LABEL));
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, $sql_params);
        // create module var
        $module = null;
        if (isset($pdos)) {
            if (($row = $pdos->fetch()) !== false) {
                $module = new Module(
                    $row[ModuleDBObject::COL_LABEL],
                    $row[ModuleDBObject::COL_NAME],
                    $row[ModuleDBObject::COL_VERSION],
                    $row[ModuleDBObject::COL_AUTHORS],
                    $row[ModuleDBObject::COL_DEPEND],
                    $row[ModuleDBObject::COL_ENABLED]);
            }
        }
        return $module;
    }

    private function __get_all_modules()
    {
        // create sql request
        $sql = parent::getDBObject()->GetTable(ModuleDBObject::TABL_MODULE)->GetSELECTQuery();
        // execute SQL query and save result
        $pdos = parent::getDBConnection()->ResultFromQuery($sql, array());
        // create an empty array for modules and fill it
        $modules = array();
        if (isset($pdos)) {
            while (($row = $pdos->fetch()) !== false) {
                array_push($modules, new Module(
                    $row[ModuleDBObject::COL_LABEL],
                    $row[ModuleDBObject::COL_NAME],
                    $row[ModuleDBObject::COL_VERSION],
                    $row[ModuleDBObject::COL_AUTHORS],
                    $row[ModuleDBObject::COL_DEPEND],
                    $row[ModuleDBObject::COL_ENABLED]));
            }
        }
        return $modules;
    }

    // -- modify

    private function __insert_module($label, $name, $version, $authors, $dependencies, $enabled)
    {
        // create sql params
        $sql_params = array(
            ":" . ModuleDBObject::COL_LABEL => $label,
            ":" . ModuleDBObject::COL_NAME => $name,
            ":" . ModuleDBObject::COL_VERSION => $version,
            ":" . ModuleDBObject::COL_AUTHORS => $authors,
            ":" . ModuleDBObject::COL_DEPEND => $dependencies,
            ":" . ModuleDBObject::COL_ENABLED => $enabled);
        // create sql request
        $sql = parent::getDBObject()->GetTable(ModuleDBObject::TABL_MODULE)->GetINSERTQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __update_module($label, $name, $version, $authors, $dependencies, $enabled)
    {
        // create sql params
        $sql_params = array(
            ":" . ModuleDBObject::COL_LABEL => $label,
            ":" . ModuleDBObject::COL_NAME => $name,
            ":" . ModuleDBObject::COL_VERSION => $version,
            ":" . ModuleDBObject::COL_AUTHORS => $authors,
            ":" . ModuleDBObject::COL_DEPEND => $dependencies,
            ":" . ModuleDBObject::COL_ENABLED => $enabled);
        // create sql request
        $sql = parent::getDBObject()->GetTable(ModuleDBObject::TABL_MODULE)->GetUPDATEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

    private function __delete_module($label)
    {
        // create sql params
        $sql_params = array(":" . ModuleDBObject::COL_LABEL => $label);
        // create sql request
        $sql = parent::getDBObject()->GetTable(ModuleDBObject::TABL_MODULE)->GetDELETEQuery();
        // execute query
        return parent::getDBConnection()->PrepareExecuteQuery($sql, $sql_params);
    }

# PUBLIC RESET STATIC DATA FUNCTION --------------------------------------------------------------------

    public function ResetStaticData()
    {
        // default modules if needed
        $modules = [
            [
                ModuleDBObject::COL_LABEL => 'hr',
                ModuleDBObject::COL_NAME => 'Ressources Humaines',
                ModuleDBObject::COL_VERSION => '1.0dev',
                ModuleDBObject::COL_AUTHORS => 'Nicolas Sorin',
                ModuleDBObject::COL_DEPEND => 'kernel',
                ModuleDBObject::COL_ENABLED => true
            ],
            [
                ModuleDBObject::COL_LABEL => 'grc',
                ModuleDBObject::COL_NAME => 'Gestion Relation Client',
                ModuleDBObject::COL_VERSION => '1.0dev',
                ModuleDBObject::COL_AUTHORS => 'Olivier Vicente',
                ModuleDBObject::COL_DEPEND => 'kernel',
                ModuleDBObject::COL_ENABLED => true
            ],
            [
                ModuleDBObject::COL_LABEL => 'kernel',
                ModuleDBObject::COL_NAME => 'Kernel',
                ModuleDBObject::COL_VERSION => '1.0dev',
                ModuleDBObject::COL_AUTHORS => 'Paul Dautry',
                ModuleDBObject::COL_DEPEND => '',
                ModuleDBObject::COL_ENABLED => true
            ],
            [
                ModuleDBObject::COL_LABEL => 'tools',
                ModuleDBObject::COL_NAME => 'Outils',
                ModuleDBObject::COL_VERSION => '1.0dev',
                ModuleDBObject::COL_AUTHORS => 'Paul Dautry',
                ModuleDBObject::COL_DEPEND => 'kernel',
                ModuleDBObject::COL_ENABLED => true
            ],
            [
                ModuleDBObject::COL_LABEL => 'support',
                ModuleDBObject::COL_NAME => 'Support',
                ModuleDBObject::COL_VERSION => '1.0dev',
                ModuleDBObject::COL_AUTHORS => 'Paul Dautry',
                ModuleDBObject::COL_DEPEND => 'kernel',
                ModuleDBObject::COL_ENABLED => true
            ],
            [
                ModuleDBObject::COL_LABEL => 'ua',
                ModuleDBObject::COL_NAME => "Unité d'affaires",
                ModuleDBObject::COL_VERSION => '1.0dev',
                ModuleDBObject::COL_AUTHORS => 'Nicolas Sorin',
                ModuleDBObject::COL_DEPEND => 'kernel, grc',
                ModuleDBObject::COL_ENABLED => true
            ]
        ];
        // --- retrieve SQL query
        $sql = parent::getDBObject()->GetTable(ModuleDBObject::TABL_MODULE)->GetINSERTQuery();
        foreach ($modules as $module) {
            // --- execute SQL query
            parent::getDBConnection()->PrepareExecuteQuery($sql, $module);
        }

    }
}

/**
 * @brief Module object interface
 */
class ModuleDBObject extends AbstractDBObject
{

    // -- consts
    // --- object name
    const OBJ_NAME = "module";
    // --- tables
    const TABL_MODULE = "dol_module";
    // --- columns
    const COL_LABEL = "label";
    const COL_NAME = "name";
    const COL_VERSION = "version";
    const COL_AUTHORS = "authors";
    const COL_DEPEND = "dependencies";
    const COL_ENABLED = "enabled";
    // -- attributes

    // -- functions

    public function __construct($module)
    {
        // -- construct parent
        parent::__construct($module, ModuleDBObject::OBJ_NAME);
        // -- create tables
        // --- dol_module table
        $dol_module = new DBTable(ModuleDBObject::TABL_MODULE);
        $dol_module
            ->AddColumn(ModuleDBObject::COL_LABEL, DBTable::DT_VARCHAR, 255, false, "", false, true)
            ->AddColumn(ModuleDBObject::COL_NAME, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(ModuleDBObject::COL_VERSION, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(ModuleDBObject::COL_AUTHORS, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(ModuleDBObject::COL_DEPEND, DBTable::DT_VARCHAR, 255, false)
            ->AddColumn(ModuleDBObject::COL_ENABLED, DBTable::DT_BOOLEAN, -1, false);

        // -- add tables
        parent::addTable($dol_module);
    }

    /**
     * @brief Returns all services associated with this object
     */
    public function GetServices($currentUser)
    {
        return new ModuleServices($currentUser, $this, $this->getDBConnection());
    }

    /**
     *    Initialize static data
     */
    public function ResetStaticData()
    {
        $services = new ModuleServices(null, $this, $this->getDBConnection());
        $services->ResetStaticData();
    }

}
