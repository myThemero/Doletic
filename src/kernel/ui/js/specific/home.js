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
		name:"KernelHomeModule",
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
		return "<div class=\"ui grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"three wide column\"> \
					</div> \
					<div class=\"ten wide column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  	    Home page is currently in development... \
				      </form> \
					</div> \
					<div class=\"three wide column\"> \
					</div> \
				  </div> \
				</div>";
	}

//
// Then all functions required by module dynamic components such as buttons etc...
//

}