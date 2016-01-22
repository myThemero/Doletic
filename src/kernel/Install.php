<?php

require_once "DoleticKernel.php";

// create a kernel
$kernel = new DoleticKernel();
$kernel->Init();
// setup database
$kernel->ConnectDB();
$kernel->ResetDatabase();
$kernel->DisconnectDB();
// end
die("Installation done !\n");
