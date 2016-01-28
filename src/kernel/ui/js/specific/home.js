var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('Home_UIModule', 'Paul Dautry', '1.0dev');
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
				  <div class=\"row\"> \
				  <div class=\"three wide column\"> \
					</div> \
					<div class=\"ten wide column\">"
					+DoleticUIFactory.makeUploadForm('test')+
					"</div> \
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
}