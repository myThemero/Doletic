var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('Support_UIModule', 'Paul Dautry', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
	}
	/**
	 *	Override build function
	 */
	this.build = function() {
		return "<div class=\"ui two column grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"three wide column\"> \
					</div> \
					<div class=\"ten wide column\"> \
					  <form id=\"support_form\" class=\"ui form segment\"> \
				  	    Support user interface is currently in development... \
				      </form> \
					</div> \
					<div class=\"three wide column\"> \
					</div> \
				  </div> \
				</div>";
	}
	/**
	 *	Override uploadSuccessHandler
	 */
	this.uploadSuccessHandler = function(id, data) {
		this.super.uploadSuccessHandler(id, data);
	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

}