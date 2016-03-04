var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('GRC_UIModule', '', '1.0dev');
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
				  <div class=\"ten wide column\"> \
					<div class=\"ui horizontal divider\">Mes tickets</div> \
						  <div id=\"open_popup\" class=\"ui special popup\"> \
							<div class=\"content\">Votre problème n'a pas encore été pris en charge.</div> \
						  </div> \
						  <div id=\"work_popup\" class=\"ui special popup\"> \
							<div class=\"content\">Votre problème est en cours de résolution.</div> \
						  </div> \
						  <div id=\"done_popup\" class=\"ui special popup\"> \
							<div class=\"content\">Votre problème est résolu.</div> \
						  </div> \
						  <div id=\"undefined_popup\" class=\"ui special popup\"> \
							<div class=\"content\">L'etat de ce ticket est étrange. Merci de prévenir les développeurs.</div> \
						  </div> \
						  <div id=\"ticket_list\" class=\"ui very relaxed celled selection list\"> \
							<!-- USER TICKETS WILL GO HERE --> \
						  </div> \
					</div> \
					<div class=\"six wide column\"> \
					  <form id=\"support_form\" class=\"ui form segment\"> \
					    <h4 class=\"ui dividing header\">Création d'un nouveau ticket</h4> \
				  	    <div class=\"fields\"> \
						    <div id=\"subject_field\" class=\"twelve wide required field\"> \
						      <label>Sujet</label> \
						      <input id=\"subject\" placeholder=\"Sujet\" type=\"text\"/> \
						    </div> \
						    <div class=\"field\"> \
						      <label>Catégorie</label> \
      						  <select id=\"category\" class=\"ui search dropdown\"> \
      							<!-- CATEGORIES WILL GO HERE --> \
    						  </select> \
  							</div> \
						  </div> \
						  <div id=\"data_field\" class=\"required field\"> \
    						<label>Description</label> \
    						<textarea id=\"data\"></textarea> \
  						  </div> \
  						  <div class=\"ui support_center small buttons\"> \
							<div id=\"abort_btn\" class=\"ui button\" onClick=\"DoleticUIModule.clearNewTicketForm();\">Annuler</div> \
							<div class=\"or\" data-text=\"ou\"></div> \
							<div id=\"send_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.sendNewTicket();\">Envoyer</div> \
						  </div> \
				      </form> \
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
	      
	    } else {
	      
	    }
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF



}