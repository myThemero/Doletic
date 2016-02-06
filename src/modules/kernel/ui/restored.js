var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('Restored_UIModule', 'Paul Dautry', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
		// remove logout button 
		DoleticMasterInterface.removeUserNotLoggedUselessButtons();
	}
	/**
	 *	Override build function
	 */
	this.build = function() {
		return "<div class=\"holder\"> \
				  <div class=\"ui three column centered middle aligned grid container\"> \
					<div class=\"column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  		<div class=\"ui message\"> \
  							<div class=\"header\">Récupération mot de passe.</div> \
  							<p>Mot de passe réinitialisé !</p> \
						</div> \
  						<div class=\"ui blue fluid button\" onClick=\"DoleticServicesInterface.getUILogin();\">Retour à la page de connexion</div> \
					  </form> \
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
