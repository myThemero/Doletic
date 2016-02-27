<?php

require_once "interfaces/AbstractCronTask.php";

class MailSyncTask extends AbstractCronTask {

	// -- consts

	// -- attributes

	// -- functions
	public function __construct() {
		parent::__construct(
			"mail_sync", // cette tache se nomme mail_sync
			"01 1 * * *" // et s'execute tous les jours à 01:01
		);
	}

	public function Run() {
		echo parent::GetName()." -> RUN !";
	}

}

// --- REGISTER TASK ---------------------------------
CronTaskLoader::RegisterTask(new MailSyncTask());