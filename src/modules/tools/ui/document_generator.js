var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('AdminTool_UIModule', 'Paul Dautry', '1.0dev');
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
				  	<div class=\"six centered wide column\"> \
				  		<div class=\"ui segment\"> \
					  		<div class=\"ui horizontal divider\"><i class=\"file text outline icon\"></i> Choix des documents</div> \
				  			<div class=\"ui list\"> \
  								<div class=\"item\"> \
  									<div class=\"ui checkbox\"> \
  										<input type=\"checkbox\"> \
  										<label>Document A</label> \
  									</div> \
  								</div> \
  								<div class=\"item\"> \
  									<div class=\"ui checkbox\"> \
  										<input type=\"checkbox\"> \
  										<label>Document B</label> \
  									</div> \
  								</div> \
  								<div class=\"item\"> \
  									<div class=\"ui checkbox\"> \
  										<input type=\"checkbox\"> \
  										<label>Document C</label> \
  									</div> \
  								</div> \
  								<div class=\"item\"> \
  									<div class=\"ui checkbox\"> \
  										<input type=\"checkbox\"> \
  										<label>Document D</label> \
  									</div> \
  								</div> \
							</div> \
							<div class=\"ui horizontal divider\"><i class=\"suitcase icon\"></i> Choix de l'étude</div> \
							<div> \
								<div class=\"ui search\"> \
  									<div class=\"ui icon input\"> \
    									<input class=\"prompt\" placeholder=\"Numéro ou titre...\" type=\"text\"> \
    									<i class=\"search icon\"></i> \
  									</div> \
  									<div class=\"results\"></div> \
								</div> \
							</div> \
							<div class=\"ui horizontal divider\"><i class=\"download icon\"></i> Téléchargement</div> \
							<button class=\"fluid ui button\">Télécharger les documents</button> \
						</div> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
				  </div> \
				</div>";
	}
	/**
	 *	Override uploadSuccessHandler
	 */
	this.uploadSuccessHandler = function(id, data) {
		this.super.uploadSuccessHandler(id, data);
	}

	this.nightMode = function(on) {
	    if(on) {
	   		/// \todo implement here
	    } else {
	    	/// \todo implement here
	    }
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

}
