<?php

/**
 * @brief
 */
abstract class AbstractManager
{

    // -- attributes
    private $kernel = null;

    // -- functions

    public function __construct(&$kernel)
    {
        $this->kernel = $kernel;
    }

    protected function kernel()
    {
        return $this->kernel;
    }

}