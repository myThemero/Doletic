<?php

require_once "interfaces/AbstractLoader.php";
// load all modules descriptions
foreach (new DirectoryIterator(CronTaskLoader::TASKS_DIR) as $fileInfo) {
    if ($fileInfo->isDot()) continue;
    if ($fileInfo->isDir()) {
        require_once CronTaskLoader::TASKS_DIR . "/" . $fileInfo->getFilename() . "/task.php";
    }
}

/**
 * @brief
 */
class CronTaskLoader extends AbstractLoader
{

    // -- consts
    const TASKS_DIR = "../cron";
    // -- static
    public static $_TASKS = array();

    // -- functions

    public function __construct(&$kernel, &$manager)
    {
        // -- construct parent
        parent::__construct($kernel, $manager);
    }

    /**
     *
     */
    public function Init()
    {
        parent::manager()->RegisterTasks(CronTaskLoader::$_TASKS);
    }

    /**
     *
     */
    public static function RegisterTask($task)
    {
        CronTaskLoader::$_TASKS[$task->GetName()] = $task;
    }
}