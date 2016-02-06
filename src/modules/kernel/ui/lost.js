var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('Lost_UIModule', 'Paul Dautry', '1.0dev');
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
					  <form id=\"lost_form\" class=\"ui form segment\"> \
				  		<div id=\"mail_field\" class=\"required field\"> \
					      <label>Adresse mail</label> \
					      <input id=\"mail_input\" placeholder=\"adresse@domaine.racine\" name=\"mail\" type=\"text\"> \
						</div> \
  						<div class=\"ui fluid green button\" onClick=\"DoleticUIModule.checkLostFormInputs();\">Envoyer un mail</div> \
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

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

	this.hasInputError = false;
	/**
	 *
	 */
	this.checkLostFormInputs = function() {
		if($('#mail_input').val().match(/^[\w\d-_\.]+@[\w\d-]+.[\w]+$/g)) {
		   	if(this.hasInputError) {
		   		DoleticUIModule.resetLostForm();	
		   	}
			DoleticServicesInterface.resetPassword(
				$('#mail_input').val(),
				function(data) {
       				if(data.sent) {
       					DoleticMasterInterface.showSuccess("Mail envoyé !","Vérifiez votre boîte mail et suivez les consignes indiquées dans le mail.");
        			} else {
        				DoleticMasterInterface.showError("Echec !","Nous n'avons pas réussi à trouver votre adresse mail.");
        			}
     		 	});
		} else {
			DoleticUIModule.showInputError();
		}
	}
	/**
	 *
	 */
	this.showInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#lost_form').attr('class', 'ui form segment error');
			$('#mail_field').attr('class', 'field error');
			$('#mail_field').append("<div id=\"mail_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer une adresse mail correcte.</div>");
		}
	}
	/**
	 *
	 */
	this.resetLostForm = function() {
		// lower inputError flag
		this.hasInputError = false;
		// clear fields 
		$('#mail_input').val('');
		// reset class attributes
		$('#lost_form').attr('class', 'ui form segment');
		$('#mail_field').attr('class', 'required field');
		// remove error elements
		$('#mail_error').remove();
	}

}