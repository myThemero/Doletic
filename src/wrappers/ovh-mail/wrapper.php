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
	const FUNC_UPDATE_MAILLIST = "update_maillist";

	// -- attributes

	// --functions

	public function __construct() {
		// creation du parent avec les informations adéquates
		parent::__construct(
			OVHMailWrapper::NAME,
			OVHMailWrapper::VERSION,
			OVHMailWrapper::AUTHORS);
		// enregistrement des fonctions du wrapper
		parent::addFunction(OVHMailWrapper::FUNC_UPDATE_MAILLIST, new UpdateMaillistFunction($this));
	}

}

/**
* Cette classe décrit la fonction du wrapper permettant de mettre à jour les mailing listes
*/
class UpdateMaillistFunction extends AbstractWrapperFunction {
	
	public function __construct($wrapper) {
		parent::__construct($wrapper);
	}

	/**
	 *	@override
	 */
	public function ExpectedArgs() {
		/// \todo implement here
	}
	/**
	 *	@override
	 */
	public function Run($args = array()) {
		/// \todo implement here
	}

}

# ENREGISTREMENT DU WRAPPER AUPRES DU LOADER ###############################################################

WrapperLoader::RegisterWrapper(new OVHMailWrapper());