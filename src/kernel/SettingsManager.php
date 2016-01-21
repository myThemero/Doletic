<?php

class SettingsManager {

	// -- attributes
	private $settings;

	// -- functions

	public function __construct() {
		$this->settings = array();
	}

	public function Init() {
		/// \todo implement here
	}

	public function RegisterSetting($key, $value) {
		$ok = false;
		if($this->settings[$key] == null) {
			$this->setting[$key] = $value;
			$ok = true;
		}
		return $ok;
	}

	public function UnregisterSetting($key) {
		$this->settings[$key] = null;
	}

	public function GetSettingValue($key) {
		return $this->settings[$key];
	}
}