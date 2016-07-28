<?php

require_once "interfaces/AbstractManager.php";

/**
 *    This manager takes care of Doletic wrappers (plugins)
 */
class WrapperManager extends AbstractManager
{

    // -- attributes
    private $wrappers = null;

    // -- functions

    public function __construct(&$kernel)
    {
        parent::__construct($kernel);
        $this->wrappers = array();
    }

    public function Init()
    {
        // nothing to do here
    }

    public function RegisterWrappers($wrappers)
    {
        $this->wrappers = $wrappers;
    }

    /**
     *
     */
    public function GetWrapper($name)
    {
        $wrapper = null;
        if (array_key_exists($name, $this->wrappers)) {
            $wrapper = $this->wrappers[$name];
        }
        return $wrapper;
    }
}