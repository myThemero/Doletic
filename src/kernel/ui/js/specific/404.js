//
//	First define the module interface
//

var DoleticModuleInterface = new function() {
	/**
	 *	Always define a module name, it will be used by DoleticMasterInterface class
	 */
	/**
	 *	Module meta data block, it will be used by DoleticMasterInterface class
	 */
	this.meta = {
		name:"Kernel404Module",
		authors:"Paul Dautry",
		version:"1.0dev"
	};
	/**
	 *	This function renders the html page for the given module
	 */
	this.render = function(htmlElement) {
		// build module UI
		htmlElement.innerHTML = this.buildUI();
	}
	/**
	 *	This function builds module ui
	 */
	this.buildUI = function() {
		return "<div class=\"holder\"> \
				  <div class=\"ui two column centered middle aligned grid container\"> \
					<div class=\"column\"> \
					  <div id=\"error_msg\" class=\"ui icon message\"> \
						  <i class=\"warning circle icon\"></i> \
						  <div class=\"content\"> \
						    <div class=\"header\"> \
						      Erreur 404 !  \
						    </div> \
						    <p>La page n'existe pas. \
							<button class=\"ui basic right floated button\" onClick=\"DoleticServicesInterface.requireSpecialHome();\"> \
  								<i class=\"icon home\"></i> \
  								Retour à l'accueil \
							</button> \
						    </p> \
						  </div> \
						</div> \
					</div> \
				   </div> \
				</div>";
	}

//
// Then all functions required by module dynamic components such as buttons etc...
//

}