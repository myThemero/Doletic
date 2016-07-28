<?php

/**
 * @brief
 */
abstract class AbstractCronTask
{

    // -- attributes
    private $name = null;
    private $frequency = null;

    // -- functions

    public function GetName()
    {
        return $this->name;
    }

    public function GetFrequency()
    {
        return $this->frequency;
    }

    abstract public function Run();

# PROTECTED & PRIVATE #########################################

    protected function __construct($name, $frequency)
    {
        $this->name = $name;
        $this->frequency = $frequency;
    }
}