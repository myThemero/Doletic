<?php

require_once "interfaces/AbstractManager.php";

/**
 * 	This manager takes care of web pages generation
 */
class UIManager extends AbstractManager {
	
	// -- consts
	const INTERFACE_LOGIN = "login";
	const INTERFACE_LOGIN_FAILED = "login_failed";
	const INTERFACE_LOGOUT = "logout";
	const INTERFACE_404 = "404";
	const INTERFACE_HOME = "home";

	// -- attributes 
	private $internal_css;
	private $internal_js;
	private $special_uis;
	// -- functions
	/**
	 *
	 */
	public function __construct(&$kernel) {
		parent::__construct($kernel);
		// internal css
		$this->internal_css = array();
		// internal js
		$this->internal_js = array();
		// -- special uis
	 	$this->special_uis = array();
	}
	/**
	 *	Initi
	 */
	public function Init() {
		// add css
		array_push($this->internal_css, "ui/semantic/dist/semantic.min.css");
		array_push($this->internal_css, "ui/css/doletic.css");
		// add js
		array_push($this->internal_js, "ui/js_depends/jquery-2.2.0.min.js");
		array_push($this->internal_js, "ui/semantic/dist/semantic.min.js");
		array_push($this->internal_js, "ui/js/doletic.js");
		// add specials
		$this->special_uis[UIManager::INTERFACE_LOGIN] = "ui/js/login.js";
		$this->special_uis[UIManager::INTERFACE_LOGIN_FAILED] = "ui/js/login_failed.js";
		$this->special_uis[UIManager::INTERFACE_LOGOUT] = "ui/js/logout.js";
		$this->special_uis[UIManager::INTERFACE_404] = "ui/js/404.js";
		$this->special_uis[UIManager::INTERFACE_HOME] = "ui/js/home.js";
	}
	/**
	 *	Creates a standard Doletic page including $js scripts and $css stylesheets
	 */
	public function MakeUI($js, $css) {
		// create page and add start
		$page = "
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>Doletic</title>";
		// add css entries
		foreach (array_merge($this->internal_css, $css) as $value) {
			$page .= "\n		<link rel=\"stylesheet\" type=\"text/css\" href=\"$value\" />";
		}
		// add js entries
		foreach (array_merge($this->internal_js, $js) as $value) {
			$page .= "\n		<script src=\"$value\" type=\"text/javascript\"></script>";	
		}
		// append page bottom
		$page .= "
	</head>
	<body>
		<div id=\"doletic_main_container\">
	 		<!-- This element's contents will be replaced with your component. -->
		</div>
	</body>
	<footer>
	</footer>
</html>\n\n";
		return $page;
	}
	/**
	 *	Returns true if $ui refer to a builtin Doletic page
	 */
	public function IsSpecialUI($ui) {
		return array_key_exists($ui, $this->special_uis);
	}
	/**
	 *	Returns a kernel page, its a Doletic native page, not created by a module.
	 */
	public function MakeSpecialUI($ui) {
		$page = null;
		if(array_key_exists($ui, $this->special_uis)) {
			$page  = $this->MakeUI(array($this->special_uis[$ui]), array());
		}
		return $page;
	}
	/**
	 *	Returns 404 not found Doletic page
	 */
	public function Make404UI() {
		return $this->MakeSpecialUI(UIManager::INTERFACE_404);
	}


}