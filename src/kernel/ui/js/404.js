var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('404_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // remove logout button 
        DoleticMasterInterface.removeUserNotLoggedUselessButtons();
    };
    /**
     *    Override build function
     */
    this.build = function () {
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
							<button class=\"ui basic right floated button\" onClick=\"DoleticServicesInterface.getUIHome();\"> \
  								<i class=\"icon home\"></i> \
  								Retour à l'accueil \
							</button> \
						    </p> \
						  </div> \
						</div> \
					</div> \
				   </div> \
				</div>";
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data) {
        this.super.uploadSuccessHandler(id, data);
    }
};