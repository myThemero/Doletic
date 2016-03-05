<?php

require_once "interfaces/AbstractManager.php";

/**
* 	This manager takes care of Doletic logs in database
*/
class LogManager extends AbstractManager {
	
	// -- attributes
	private $loggers = null;

	// -- functions

	public function __construct(&$kernel) {
		parent::__construct($kernel);
	}

	public function Init() {
		// nothing to do here
	}

	public function LogInfo($script, $message) {
		$this->_log(Log::CRIT_INFO, $script, $message);
	}

	public function LogWarning($script, $message) {
		$this->_log(Log::CRIT_WARN, $script, $message);
	}

	public function LogError($script, $message) {
		$this->_log(Log::CRIT_ERRO, $script, $message);
	}

	public function LogFatal($script, $message) {
		$this->_log(Log::CRIT_FATA, $script, $message);
	}

# PROTECTED & PRIVATE #####################################

	private function _log($criticity, $script, $message) {
		parent::kernel()->GetDBObject(LogDBObject::OBJ_NAME)->GetServices(parent::kernel()->GetCurrentUser())
			->GetResponseData(LogServices::INSERT, array(
				LogServices::PARAM_CRITICITY => $criticity,
				LogServices::PARAM_SCRIPT    => $script,
				LogServices::PARAM_MESSAGE   => $message
				)
			);
	}
}
