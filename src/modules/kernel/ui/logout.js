var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('Logout_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // remove useless menus when disconnected
        DoleticMasterInterface.clearModuleSubmenu();
        // remove useless buttons when disconnected
        DoleticMasterInterface.removeGeneralButtons();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"holder\"> \
				  <div class=\"ui three column centered middle aligned grid container\"> \
					<div class=\"column\"> \
					  <form id=\"logout_form\" class=\"ui form segment\"> \
				  		<div class=\"ui message\"> \
  							<div class=\"header\">Déconnexion</div> \
  							<p>Procédure de déconnexion réussie.</p> \
						</div> \
  						<div id=\"reset_btn\" class=\"ui blue fluid button\" onClick=\"DoleticServicesInterface.resetUI();\">Retour à la page de connexion</div> \
					  </form> \
					</div> \
				   </div> \
				</div>";
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data) {
        this.super.uploadSuccessHandler(id, data);
    };

    this.nightMode = function (on) {
        if (on) {
            $('#reset_btn').attr('class', 'ui blue fluid button inverted');
            $('#logout_form').attr('class', 'ui form segment inverted');
        } else {
            $('#reset_btn').attr('class', 'ui blue fluid button');
            $('#logout_form').attr('class', 'ui form segment');
        }
    }
};
