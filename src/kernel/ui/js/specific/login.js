/**
 *	doletic.js defines the following classes :
 *		
 *		+ DoleticModuleInterface
 *			.registerModule(module)
 *
 *
 *		+ DoleticServicesInterface
 *			....
 *
 *	Note : 
 *		It is strongly recommended to build your module as it is done in this file
 *
 */

//
//	First define the module interface
//

var DoleticModuleInterface = new function() {
	/**
	 *	Module meta data block, it will be used by DoleticMasterInterface class
	 */
	this.meta = {
		name:"KernelLoginModule",
		authors:"Paul Dautry",
		version:"1.0dev"
	};
	/**
	 *	This function renders the html page for the given module
	 */
	this.render = function(htmlElement) {
		// remove logout button 
		DoleticMasterInterface.removeLogoutButton();
		// build module UI
		htmlElement.innerHTML = this.buildUI();
	}
	/**
	 *	This function builds module ui
	 */
	this.buildUI = function() {
		return "<div class=\"holder\"> \
				  <div class=\"ui three column centered middle aligned grid container\"> \
					<div class=\"column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  		<div id=\"uname_field\" class=\"required field\"> \
					      <label>Nom d'utilisateur</label> \
					      <input id=\"uname_input\" placeholder=\"prenom.nom\" name=\"username\" type=\"text\"> \
						</div> \
						<div id=\"pass_field\" class=\"required field\"> \
					      <label>Mot de passe</label> \
					      <input id=\"pass_input\" placeholder=\"mot de passe\" name=\"password\" type=\"password\"> \
						</div> \
  						<div class=\"ui green button\" onClick=\"DoleticModuleInterface.checkLoginFormInputs();\">Valider</div> \
  						<div class=\"ui right floated button\" onClick=\"DoleticModuleInterface.resetLoginForm();\">Reset</div> \
					  </form> \
					</div> \
				   </div> \
				</div>";
	}

//
// Then all functions and internal vars required by module dynamic components such as buttons etc...
//

	this.hasInputError = false;
	/**
	 *
	 */
	this.checkLoginFormInputs = function() {
		if($('#uname_input').val().length > 0 && 
		   $('#pass_input').val().length > 0 && 
		   $('#uname_input').val().match(/^[\w-]+\.[\w-]+$/g)) {
		   	if(this.hasInputError) {
		   		DoleticModuleInterface.resetLoginForm();	
		   	}
			DoleticServicesInterface.authenticate(
				$('#uname_input').val(),
				$('#pass_input').val(),
				function(data) {
       				if(data.authenticated) {
       					// Call for home interface
          				DoleticServicesInterface.getUIHome();
        			} else {
        				DoleticModuleInterface.showLoginError();
        			}
     		 	});
		} else {
			this.showInputError();
		}
	}
	/**
	 *
	 */
	this.showLoginError = function() {
		// reset login form
		this.resetLoginForm();
		// put form in error state
		$('#login_form').attr('class', 'ui form error');
		// display error message
		$('#pass_field').after("<div id=\"login_error\" class=\"ui error message\"> \
								   <div class=\"header\">Connexion refusée !</div> \
								   <p>Veuillez saisir vos identifiants de nouveau.</p> \
		   						 </div>");
	}
	/**
	 *
	 */
	this.showInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#login_form').attr('class', 'ui form segment error');
			$('#uname_field').attr('class', 'field error');
			$('#uname_field').append("<div id=\"uname_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer votre nom d'utilisateur</div>");
			$('#pass_field').attr('class', 'field error');
			$('#pass_field').append("<div id=\"pass_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer votre mot de passe</div>");
		}
	}
	/**
	 *
	 */
	this.resetLoginForm = function() {
		// lower inputError flag
		this.hasInputError = false;
		// clear fields 
		$('#uname_input').val("");
		$('#pass_input').val("");
		// reset class attributes
		$('#login_form').attr('class', 'ui form segment');
		$('#uname_field').attr('class', 'required field');
		$('#pass_field').attr('class', 'required field');
		// remove error elements
		$('#uname_error').remove();
		$('#pass_error').remove();
		$('#login_error').remove();
	}

}
