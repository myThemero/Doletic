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
		// fill fields
		TicketServicesInterface.getAllCategories(DoleticUIModule.resetCategorySelector);
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
					    <h4 class=\"ui dividing header\">Création d'un nouveau ticket</h4> \
				  	    <div class=\"fields\"> \
						    <div id=\"subject_field\" class=\"fourteen wide required field\"> \
						      <label>Sujet</label> \
						      <input id=\"subject\" placeholder=\"Sujet\" type=\"text\"/> \
						    </div> \
						    <div class=\"field\"> \
						      <label>Catégorie</label> \
      						  <select id=\"category\" class=\"ui search dropdown\"> \
      							<!-- categories goes here --> \
    						  </select> \
  							</div> \
						  </div> \
						  <div id=\"data_field\" class=\"required field\"> \
    						<label>Description</label> \
    						<textarea id=\"data\"></textarea> \
  						  </div> \
  						  <div class=\"ui support_center small buttons\"> \
							<div class=\"ui button\" onClick=\"DoleticUIModule.clearNewTicketForm();\">Annuler</div> \
							<div class=\"or\" data-text=\"ou\"></div> \
							<div class=\"ui button\" onClick=\"DoleticUIModule.sendNewTicket();\">Envoyer</div> \
						  </div> \
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

	this.hasInputError = false;

	this.resetCategorySelector = function(data) {
		// if no service error
		if(data.code == 0) {
			// create content var to build html
			var content = "";
			var json = JSON.parse(data.data);
			// iterate over values to build options
			for (var i = 0; i < json.length; i++) {
				content += "<option value=\""+(i+1)+"\">"+json[i]+"</option>\n";
			};
			// insert html content
			$('#category').html(content);
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		}
	}

	this.clearNewTicketForm = function() {
		$('#subject').val('');
		$('#data').val('');
		/// \todo trouver quelque chose de mieux ici pour le reset du selecteur
		TicketServicesInterface.getAllCategories(DoleticUIModule.resetCategorySelector);
		// clear error
		if(this.hasInputError) {
			// disable has error
			this.hasInputError = false;
			// change input style
			$('#support_form').attr('class', 'ui form segment');
			$('#subject_field').attr('class', 'required field');
			$('#data_field').attr('class', 'required field');
			// remove error elements
			$('#subject_error').remove();
			$('#data_error').remove();
		}
	}

	this.sendHandler = function(data) {
		// if no service error
		if(data.code == 0) {
			// clear ticket form
			DoleticUIModule.clearNewTicketForm();
			// alert user that creation is a success
			DoleticMasterInterface.showSuccess("Création réussie !", "Le ticket a été créé avec succès !");
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		}
	}

	this.showInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#support_form').attr('class', 'ui form segment error');
			$('#subject_field').attr('class', 'field error');
			$('#subject_field').append("<div id=\"subject_error\" class=\"ui basic red pointing prompt label transition visible\">Le sujet ne doit pas être vide</div>");
			$('#data_field').attr('class', 'field error');
			$('#data_field').append("<div id=\"data_error\" class=\"ui basic red pointing prompt label transition visible\">La description ne dois pas être vide</div>");
		}
	}

	this.sendNewTicket = function() {
		if($('#subject').val().length > 0 && 
		   $('#data').val().length > 0) {
		   	// retreive missing information
		TicketServicesInterface.insert(
			"-1",
			$('#subject').val(),
			$('#category').val(),
			$('#data').val(),
			DoleticUIModule.sendHandler
			);
		} else {
			DoleticUIModule.showInputError();
		}
		
	}

}