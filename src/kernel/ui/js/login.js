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
	 *	Always define a module name, it will be used by DoleticMasterInterface class
	 */
	this.name = "LoginModule";
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
				  		<div id=\"uname_field\" class=\"field\"> \
					      <label>Nom d'utilisateur</label> \
					      <input id=\"uname_input\" placeholder=\"prenom.nom\" name=\"username\" type=\"text\"> \
						</div> \
						<div id=\"pass_field\" class=\"field\"> \
					      <label>Mot de passe</label> \
					      <input id=\"pass_input\" placeholder=\"mot de passe\" name=\"password\" type=\"password\"> \
						</div> \
  						<div class=\"ui green button\" onClick=\"DoleticModuleInterface.check_login_form_inputs();\">Valider</div> \
  						<div class=\"ui right floated button\" onClick=\"DoleticModuleInterface.reset_login_form();\">Reset</div> \
					  </form> \
					</div> \
				   </div> \
				</div>";
	}

//
// Then all functions required by module dynamic components such as buttons etc...
//

	this.check_login_form_inputs = function() {
		if($('#uname_input').val().length > 0 && 
		   $('#pass_input').val().length > 0) {
			this.do_login();	
		} else {
			this.show_error();
		}
	}

	this.do_login = function() {
		/// \todo implement here
	}

	this.show_error = function() {
		$('#login_form').attr('class', 'ui form segment error');
		$('#uname_field').attr('class', 'field error');
		$('#uname_field').append("<div id=\"uname_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer votre nom d'utilisateur</div>");
		$('#pass_field').attr('class', 'field error');
		$('#pass_field').append("<div id=\"pass_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer votre mot de passe</div>");
	}

	this.reset_login_form = function() {
		$('#login_form').attr('class', 'ui form segment');
		$('#uname_field').attr('class', 'field');
		$('#uname_error').remove();
		$('#pass_field').attr('class', 'field');
		$('#pass_error').remove();
	}

}
