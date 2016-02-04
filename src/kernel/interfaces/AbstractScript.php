<?php

/**
 *	An interface for a generic script executable function
 */
abstract class AbstractFunction {
	
	// -- consts
	const LOG_TIME_FORMAT = 'H:i:s';
	// -- attributes
	private $script;	// reference to parent script (in order to access flag shared between functions)
	private $name;		// function name
	private $short_opt; // short opt, example '-h' for help function
	private $long_opt;	// long opt, example '--help' for help function
	private $description;
	// -- functions

	public function __construct($script, $name, $shortOpt, $longOpt = "", $description = "no-description") {
		$this->script = $script;
		$this->name = $name;
		$this->short_opt = $shortOpt;
		$this->long_opt = $longOpt;
		$this->description = $description;
	}
	/**
	 *	This is the name of the function
 	 */
	public function GetName() {
		return $this->name;
	}
	/**
	 *	This is the short version of the option corresponding to this function
	 */
	public function GetShortOpt() {
		return $this->short_opt;
	}
	/**
	 *	This returns the long version of the option corresponding to this function
	 */
	public function GetLongOpt() {
		return $this->long_opt;
	}
	/**
	 *	Convenient function required by script
	 */
	public function GetOpts() {
		$opts = $this->short_opt;
		if(strlen($this->long_opt) > 0) {
			$opts .= ", ".$this->long_opt;
		}
		return $opts;
	}
	/**
	 *	Returns the description of the function
	 */
	public function GetDescription() {
		return $this->description;
	}
	/**
 	 *	This is what the function do.
	 */
	abstract public function Execute();

//
// ---------------- PRIVATE & PROTECTED ----------------
//
	/**
	 *	Quick access function to access parent script
	 */
	protected function script() {
		return $this->script;
	}
	/**
	 *	Some usefull logging functions
	 */
	protected function log($lvl, $message) {
		echo sprintf("(%s)[%s]{function:%s} - %s", 
			date(AbstractFunction::LOG_TIME_FORMAT), $lvl, $this->name, $message)."\n";
	}
	protected function partial_log($lvl, $message) {
		echo sprintf("(%s)[%s]{function:%s} - %s", 
			date(AbstractFunction::LOG_TIME_FORMAT), $lvl, $this->name, $message);
	}
	protected function trace($msg, $partial = false) 	
	{ if($partial){$this->partial_log('TRACE', $msg);}else{$this->log('TRACE', $msg);} }
	protected function debug($msg, $partial = false) 	
	{ if($partial){$this->partial_log('DEBUG', $msg);}else{$this->log('DEBUG', $msg);} }
	protected function info($msg, $partial = false) 	
	{ if($partial){$this->partial_log('INFO', $msg);}else{$this->log('INFO', $msg);} }
	protected function warn($msg, $partial = false) 	
	{ if($partial){$this->partial_log('WARN', $msg);}else{$this->log('WARN', $msg);} }
	protected function error($msg, $partial = false) 	
	{ if($partial){$this->partial_log('ERROR', $msg);}else{$this->log('ERROR', $msg);} }
	protected function fatal($msg, $partial = false) 	
	{ if($partial){$this->partial_log('FATAL', $msg);}else{$this->log('FATAL', $msg);} }
	protected function endlog($msg) 	{ echo "$msg\n"; }

}
/**
 *	Default script help function
 */
class DefaultHelpFunction extends AbstractFunction {
	public function __construct($script) {
		parent::__construct($script, 'DefaultHelp', '-h', '--help', "Default help option,\nIt prints this message.");
	}
	public function Execute() {
		echo parent::script()->GetDescription();
		echo parent::script()->GetAvailableOptions();
	}
}
/**
 *	An interface for a generic script
 */
abstract class AbstractScript {

	// -- attributes
	private $name;					// script's name
	private $description;			// script's description
	private $arg_c;					// arguments count
	private $arg_v;					// arguments vector
	private $require_opts;  		// flag used to specify if script can be run without arguments or not
	private $functions;				// functions = array('<short_arg>', array(<AbstractFunction>, ...))
	private $flags;					// internal script flags
	private $opt_translation_table; // translation table = array('<long_arg>', '<short_arg>')
	private $excluded;				// excluded short args of run all
	// -- functions

	public function __construct($argV, $name, $requireOpts = true, $description = "no-script-description") {
		// -- intialize attributes
		$this->name = $name;
		$this->description = $description;
		$this->arg_c = sizeof($argV);
		$this->arg_v = $argV;
		$this->require_opts = $requireOpts;
		$this->functions = array();
		$this->flags = array();
		$this->opt_translation_table = array();
		// -- add default functions
		$h_f = new DefaultHelpFunction($this);
		$this->addFunction($h_f);
		$this->excluded = array($h_f->GetShortOpt());
	}

	/**
	 *	This is the entry point of the script
	 */
	public function Run() {
		echo "-- running --\n";
		// If script has less than 2 options
		if($this->arg_c < 2) {
			// If script require at least 1 option more
			if($this->require_opts) {
				$this->__require_opts();
			} // if script doesn't need more options, default behaviour is to run every functions 
			else {
				$this->__run_all();	
			}
		} // Else script has at least one option 
		else {
			// for each option given, 
			for ($i=1; $i < sizeof($this->arg_v); $i++) { 
				// if it can be translated,
				if(array_key_exists($this->arg_v[$i], $this->opt_translation_table)) {
					// run its translated version
					$this->__run($this->opt_translation_table[$this->arg_v[$i]]);
				} else {
					// run it directly
					$this->__run($this->arg_v[$i]);
				}
			}
		}
		echo "\n-- stopped --\n";
	}
	/**
	 *	This function is required by AbstractFunction to set flags
	 */
	public function SetFlag($name, $value = true) {
		if(array_key_exists($name, $this->flags)) {
			$this->warning("Overriding shared flag '$name' with value '$value' !");
		}
		$this->flags[$name] = $value;	
	}
	/**
	 *	This function is required by AbstractFunction to get flags
	 */
	public function GetFlag($name) {
		$flag = false;
		if(array_key_exists($name, $this->flags)) {
			$flag = $this->flags[$name];
		} else {
			$this->warning("Flag '$name' required but inexistant, FALSE returned !");
		}
		return $flag;
	}
	/**
	 *	Returns the description of the script
	 */
	public function GetDescription() {
		return "Name :\n\t".$this->name."\n\nDescription :\n\t".$this->description."\n\n";
	}
	/**
	 *	Convenient function to get a string with options and their description automatically	
	 */
	public function GetAvailableOptions() {
		$string = "No options avaliable for this script.";
		if(sizeof($this->functions) > 0) {
			$string = "Available options for this script are :\n\n";
			foreach ($this->functions as $functions) {
				$string .= "\t+ ".$functions[0]->GetOpts()." :\n";
				foreach ($functions as $f) {
					$string .= "\t\t- ".str_replace("\n", "\n\t\t  ", $f->GetDescription())."\n";
				}
				$string .= "\n";
			}	
		}
		return $string;
	}
//
// ---------------- PRIVATE & PROTECTED ----------------
//
	/**
	 *	This function is internally called if script requires at 
	 *	least one option and is called with no option at all
	 */
	private function __require_opts() {
		$this->info("This script requires some options. Run it again with '--help' or '-h' option.");
	}
	/**
	 *	This function runs a given set of functions based on option
	 */
	private function __run($opt) {
		if(array_key_exists($opt, $this->functions)) {
			foreach ($this->functions[$opt] as $f) {
				$f->Execute();
			}
		} else {
			$this->error("Unknown option '$opt' !");
		}
	}
	/**
	 *	This function run all script functions
	 */
	private function __run_all() {
		foreach ($this->functions as $key => $functions) {
			if(!in_array($key, $this->excluded)) {
				foreach ($functions as $f) {
					$f->Execute();
				}
			}
		}
	}
	/**
	 *	This function adds a function to the script
	 */
	protected function addFunction($function, $override = false) {
		// update opt translation table if needed
		if(strlen($function->GetLongOpt()) > 0) {
			$this->opt_translation_table[$function->GetLongOpt()] = $function->GetShortOpt();
		}
		// add function to function hash table
		if( !$override && array_key_exists($function->GetShortOpt(), $this->functions) ) {
			array_push($this->functions[$function->GetShortOpt()], $function);
		} else {
			$this->functions[$function->GetShortOpt()] = array($function);
		}
	}
	/**
	 *	Some usefull logging functions
	 */
	protected function log($lvl, $message) {
		echo sprintf("(%s)[%s]{script:%s} - %s", 
			date(AbstractFunction::LOG_TIME_FORMAT), $lvl, $this->name, $message)."\n";
	}
	protected function trace($msg) 		{ $this->log('TRACE', $msg); }
	protected function debug($msg) 		{ $this->log('DEBUG', $msg); }
	protected function info($msg) 		{ $this->log('INFO', $msg); }
	protected function warning($msg) 	{ $this->log('WARN', $msg); }
	protected function error($msg) 		{ $this->log('ERROR', $msg); }
	protected function fatal($msg) 		{ $this->log('FATAL', $msg); }
}