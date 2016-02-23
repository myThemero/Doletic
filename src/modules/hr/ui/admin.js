var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('HR_UIModule', 'Nicolas Sorin', '1.0dev');
	//test
	/*var DoleticUIModule.position_list = new Array();
	var DoleticUIModule.INSADept_list = new Array();
	var DoleticUIModule.country_list = new Array();*/
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
		// activate items in tabs
		$('.menu .item').tab();
		// hide user details tab
		$('#det').hide();
		// fill user list
		DoleticUIModule.fillUsersList();
		// fill team list
		//DoleticUIModule.fillTeamsList();
		// fill country field
		DoleticUIModule.fillCountrySelector();
		// fill gender field
		DoleticUIModule.fillGenderSelector();
		// fill INSA dept field
		DoleticUIModule.fillINSADeptSelector();
		// fill position field
		DoleticUIModule.fillPositionSelector();
		// fill school year field
		DoleticUIModule.fillSchoolYearSelector();
		//fill division field
		//DoleticUIModule.fillDivisionSelector();
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
  							<a class=\"item active\" data-tab=\"stats\">Statistiques</a> \
  							<a class=\"item\" data-tab=\"memberlist\">Liste des membres</a> \
  							<a class=\"item\" data-tab=\"teamlist\">Liste des équipes</a> \
  							<a class=\"item\" id=\"det\" data-tab=\"userdetails\">Détails de l'utilisateur</a> \
						</div> \
						<div class=\"ui bottom attached tab segment active\" data-tab=\"stats\"> \
				  			<p>Cette portion est encore en développpement...</p>\
					    </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"memberlist\"> \
						  <div class=\"ui two column grid container\"> \
							  <div class=\"row\"> \
							  </div> \
							  <div class=\"row\"> \
							  <div class=\"ten wide column\"> \
								<div class=\"ui horizontal divider\">Membres inscrits</div> \
									<table class=\"ui very basic collapsing celled table\" id=\"user_table\"> \
  										<thead> \
    										<tr><th>Membre</th> \
    										<th>Email</th> \
    										<th>Téléphone</th> \
    										<th>Département</th> \
    										<th>Actions</th> \
  										</tr></thead>\
  										<tbody id=\"user_body\"> \
  											<!-- USER LIST WILL GO THERE --> \
  										</tbody> \
									</table> \
								</div> \
								<div class=\"six wide column\"> \
								  <form id=\"user_form\" class=\"ui form segment\"> \
								    <h4 class=\"ui dividing header\">Ajout d'un membre</h4> \
			  							<div id=\"firstname_field\" class=\"twelve wide required field\"> \
									      <label>Prénom</label> \
									      <input id=\"firstname\" placeholder=\"Prénom...\" type=\"text\"/> \
									    </div> \
									    <div id=\"lastname_field\" class=\"twelve wide required field\"> \
									      <label>Nom</label> \
									      <input id=\"lastname\" placeholder=\"Nom...\" type=\"text\"/> \
									    </div> \
									    <div class=\"fields\"> \
									    	<div class=\"twelve wide required field\"> \
									      		<label>Civilité</label> \
			      						  		<select id=\"gender\" class=\"ui search dropdown\"> \
			      								<!-- GENDERS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
									    	<div id=\"birthdate_field\" class=\"twelve wide required field\"> \
									      		<label>Date de naissance</label> \
									      		<input id=\"birthdate\" placeholder=\"YYYY-MM-JJ\" type=\"text\"/> \
										    </div> \
										</div> \
									    <div id=\"tel_field\" class=\"twelve wide required field\"> \
									      <label>Numéro de téléphone</label> \
									      <input id=\"tel\" placeholder=\"0000000000\" type=\"text\"/> \
									    </div> \
									    <div id=\"mail_field\" class=\"twelve wide required field\"> \
									      <label>Adresse mail</label> \
									      <input id=\"mail\" placeholder=\"exemple@domaine.com\" type=\"text\"/> \
									    </div> \
									    <div id=\"address_field\" class=\"twelve wide required field\"> \
									      <label>Adresse</label> \
									      <input id=\"address\" placeholder=\"X rue de ...\" type=\"text\"/> \
									    </div> \
									    <div class=\"fields\"> \
									    	<div id=\"postalcode_field\" class=\"twelve wide required field\"> \
									      		<label>Code postal</label> \
									      		<input id=\"postalcode\" placeholder=\"00000\" type=\"text\"/> \
									    	</div> \
									    	<div id=\"city_field\" class=\"twelve wide required field\"> \
									      		<label>Ville</label> \
									      		<input id=\"city\" placeholder=\"Ville...\" type=\"text\"/> \
									    	</div> \
			  						    </div> \
			  						    <div class=\"twelve wide required field\"> \
									      		<label>Pays</label> \
			      						  		<select id=\"country\" class=\"ui search dropdown\"> \
			      								<!-- COUNTRIES WILL GO HERE --> \
			    						  		</select> \
			  							</div> \
			  							<div class=\"fields\"> \
									    	<div class=\"twelve wide required field\"> \
									      		<label>Année d'étude</label> \
			      						  		<select id=\"schoolyear\" class=\"ui search dropdown\"> \
			      								<!-- SCHOOLYEARS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
			  								<div class=\"twelve wide required field\"> \
									      		<label>Département</label> \
			      						  		<select id=\"dept\" class=\"ui search dropdown\"> \
			      								<!-- DEPTS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
			  						    </div> \
			  						    <div class=\"fields\"> \
									    	<div class=\"twelve wide required field\"> \
									      		<label>Position</label> \
			      						  		<select id=\"position\" class=\"ui search dropdown\"> \
			      								<!-- POSITIONS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
			  								<div class=\"twelve wide required field\"> \
									      		<label>Intervenant</label> \
			      						  		<select id=\"interv\" class=\"ui search dropdown\"> \
			      								<option value=\"0\">Non</option> \
			      								<option value=\"1\">Oui</option> \
			    						  		</select> \
			  								</div> \
			  						    </div> \
			  						  <div class=\"ui hr_center small buttons\"> \
										<div id=\"abort_btn\" class=\"ui button\" onClick=\"DoleticUIModule.clearNewTicketForm();\">Annuler</div> \
										<div class=\"or\" data-text=\"ou\"></div> \
										<div id=\"send_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.sendNewTicket();\">Ajouter</div> \
									  </div> \
							      </form> \
								</div> \
							  </div> \
							  <div class=\"row\"> \
							  </div> \
							</div> \
						  </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"teamlist\"> \
						  <p>Cette portion est encore en développpement.........</p>\
						</div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"userdetails\"> \
						  <p>Cette portion est encore en développpement et ne devrait même pas s'afficher...</p>\
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
	    /*if(on) {
	      $('.ui.horizontal.divider').attr('class', 'ui horizontal divider inverted');
	      $('#ticket_list').attr('class', 'ui very relaxed celled selection list inverted');
	      $('#support_form').attr('class', 'ui form segment inverted');
	      $('#abort_btn').attr('class', 'ui button inverted');
		  $('#send_btn').attr('class', 'ui green button inverted');
	    } else {
	      $('.ui.horizontal.divider.inverted').attr('class', 'ui horizontal divider');
	      $('#ticket_list').attr('class', 'ui very relaxed celled selection list');
	      $('#support_form').attr('class', 'ui form segment');
	      $('#abort_btn').attr('class', 'ui button');
	      $('#send_btn').attr('class', 'ui green button');
	    }*/
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

	this.hasInputError = false;

	this.fillCountrySelector = function() {
		UserDataServicesInterface.getAllCountries(function(data) {
			// if no service error
			if(data.code == 0) {
				// create content var to build html
				var content = "";
				//DoleticUIModule.country_list = data.object;
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i]+"</option>\n";
				};
				// insert html content
				$('#country').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillGenderSelector = function() {
		UserDataServicesInterface.getAllGenders(function(data) {
			// if no service error
			if(data.code == 0) {
				// create content var to build html
				var content = "";
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i]+"</option>\n";
				};
				// insert html content
				$('#gender').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillINSADeptSelector = function() {
		UserDataServicesInterface.getAllINSADepts(function(data) {
			// if no service error
			if(data.code == 0) {
				// create content var to build html
				var content = "";
				//DoleticUIModule.INSADept_list = data.object;
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i].label+"</option>\n";
				};
				// insert html content
				$('#dept').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillSchoolYearSelector = function() {
		// create content var to build html
		var content = "";
		// iterate over values to build options
		for (var i = 0; i < 8; i++) {
			content += "<option value=\""+(i+1)+"\">"+(i+1)+"</option>\n";
		};
		// insert html content
		$('#schoolyear').html(content);
	}

	this.fillPositionSelector = function() {
		UserDataServicesInterface.getAllPositions(function(data) {
			// if no service error
			if(data.code == 0) {
				// create content var to build html
				//DoleticUIModule.position_list = data.object;
				var content = "";
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i]+"</option>\n";
				};
				// insert html content
				$('#position').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillDivisionSelector = function() {
		HrServicesInterface.getAllDivisions(function(data) {
			// if no service error
			if(data.code == 0) {
				// create content var to build html
				var content = "";
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i]+"</option>\n";
				};
				// insert html content
				$('#division').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillUsersList = function() {
		UserDataServicesInterface.getAll(function(data) {
			// if no service error
			if(data.code == 0 && data.object != "[]") {
				// iterate over values to build options
				var content = {html:""};
				for (var i = 0; i < data.object.length; i++) {
					content.html += "<tr> \
      						<td> \
        				<h4 class=\"ui image header\"> \
          				<img src=\"/resources/image.png\" class=\"ui mini rounded image\"> \
          				<div class=\"content\">"  + data.object[i].firstname + " " + data.object[i].lastname +
            			"<div class=\"sub header\">";
            		// Get position label            		
            		UserDataServicesInterface.getUserLastPos(data.object[i].user_id, function(data, content){
            			if(data.code == 0 && data.object != "[]") {
            				/*UserDataServicesInterface.getINSADeptById(data.object.id, function(data, content) {
            					if(data.code == 0 && data.object != "[]") {
            						content.html += data.object;
            						//$('#user_body').append(data.object);
            					} else {
            						// use default service service error handler
									DoleticServicesInterface.handleServiceError(data);		
            					}
            				});*/
            			} else {
            				// use default service service error handler
							DoleticServicesInterface.handleServiceError(data);
						}
					});
          			content.html += "</div> \
        				</div> \
      					</h4></td> \
      					<td>" + data.object[i].email+         				
      					"</td> \
      					<td>" + data.object[i].tel+         				
      					"</td> \
      					<td>"; // + DoleticUIModule.INSADept_list[data.object[i].insa_dept_id].label;
    				/*UserDataServicesInterface.getINSADeptById(data.object[i].insa_dept_id, function(data, content) {
    					if(data.code == 0 && data.object != "[]") {
            				content.html += data.object.label;
            			} else {
            				// use default service service error handler
							DoleticServicesInterface.handleServiceError(data);		
            			}
    				});*/
    				content.html += "</td> \
    				<td> \
    				</td> \
    				</tr>"; 
				};
				$('#user_body').html(content.html);
				
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillTeamsList = function() {
		HrServicesInterface.getAllTeams(function(data) {
			// if no service error
			if(data.code == 0 && data.object != "[]") {
				// create content var to build html
				var content = "";
				// iterate over values to build options
				/*for (var i = 0; i < data.object.length; i++) {
					var status, popup_selector;
					var id = "popup_trigger_"+i;
					switch (data.object[i].status_id) {
					  case 1:
					  	status = "red radio";
					  	popup_selector = "#open_popup";
					    break;
					  case 2:
					  	status = "orange spinner";
					  	popup_selector = "#work_popup";
					    break;
					  case 3:
					  	status = "green selected radio";
					  	popup_selector = "#done_popup";
					    break;
					  default:
					  	status = "blue help";
					  	popup_selector = "#undefined_popup";
					    break;
					}
					content += "<div class=\"item\"> \
								  <i id=\""+id+"\" class=\""+status+" big icon link\"></i> \
								  <div class=\"middle aligned content\"> \
								    <a class=\"header\">"+data.object[i].subject+"</a> \
								    <div class=\"description\">"+data.object[i].data+"</div>  \
								  </div> \
								  <script>$('#"+id+"').popup({popup:'"+popup_selector+"'});</script> \
								</div>";
				};*/
				// insert html content
				$('#team_list').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.clearNewTicketForm = function() {
		$('#subject').val('');
		$('#data').val('');
		/// \todo trouver quelque chose de mieux ici pour le reset du selecteur
		DoleticUIModule.fillCountrySelector();
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

	this.sendHandler = function(data) {
		// if no service error
		if(data.code == 0) {
			// clear ticket form
			DoleticUIModule.clearNewTicketForm();
			// alert user that creation is a success
			DoleticMasterInterface.showSuccess("Création réussie !", "Le ticket a été créé avec succès !");
			// fill ticket list
			DoleticUIModule.fillTicketsList();
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
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