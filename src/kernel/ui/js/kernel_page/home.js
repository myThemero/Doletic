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
		return "<div class=\"ui two column grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"three wide column\"> \
					<div class=\"ui card\"> \
					  <div class=\"image\"> \
					    <img src=\"/resources/aie.png\"> \
					  </div> \
					  <div class=\"content\"> \
					    <a class=\"header\">John Doe</a> \
					    <div class=\"meta\"> \
					      <span class=\"date\">Dernière connexion le 13-02-2015 à 12:00</span> \
					    </div> \
					    <div class=\"description\"> \
					      Responsable DSI \
					    </div> \
					  </div> \
					  <div class=\"extra content\"> \
					    <a> \
					      <i class=\"user icon\"></i> \
					      Modifier mon profil \
					    </a> \
					  </div> \
					</div> \
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
					<div class=\"ten wide column\"> \
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
}