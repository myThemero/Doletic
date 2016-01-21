<?php

class LogMessage {

	// -- consts
	const string LVL_FATAL = "FATAL";
	const string LVL_CRITICAL = "CRITICAL";
	const string LVL_ERROR = "ERROR";
	const string LVL_WARNING = "WARNING";
	const string LVL_INFO = "INFO";
	const string LVL_DEBUG = "DEBUG";
	// -- attributes
	private $sender;
	private $content;
	private $level;
	private $date;

	// -- function

	public function __construct($sender, $content, $level = LVL_INFO) {
		$this->sender = $sender;
		$this->content = $content;
		$this->level = $level;
		$this->date = date(DateTime::ISOISO8601);
	}

	public function ToString($format) {
		$msg = str_replace("%l", $this->level, $msg);
		$msg = str_replace("%d", $this->date, $msg);
		$msg = str_replace("%c", $this->content, $msg);
		$msg = str_replace("%s", $this->sender, $msg);
		return $msg;
	}

}