<?php

require_once "DoleticKernel.php";

// create a kernel
$kernel = new DoleticKernel();
$kernel->Init();
// update database
$kernel->ConnectDB();
$kernel->UpdateDatabase();
$kernel->DisconnectDB();
// end
die("Installation done !\n");