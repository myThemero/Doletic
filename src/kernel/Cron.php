<?php

require_once "DoleticKernel.php";

// instanciate & initialize kernel
$kernel = new DoleticKernel();
$kernel->Init();
$kernel->ConnectDB();
// run cron tasks
if(isset($argv[1]) && $argv[1] === "-l") {
	$kernel->ListCronTasks();
} else {
	$kernel->RunCron();	
}
// disconnect & destroy
$kernel->DisconnectDB();
$kernel = null;

