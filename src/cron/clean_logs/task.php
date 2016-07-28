<?php

require_once "interfaces/AbstractCronTask.php";

class CleanLogsTask extends AbstractCronTask
{

    // -- consts

    // -- attributes

    // -- functions
    public function __construct()
    {
        parent::__construct(
            "clean_logs",
            "02 2 * * *" // execution tous les jours à 02:02
        );
    }

    public function Run()
    {
        echo parent::GetName() . " -> RUN !";
    }

}

// --- REGISTER TASK ---------------------------------
CronTaskLoader::RegisterTask(new CleanLogsTask());