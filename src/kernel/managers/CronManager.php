<?php 

require_once "interfaces/AbstractManager.php";

/**
* 	This manager is charge of managing cron tasks
*/
class CronManager extends AbstractManager {

	// -- attributes 
	private $tasks;

	// -- functions

	public function __construct(&$kernel) {
		parent::__construct($kernel);
		$this->tasks = array();
	}

	public function Init() {
		/// \todo implement here
	}

	public function RegisterTask($task) {
		array_push($this->tasks, $task);
	}

	public function RunTasks() {
		/// \todo implement here
	}
}