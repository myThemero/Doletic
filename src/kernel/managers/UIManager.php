<?php

require_once "interfaces/AbstractManager.php";

/**
 * 	This manager takes care of web pages generation
 */
class UIManager extends AbstractManager {
	
	// -- consts
	const INTERFACE_LOGIN = "kernel:login";
	const INTERFACE_LOGOUT = "kernel:logout";
	const INTERFACE_LOST = "kernel:lost";
	const INTERFACE_RESTORED = "kernel:restored";
	const INTERFACE_404 = "kernel:404";
	const INTERFACE_HOME = "kernel:home";
	const INTERFACE_TEST = "kernel:test";

	// -- attributes 
	private $internal_css;
	private $internal_js;
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
	}
	/**
	 *	Initi
	 */
	public function Init($modulesJSServices) {
		// add css
		$this->__init_css();
		// add js
		$this->__init_js($modulesJSServices);
	}
	public function MakeHTMLBase() {
				$page = "
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>
		<title>Doletic</title>";
		// add css entries
		foreach (array_merge($this->internal_css) as $value) {
			$page .= "\n		<link rel=\"stylesheet\" type=\"text/css\" href=\"$value\" />";
		}
		// add js entries
		foreach (array_merge($this->internal_js) as $value) {
			$page .= "\n		<script src=\"$value\" type=\"text/javascript\"></script>";	
		}
		// append page bottom
		$page .= "
	</head>
	<body id=\"body\">
	</body>
	<footer>
	</footer>
</html>\n\n";
		return $page;
	}
	/**
	 *	Creates a standard Doletic page including $js scripts and $css stylesheets
	 */
	public function MakeUI($js, $css) {
		// create page and add start
		$html_fragment = "";
		// add css entries
		foreach ($css as $value) {
			$html_fragment .= "<link class=\"doletic_subscript\" rel=\"stylesheet\" type=\"text/css\" href=\"$value\" />\n";
		}
		// add js entries
		foreach ($js as $value) {
			$html_fragment .= "<script class=\"doletic_subscript\" src=\"$value\" type=\"text/javascript\"></script>\n";	
		}
		return json_encode(array("module_scripts" => $html_fragment));
	}
	/**
	 *	Returns 404 not found Doletic page
	 */
	public function Make404UI() {
		return $this->MakeUI(array("ui/js/404.js"),array("ui/css/404.css"));
	}

# PROTECTED & PRIVATE ################################################################

	private function __init_css() {
		array_push($this->internal_css, "ui/semantic/dist/semantic.min.css");
		array_push($this->internal_css, "ui/css/doletic.css");
	}

	private function __init_js($modulesJSServices) {
		// add kernel scripts
		array_push($this->internal_js, "ui/js_depends/jquery-2.2.0.min.js");
		array_push($this->internal_js, "ui/js_depends/plotly-1.5.0.min.js");
		array_push($this->internal_js, "ui/semantic/dist/semantic.min.js");
		array_push($this->internal_js, "services/doletic_services.js");
		array_push($this->internal_js, "ui/js/abstract_doletic_module.js");
		array_push($this->internal_js, "ui/js/doletic_utils.js");
		array_push($this->internal_js, "ui/js/doletic_config.js");
		array_push($this->internal_js, "ui/js/doletic.js");
		// merge with modules services scripts
		$this->internal_js = array_merge($this->internal_js, $modulesJSServices);
	}
}