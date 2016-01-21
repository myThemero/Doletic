<?php 


class CronManager {

	// -- attributes 
	private $tasks;

	// -- functions

	public function __construct() {
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