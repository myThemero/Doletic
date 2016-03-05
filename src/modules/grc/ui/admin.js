var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('GRC_UIModule', 'Olivier Vicente', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
        // activate items in tabs
		$('.menu .item').tab();
	}
	/**
	 *	Override build function
	 */
     this.build = function() {
 		return "<div class=\"ui two column grid container\"> \
 				  <div class=\"row\"> \
 				  </div> \
 				  <div class=\"row\"> \
 				  	<div class=\"sixteen wide column\"> \
 				  		<div class=\"ui top attached tabular menu\"> \
   							<a class=\"item active\" data-tab=\"contacts\">Gestion des Contacts</a> \
   							<a class=\"item\" data-tab=\"companies\">Gestion des Sociétés</a> \
							<a class=\"item\" data-tab=\"stats\">Statistiques</a> \
 						</div> \
 						<div class=\"ui bottom attached tab segment active\" data-tab=\"contacts\"> \
 				  			<p>Cette portion est encore en développpement...</p>\
 					    </div> \
 						<div class=\"ui bottom attached tab segment\" data-tab=\"companies\"> \
 						    <p>Cette portion est encore en développpement...</p>\
                        </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"stats\"> \
							<div class="ui labeled button" tabindex="0"> \
								<div class="ui button"> \
									Nombre de Contacts \
								</div> \
								<a class="ui basic label"> \
									666 \
								</a> \
							</div> \
							<div class="ui labeled button" tabindex="0"> \
								<div class="ui button"> \
									Nombre de Sociétés \
								</div> \
								<a class="ui basic label"> \
									666 \
								</a> \
							</div> \
                        </div> \
 					</div> \
 				  </div> \
 				  <div class=\"row\"> \
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

	    } else {

	    }
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF



}
