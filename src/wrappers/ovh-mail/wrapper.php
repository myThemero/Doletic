<?php

require_once "interfaces/AbstractWrapper.php";
require_once "../wrappers/utils/DoleticOVHAPIConnector.php";

/**
 *	Interface principale du wrapper de couplage avec OVH concernant les mails
 */
class OVHMailWrapper extends AbstractWrapper {

	// -- consts
	// --- wrapper metadata related consts
	const NAME = "ovh-mail";
	const VERSION = "1.0dev";
	const AUTHORS = array(
		"Paul Dautry"
		);
	// --- wrapper functions related consts
	const FUNC_LIST_MAILLIST = "list_maillist";

	// --functions

	public function __construct() {
		// creation du parent avec les informations adéquates
		parent::__construct(
			OVHMailWrapper::NAME,
			OVHMailWrapper::VERSION,
			OVHMailWrapper::AUTHORS);
		// enregistrement des fonctions du wrapper
		parent::addFunction(OVHMailWrapper::FUNC_LIST_MAILLIST, new ListMaillistFunction($this));
	}

}

/**
* Cette classe décrit la fonction du wrapper permettant de lister les mailing lists existantes
*/
class ListMaillistFunction extends AbstractWrapperFunction {
	
	// -- consts
	// --- function name
	const FUNC_NAME = "ListMaillistFunction";
	// --- function expected arguments
	const ARG_DOMAIN = "domain"; 
	// -- attributes

	// -- functions

	public function __construct($wrapper) {
		parent::__construct($wrapper, ListMaillistFunction::FUNC_NAME);
	}

	/**
	 *	@override
	 */
	public function ExpectedArgs() {
		return array(ListMaillistFunction::ARG_DOMAIN);
	}
	/**
	 *	@override
	 */
	public function Run($args = array()) {
		$ok = false;
		try {
			$connector = new DoleticOVHAPIConnector(parent::wrapper()->GetKernel());
			// retrieve args
			$domain = $args[ListMaillistFunction::ARG_DOMAIN];
			// execute request
			$mailists = $connector->get("/email/domain/".$domain."/mailingList");
			// store results
			parent::setResult($mailists);
			// set ok flag up
			$ok = true;
		} catch ( Exception $e ) {
			parent::updateLastError($e->getMessage());
		}	
		// return status
		return $ok;
	}
}

# ENREGISTREMENT DU WRAPPER AUPRES DU LOADER ###############################################################

WrapperLoader::RegisterWrapper( new OVHMailWrapper() );