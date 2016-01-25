/**
 *	doletic.js defines the following classes :
 *		
 *		+ DoleticModuleInterface
 *			.registerModule(module)
 *
 *
 *		+ DoleticServicesInterface
 *			....
 *
 *	Note : 
 *		It is strongly recommended to build your module as it is done in this file
 *
 */

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
		name:"KernelLogoutModule",
		authors:"Paul Dautry",
		version:"1.0dev"
	};
	/**
	 *	This function renders the html page for the given module
	 */
	this.render = function(htmlElement) {
		// remove logout button 
		DoleticMasterInterface.removeLogoutButton();
		// build module UI
		htmlElement.innerHTML = this.buildUI();
	}
	/**
	 *	This function builds module ui
	 */
	this.buildUI = function() {
		return "<div class=\"holder\"> \
				  <div class=\"ui three column centered middle aligned grid container\"> \
					<div class=\"column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  		<div class=\"ui message\"> \
  							<div class=\"header\">Déconnexion</div> \
  							<p>Procédure de déconnexion réussie.</p> \
						</div> \
  						<div class=\"ui blue fluid button\" onClick=\"DoleticServicesInterface.requireSpecialLogin();\">Go back to login</div> \
					  </form> \
					</div> \
				   </div> \
				</div>";
	}

//
// Then all functions required by module dynamic components such as buttons etc...
//

}
