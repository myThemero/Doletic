var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('HR_UIModule', 'Nicolas Sorin', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {

		this.super.render(htmlNode, this);
		// activate items in tabs
		$('.menu .item').tab();
		$('.dropdown').dropdown();
		// hide user details tab
		$('#det').hide();
		// fill user list
		DoleticUIModule.fillUsersList();
		// fill team list
		DoleticUIModule.fillTeamsList();
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
		DoleticUIModule.fillDivisionSelector();
		//fill ag field
		DoleticUIModule.fillAGSelector();
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
								<div class=\"ui horizontal divider\"> \
									Tous les membres <!-- PLACER FILTRE ICI -->\
								</div> \
									<table class=\"ui very basic celled table\" id=\"user_table\"> \
  										<thead> \
    										<tr><th></th><th>Nom</th> \
    										<th>Email</th> \
    										<th>Téléphone</th> \
    										<th>Dept.</th> \
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
			  						  	<div class=\"fields\"> \
				  							<div id=\"firstname_field\" class=\"eight wide required field\"> \
										      <label>Prénom</label> \
										      <input id=\"firstname\" placeholder=\"Prénom...\" type=\"text\"/> \
										    </div> \
										    <div id=\"lastname_field\" class=\"eight wide required field\"> \
										      <label>Nom</label> \
										      <input id=\"lastname\" placeholder=\"Nom...\" type=\"text\"/> \
										    </div> \
										</div>\
									    <div class=\"fields\"> \
									    	<div class=\"twelve wide required field\"> \
									      		<label>Civilité</label> \
			      						  		<select id=\"gender\" class=\"ui fluid search dropdown\"> \
			      								<!-- GENDERS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
									    	<div id=\"birthdate_field\" class=\"twelve wide required field\"> \
									      		<label>Date de naissance</label> \
									      		<input id=\"birthdate\" placeholder=\"YYYY-MM-JJ\" type=\"text\"/> \
										    </div> \
										</div> \
										<div class=\"fields\"> \
										    <div id=\"tel_field\" class=\"twelve wide required field\"> \
										      <label>Numéro de téléphone</label> \
										      <input id=\"tel\" placeholder=\"0000000000\" type=\"text\"/> \
										    </div> \
										    <div id=\"mail_field\" class=\"twelve wide required field\"> \
										      <label>Adresse mail</label> \
										      <input id=\"mail\" placeholder=\"exemple@domaine.com\" type=\"text\"/> \
										    </div> \
										</div>\
									    <div id=\"address_field\" class=\"sixteen wide required field\"> \
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
			  						    <div class=\"sixteen wide required field\"> \
									      		<label>Pays</label> \
			      						  		<select id=\"country\" class=\"ui fluid search dropdown\"> \
			      								<!-- COUNTRIES WILL GO HERE --> \
			    						  		</select> \
			  							</div> \
			  							<div class=\"fields\"> \
									    	<div class=\"sixteen wide required field\"> \
									      		<label>Année d'étude</label> \
			      						  		<select id=\"schoolyear\" class=\"ui fluid search dropdown\"> \
			      								<!-- SCHOOLYEARS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
			  								<div class=\"sixteen wide required field\"> \
									      		<label>Département</label> \
			      						  		<select id=\"dept\" class=\"ui fluid search dropdown\"> \
			      								<!-- DEPTS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
			  						    </div> \
			  						    <div class=\"fields\"> \
									    	<div class=\"twelve wide required field\"> \
									      		<label>Position</label> \
			      						  		<select id=\"position\" class=\"ui fluid search dropdown\"> \
			      								<!-- POSITIONS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
			  								<div class=\"four wide required field\"> \
									      		<label>Intervenant</label> \
			      						  		<select id=\"interv\" class=\"ui fluid dropdown\"> \
			      								<option value=\"0\">Non</option> \
			      								<option value=\"1\">Oui</option> \
			    						  		</select> \
			  								</div> \
			  						    </div> \
			  						  <div class=\"ui hr_center small buttons\"> \
										<div id=\"abort_btn\" class=\"ui button\" onClick=\"DoleticUIModule.clearNewUserForm();\">Annuler</div> \
										<div class=\"or\" data-text=\"ou\"></div> \
										<div id=\"adduser_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.insertNewUser();\">Ajouter</div> \
									  </div> \
							      </form> \
								</div> \
							  </div> \
							  <div class=\"row\"> \
							  </div> \
							</div> \
						  </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"teamlist\"> \
						  <div class=\"ui two column grid container\"> \
							  <div class=\"row\"> \
							  </div> \
							  <div class=\"row\"> \
							  <div class=\"ten wide column\"> \
								<div class=\"ui horizontal divider\"> \
									Toutes les équipes <!-- PLACER FILTRE ICI -->\
								</div> \
									<table class=\"ui very basic celled table\" id=\"team_table\"> \
  										<thead> \
    										<tr><th>Nom</th> \
    										<th>Chef d'équipe</th> \
    										<th>Pôle</th> \
    										<th>Membres</th> \
    										<th>Actions</th> \
  										</tr></thead>\
  										<tbody id=\"team_body\"> \
  											<!-- TEAM LIST WILL GO THERE --> \
  										</tbody> \
									</table> \
								</div> \
								<div class=\"six wide column\"> \
								  <form id=\"team_form\" class=\"ui form segment\"> \
								    <h4 class=\"ui dividing header\">Ajout d'une équipe</h4> \
			  							<div id=\"tname_field\" class=\"twelve wide required field\"> \
									      <label>Nom</label> \
									      <input id=\"tname\" placeholder=\"Nom d'équipe...\" type=\"text\"/> \
									    </div> \
			  						    <div class=\"twelve wide required field\"> \
									      		<label>Chef d'équipe</label> \
			      						  		<select id=\"leader\" class=\"ui fluid search dropdown\"> \
			      								<!-- LEADERS WILL GO HERE --> \
			    						  		</select> \
			  							</div> \
			  							<div class=\"twelve wide required field\"> \
									      		<label>Pôle associé</label> \
			      						  		<select id=\"division\" class=\"ui fluid search dropdown\"> \
			      								<!-- DIVISIONS WILL GO HERE --> \
			    						  		</select> \
			  							</div> \
			  						  <div class=\"ui hr_center small buttons\"> \
										<div id=\"abort_btn\" class=\"ui button\" onClick=\"DoleticUIModule.clearNewTeamForm();\">Annuler</div> \
										<div class=\"or\" data-text=\"ou\"></div> \
										<div id=\"addteam_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.insertNewTeam();return false;\">Ajouter</div> \
									  </div> \
							      </form> \
								</div> \
							  </div> \
							  <div class=\"row\"> \
							  </div> \
							</div> \
						</div> \
						 \
						 	<!-- 		USER DETAILS 		--> \
						 	\
						<div class=\"ui bottom attached tab segment\" data-tab=\"userdetails\"> \
						  	<div class=\"ui two column grid container\"> \
						  	  <div class=\"four wide column\"> \
						  	  	<div class=\"ui relaxed divided list\"> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Nom complet</div> \
								      <div class=\"description\" id=\"det_name\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Poste actuel</div> \
								      <div class=\"description\" id=\"det_pos\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Date de naissance</div> \
								      <div class=\"description\" id=\"det_birth\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Année</div> \
								      <div class=\"description\" id=\"det_year\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Téléphone</div> \
								      <div class=\"description\" id=\"det_tel\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Email</div> \
								      <div class=\"description\" id=\"det_mail\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Adresse</div> \
								      <div class=\"description\" id=\"det_add\">undefined</div> \
								      <div class=\"description\" id=\"det_postal\">undefined</div> \
								      <div class=\"description\" id=\"det_city\">undefined</div> \
								    </div> \
								  </div> \
								  <div class=\"item\"> \
								    <div class=\"content\"> \
								      <div class=\"header\">Nationalité</div> \
								      <div class=\"description\" id=\"det_country\">undefined</div> \
								    </div> \
								  </div> \
								</div> \
						  	  </div> \
						  	  <div class=\"eight wide column\"> \
						  	  	<div class=\"ui horizontal divider\"> \
									Adhésions administrateur \
								</div> \
						  	  	<table class=\"ui very basic celled table\" id=\"admm_table\"> \
  									<thead> \
    								<tr><th>Date de début</th> \
    									<th>Date de fin</th> \
    									<th>Cotis.</th> \
    									<th>Fiche</th> \
    									<th>Certif.</th> \
    									<th>AG</th> \
    									<th>Actions</th> \
  									</tr></thead>\
  										<tbody id=\"admm_body\"> \
  											<!-- ADM MEMBERSHIP LIST WILL GO THERE --> \
  										</tbody> \
								</table> \
								<div class=\"ui horizontal divider\"> \
									Adhésions intervenant \
								</div> \
						  	  	<table class=\"ui very basic collapsing celled table\" id=\"intm_table\"> \
  									<thead> \
    								<tr><th>Date de début</th> \
    									<th>Cotis.</th> \
    									<th>Fiche</th> \
    									<th>Certif.</th> \
    									<th>RIB</th> \
    									<th>Pièce id.</th> \
    									<th>Actions</th> \
  									</tr></thead>\
  										<tbody id=\"intm_body\"> \
  											<!-- INT MEMBERSHIP LIST WILL GO THERE --> \
  										</tbody> \
								</table> \
						  	  </div> \
						  	  <div class=\"four wide column\"> \
						  	  <div class=\"ui top attached tabular menu\"> \
						  	  	<a class=\"item active\" data-tab=\"admm\" id=\"admm_tab\">Administrateur</a> \
  								<a class=\"item\" data-tab=\"intm\">Intervenant</a> \
  							  </div> \
  									<div class=\"ui bottom attached tab segment\" data-tab=\"admm\"> \
							  	  	   <form id=\"admm_form\" class=\"ui form segment\"> \
									    <h4 class=\"ui dividing header\">Ajout d'une adhésion</h4> \
				  							<div id=\"sdate_field\" class=\"sixteen wide required field\"> \
										      <label>Date de début</label> \
										      <input id=\"sdatea\" placeholder=\"YYYY-MM-JJ\" type=\"text\"/> \
										    </div> \
										    <div id=\"edate_field\" class=\"sixteen wide required field\"> \
										      <label>Date de fin</label> \
										      <input id=\"edate\" placeholder=\"YYYY-MM-JJ\" type=\"text\"/> \
										    </div> \
										    <div class=\"sixteen wide required field\"> \
									      		<label>AG de recrutement</label> \
			      						  		<select id=\"ag\" class=\"ui fluid search dropdown\"> \
			      								<!-- AGS WILL GO HERE --> \
			    						  		</select> \
			  								</div> \
				  						    <div class=\"sixteen wide required field\"> \
										      		<label>Documents présents</label> \
				      						  		<select id=\"docs_adm\" name=\"Documents\" multiple=\"\" class=\"ui fluid dropdown\"> \
				      									<option value=\"0\">Cotisation</option> \
														<option value=\"1\">Fiche d'inscription</option> \
														<option value=\"2\">Certificat de scolarité</option> \
				    						  		</select> \
				  							</div> \
				  						  <div class=\"ui hr_center small buttons\"> \
											<div id=\"abort_btn\" class=\"ui button\" onClick=\"DoleticUIModule.clearNewAdmMembershipForm());\">Annuler</div> \
											<div class=\"or\" data-text=\"ou\"></div> \
											<div id=\"admm_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.insertNewAdmMembership(-1);\">Ajouter</div> \
										  </div> \
								      </form> \
								    </div> \
								    <div class=\"ui bottom attached tab segment\" data-tab=\"intm\"> \
								    	<form id=\"intm_form\" class=\"ui form segment\"> \
									    	<h4 class=\"ui dividing header\">Ajout d'une adhésion</h4> \
				  							<div id=\"sdate_field\" class=\"sixteen wide required field\"> \
										      <label>Date de début</label> \
										      <input id=\"sdatei\" placeholder=\"YYYY-MM-JJ\" type=\"text\"/> \
										    </div> \
				  						    <div class=\"sixteen wide required field\"> \
										      		<label>Documents présents</label> \
				      						  		<select id=\"docs_int\" name=\"Documents\" multiple=\"\" class=\"ui fluid dropdown\"> \
				      									<option value=\"0\">Cotisation</option> \
														<option value=\"1\">Fiche d'inscription</option> \
														<option value=\"2\">Certificat de scolarité</option> \
														<option value=\"3\">RIB</option> \
														<option value=\"4\">Pièce d'identité</option> \
				    						  		</select> \
				  							</div> \
				  						  <div class=\"ui hr_center small buttons\"> \
											<div id=\"abort_btn\" class=\"ui button\" onClick=\"DoleticUIModule.clearNewTicketForm();\">Annuler</div> \
											<div class=\"or\" data-text=\"ou\"></div> \
											<div id=\"intm_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.insertNewIntMembership(-1);\">Ajouter</div> \
										  </div> \
								      </form> \
								    </div> \
						  	  </div> \
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
	    /*if(on) {
	      $('.ui.horizontal.divider').attr('class', 'ui horizontal divider inverted');
	      $('#ticket_list').attr('class', 'ui very relaxed celled selection list inverted');
	      $('#support_form').attr('class', 'ui form segment inverted');
	      $('#abort_btn').attr('class', 'ui button inverted');
		  $('#adduser_btn').attr('class', 'ui green button inverted');
	    } else {
	      $('.ui.horizontal.divider.inverted').attr('class', 'ui horizontal divider');
	      $('#ticket_list').attr('class', 'ui very relaxed celled selection list');
	      $('#support_form').attr('class', 'ui form segment');
	      $('#abort_btn').attr('class', 'ui button');
	      $('#adduser_btn').attr('class', 'ui green button');
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
				//var extra_content = "<option value=\"0\">Tous</option>";
				var content = "";
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i]+"</option>\n";
				};
				// insert html content
				$('#position').html(content);
				//$('#user_filter').html(extra_content + content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillDivisionSelector = function() {
		TeamServicesInterface.getAllDivisions(function(data) {
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
	this.fillAGSelector = function() {
		AdmMembershipServicesInterface.getAllAgs(function(data) {
			// if no service error
			if(data.code == 0) {
				// create content var to build html
				var content = "";
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					content += "<option value=\""+(i+1)+"\">"+data.object[i]+"</option>\n";
				};
				// insert html content
				$('#ag').html(content);
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
				// Store data in global array
				window.user_list = data.object;
				// iterate over values to build options
				var content = "";
				var selector_content = "";
				for (var i = 0; i < data.object.length; i++) {
					content += "<tr> \
      						<td> \
      						<button class=\"ui icon button\" onClick=\"DoleticUIModule.fillUserDetails("+data.object[i].user_id+"); return false;\"> \
	  							<i class=\"user icon\"></i> \
							</button> \
							</td><td> \
        				<h4 class=\"ui header\"> \
          				<div class=\"content\">"  + data.object[i].firstname + " " + data.object[i].lastname +
            			"<div class=\"sub header\">" + data.object[i].last_pos.label + "</div> \
        				</div> \
      					</h4></td> \
      					<td>" + data.object[i].email + "</td> \
      					<td>" + data.object[i].tel + "</td> \
      					<td>" + data.object[i].insa_dept + "</td> \
    				<td> \
    					<div class=\"ui icon buttons\"> \
	    					<button class=\"ui icon button\" onClick=\"DoleticUIModule.editUser("+data.object[i].id+", "+data.object[i].user_id +"); return false;\"> \
	  							<i class=\"write icon\"></i> \
							</button> \
							<button class=\"ui icon button\" onClick=\"DoleticUIModule.archiveUser("+data.object[i].id+", "+data.object[i].user_id +"); return false;\"> \
	  							<i class=\"archive icon\"></i> \
							</button> \
							<button class=\"ui icon button\" onClick=\"DoleticUIModule.deleteUser("+data.object[i].id+", "+data.object[i].user_id+"); return false;\"> \
	  							<i class=\"remove user icon\"></i> \
							</button> \
						</div> \
    				</td> \
    				</tr>";
    				selector_content += "<option value=\""+data.object[i].user_id+"\">"
    							+ data.object[i].firstname + " " + data.object[i].lastname + "</option>\n";
				};
				$('#user_body').html(content);
				$('#leader').html(selector_content);
				
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.fillTeamsList = function() {
		TeamServicesInterface.getAll(function(data) {
			// if no service error
			if(data.code == 0 && data.object != "[]") {
				// create content var to build html
				var content = "";
				// iterate over values to build options
				for (var i = 0; i < data.object.length; i++) {
					/*var status, popup_selector;
					var id = "popup_trigger_"+i;*/
					DoleticUIModule.makeTeamModal(data.object[i]);
					content += "<tr><td>"+data.object[i].name+"</td> \
								<td>"+window.user_list[data.object[i].leader_id-1].firstname + " " 
								+ window.user_list[data.object[i].leader_id-1].lastname +"</td> \
								<td>" + data.object[i].division + "</td> \
								<td> \
									<button class=\"ui icon button\" onClick=\"$('#tmodal_"+data.object[i].id+"').modal('show');\"> \
	  									<i class=\"write icon\"></i>Gérer \
								</td> \
								<td> \
									<div class=\"ui icon buttons\"> \
									<button class=\"ui icon button\" onClick=\"DoleticUIModule.editTeam("+data.object[i].id+"); return false;\"> \
	  									<i class=\"write icon\"></i> \
									</button> \
									<button class=\"ui icon button\"onClick=\"DoleticUIModule.deleteTeam("+data.object[i].id+"); return false;\"> \
	  									<i class=\"remove icon\"></i> \
									</button></td> \
									</div> \
								</tr>";
				};
				// insert html content
				$('#team_body').html(content);
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.makeTeamModal = function(team) {
		var modal = "<div class=\"ui modal\" id=\"tmodal_"+team.id+"\" > \
	  		<div class=\"header\">\
	    		Gérer les membres\
	  		</div> <br>\
	  		<div class=\"ui stackable grid container\"> \
	  			<div class=\"row\"> \
	  				<div class=\"ten wide column\"> \
	  					<table class=\"ui very basic single line striped table\"><tbody>";
	  	for(var i=0; i<team.member_id.length; i++) {
	  		modal += "		<tr><td> \
						  <i class=\"large user middle aligned icon\"></i></td><td>\
						  <div class=\"content\">\
						    <div class=\"header\"><strong>"+ window.user_list[team.member_id[i]-1].firstname + " " 
						    					   + window.user_list[team.member_id[i]-1].lastname;
			modal +=	    "</strong></div><div class=\"description\">"+window.user_list[team.member_id[i]-1].last_pos.label+"</div>\
						  </div>";
			if(team.member_id[i] != team.leader_id) {
				modal += "<td><button class=\"ui small icon button\"onClick=\"DoleticUIModule.deleteTeamMember("+team.id+", "+team.member_id[i]+"); return false;\"> \
	  									<i class=\"remove icon\"></i>Retirer \
							</button></td>";
			} else {
				modal += "<td> (Chef d'équipe) </td>";
			}
			modal += "</td></tr>";
	  	}
	  	modal += "		</tbody></table> \
	  				</div> \
	  				<div class=\"six wide column\"> \
	  					<form class=\"ui form\">\
 						 <h4 class=\"ui dividing header\">Ajouter des membres</h4>\
 						 <div class=\"required field\"> \
							<label>Membre à ajouter</label> \
			      				<select id=\"add_tmember_select"+team.id+"\" class=\"ui fluid search dropdown\" multiple >";
		for(var i = 0; i<window.user_list.length; i++) {
 			modal += "<option value=\""+window.user_list[i].id+"\">"+window.user_list[i].firstname + " " + window.user_list[i].lastname+"</option>";
 		}
		modal +=				"</select> \
		  				 </div> \
		  				 <div id=\"add_tmember_btn"+team.id+"\" class=\"ui green button\" onClick=\"DoleticUIModule.insertTeamMember("+team.id+");\">Ajouter</div> \
 						</form>\
	  				</div> \
	  			</div> \
	  			<div class=\"row\"></div>\
	  		</div> \
		</div>";
		
		var $mod = $("#something").find("#tmodal_"+team.id);
		if (!$mod.length) {
		    $("body").append(modal);
		} else {
		    $mod.replaceWith(modal);
		}
		//$("body").append(modal);
		$("#add_tmember_select"+team.id).dropdown();
	}

	this.fillUserDetails = function(userId) {
		this.userId = userId;
		UserDataServicesInterface.getById(userId, function(data) {
			// if no service error
			if(data.code == 0 && data.object != "[]") {
				$('#det_name').html(data.object.gender + " " + data.object.firstname + " " + data.object.lastname);
				$('#det_pos').html(data.object.last_pos.label);
				$('#det_birth').html(data.object.birthdate);
				$('#det_country').html(data.object.country);
				$('#det_city').html(data.object.city);
				$('#det_add').html(data.object.address);
				$('#det_postal').html(data.object.postal_code);
				$('#det_tel').html(data.object.tel);
				$('#det_mail').html(data.object.email);
				$('#det_year').html(data.object.school_year + data.object.insa_dept);
				$('#det').show();
				$('#det').html("Détails de "+ data.object.firstname + " " + data.object.lastname);
				$('#det').click();
				$('#admm_btn').attr("onClick", "DoleticUIModule.insertNewAdmMembership("+data.object.user_id+"); return false;");
				$('#intm_btn').attr("onClick", "DoleticUIModule.insertNewIntMembership("+data.object.user_id+"); return false;");
				$('#admm_tab').click();

				// Fill memberships tables
				AdmMembershipServicesInterface.getUserAdmMemberships(data.object.user_id, function(data) {
					var html = "";
					for(var i=0; i<data.object.length; i++) {
						if(!(data.object[i].fee && data.object[i].form && data.object[i].certif)) {
							html += "<tr class=\"warning\">";
						} else {
							html += "<tr>";
						}
						html += "<td>"+data.object[i].start_date+"</td>";
						html += "<td>"+data.object[i].end_date+"</td>";
						html += "<td>"+data.object[i].fee+"</td>";
						html += "<td>"+data.object[i].form+"</td>";
						html += "<td>"+data.object[i].certif+"</td>";
						html += "<td>"+data.object[i].ag+"</td>";
						html += "<td>temp</td></tr>";
						html = html.replace(/false/g, "Non");
						html = html.replace(/true/g, "Oui");
					}
					$("#admm_body").html(html);
				});
				IntMembershipServicesInterface.getUserIntMemberships(data.object.user_id, function(data) {
					var html = "";
					for(var i=0; i<data.object.length; i++) {
						if(!(data.object[i].fee && data.object[i].form && data.object[i].certif && data.object[i].rib && data.object[i].identity)) {
							html += "<tr class=\"warning\">";
						} else {
							html += "<tr>";
						}
						html += "<td>"+data.object[i].start_date+"</td>";
						html += "<td>"+data.object[i].fee+"</td>";
						html += "<td>"+data.object[i].form+"</td>";
						html += "<td>"+data.object[i].certif+"</td>";
						html += "<td>"+data.object[i].rib+"</td>";
						html += "<td>"+data.object[i].identity+"</td>";
						html += "<td>temp</td></tr>";
						html = html.replace(/false/g, "Non");
						html = html.replace(/true/g, "Oui");
					}
					$("#intm_body").html(html);
				});
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});

	}

	this.clearNewUserForm = function() {
		/// \todo trouver quelque chose de mieux ici pour le reset du selecteur
		/*DoleticUIModule.fillCountrySelector();
		DoleticUIModule.fillPositionSelector();
		DoleticUIModule.fillINSADeptSelector();
		DoleticUIModule.fillGenderSelector();
		DoleticUIModule.fillSchoolYearSelector();*/
		
		$('#user_form')[0].reset();
		$('#user_form .dropdown').dropdown('restore defaults');

		$('#adduser_btn').html("Ajouter");
		$('#adduser_btn').attr("onClick", "DoleticUIModule.insertNewUser(); return false;");
		// clear error
		if(this.hasInputError) {
			// disable has error
			this.hasInputError = false;
			// change input style
			$('#user_form').attr('class', 'ui form segment');
			$('#firstname_field').attr('class', 'required field');
			$('#lastname_field').attr('class', 'required field');
			// remove error elements
			$('#subject_error').remove();
			$('#data_error').remove();
		}
	}

	this.clearNewTeamForm = function() {
		$('#team_form')[0].reset();
		$('#team_form .dropdown').dropdown('restore defaults');
		$('#addteam_btn').html("Ajouter");
		$('#addteam_btn').attr("onClick", "DoleticUIModule.insertNewTeam(); return false;");
		/// \todo trouver quelque chose de mieux ici pour le reset du selecteur
		//DoleticUIModule.fillDivisionSelector();
		// clear error
		if(this.hasInputError) {
			// disable has error
			this.hasInputError = false;
			// change input style
			//$('#support_form').attr('class', 'ui form segment');
			//$('#subject_field').attr('class', 'required field');
			//$('#data_field').attr('class', 'required field');
			// remove error elements
			//$('#subject_error').remove();
			//$('#data_error').remove();
		}
	}

	this.clearNewAdmMembershipForm = function() {
		$('#admm_form')[0].reset();
		$('#admm_form .dropdown').dropdown('restore defaults');
		// reset selector
		// clear error
		if(this.hasInputError) {
			// disable has error
			this.hasInputError = false;
			// change input style
			//$('#support_form').attr('class', 'ui form segment');
			//$('#subject_field').attr('class', 'required field');
			//$('#data_field').attr('class', 'required field');
			// remove error elements
			//$('#subject_error').remove();
			//$('#data_error').remove();
		}
	}

	this.clearNewIntMembershipForm = function() {
		$('#intm_form')[0].reset();
		$('#intm_form .dropdown').dropdown('restore defaults');
		// reset selector
		// clear error
		if(this.hasInputError) {
			// disable has error
			this.hasInputError = false;
			// change input style
			//$('#support_form').attr('class', 'ui form segment');
			//$('#subject_field').attr('class', 'required field');
			//$('#data_field').attr('class', 'required field');
			// remove error elements
			//$('#subject_error').remove();
			//$('#data_error').remove();
		}
	}

	this.showUserInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#user_form').attr('class', 'ui form segment error');
			$('#subject_field').attr('class', 'field error');
			$('#subject_field').append("<div id=\"subject_error\" class=\"ui basic red pointing prompt label transition visible\">Le sujet ne doit pas être vide</div>");
			$('#data_field').attr('class', 'field error');
			$('#data_field').append("<div id=\"data_error\" class=\"ui basic red pointing prompt label transition visible\">La description ne dois pas être vide</div>");
		}
	}

	this.showTeamInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#team_form').attr('class', 'ui form segment error');
			$('#subject_field').attr('class', 'field error');
			$('#subject_field').append("<div id=\"subject_error\" class=\"ui basic red pointing prompt label transition visible\">Le sujet ne doit pas être vide</div>");
			$('#data_field').attr('class', 'field error');
			$('#data_field').append("<div id=\"data_error\" class=\"ui basic red pointing prompt label transition visible\">La description ne dois pas être vide</div>");
		}
	}

	this.showAdmMembershipInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#admm_form').attr('class', 'ui form segment error');
			$('#subject_field').attr('class', 'field error');
			$('#subject_field').append("<div id=\"subject_error\" class=\"ui basic red pointing prompt label transition visible\">Le sujet ne doit pas être vide</div>");
			$('#data_field').attr('class', 'field error');
			$('#data_field').append("<div id=\"data_error\" class=\"ui basic red pointing prompt label transition visible\">La description ne dois pas être vide</div>");
		}
	}

	this.showIntMembershipInputError = function() {
		if(!this.hasInputError) {
			// raise loginError flag
			this.hasInputError = true;
			// show input error elements
			$('#intm_form').attr('class', 'ui form segment error');
			$('#subject_field').attr('class', 'field error');
			$('#subject_field').append("<div id=\"subject_error\" class=\"ui basic red pointing prompt label transition visible\">Le sujet ne doit pas être vide</div>");
			$('#data_field').attr('class', 'field error');
			$('#data_field').append("<div id=\"data_error\" class=\"ui basic red pointing prompt label transition visible\">La description ne dois pas être vide</div>");
		}
	}

	this.addUserHandler = function(data) {
		// if no service error
		if(data.code == 0) {
			// clear ticket form
			DoleticUIModule.clearNewUserForm();
			// alert user that creation is a success
			DoleticMasterInterface.showSuccess("Création réussie !", "L'utilisateur a été créé avec succès !");
			// fill ticket list
			DoleticUIModule.fillUsersList();
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		}
	}

	this.editUserHandler = function(data) {
		// if no service error
		if(data.code == 0) {
			// clear ticket form
			DoleticUIModule.clearNewUserForm();
			// alert user that creation is a success
			DoleticMasterInterface.showSuccess("Edition réussie !", "L'utilisateur a été modifié avec succès !");
			// fill ticket list
			DoleticUIModule.fillUsersList();
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		}
	}

	this.addTeamHandler = function(data) {
		// if no service error
		if(data.code == 0) {
			// clear ticket form
			DoleticUIModule.clearNewTeamForm();
			// alert user that creation is a success
			DoleticMasterInterface.showSuccess("Création réussie !", "L'équipe a été créée avec succès !");
			// fill ticket list
			DoleticUIModule.fillTeamsList();
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		}
	}

	this.editTeamHandler = function(data) {
		// if no service error
		if(data.code == 0) {
			// clear ticket form
			DoleticUIModule.clearNewTeamForm();
			// alert user that creation is a success
			DoleticMasterInterface.showSuccess("Edition réussie !", "L'équipe a été modifiée avec succès !");
			// fill ticket list
			DoleticUIModule.fillTeamsList();
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		}
	}

	this.insertNewUser = function() {
		// ADD OTHER TESTS
		if(DoleticUIModule.checkNewUserForm()) {
		   	// generate credentials according to db
			UserServicesInterface.generateCredentials($('#firstname').val().trim(), $('#lastname').val().trim(), function(data) {
				// Insert new user in db
				UserServicesInterface.insert(data.object.username, data.object.pass, function(data) {
					// Insert user data in db SELECT ?
					UserDataServicesInterface.insert(data.object, 
													$('#gender option:selected').text(),
													$('#firstname').val(), 
													$('#lastname').val(),
													$('#birthdate').val(),
													$('#tel').val(),
													$('#mail').val(),
													$('#address').val(),
													$('#city').val(),
													$('#postalcode').val(),
													$('#country option:selected').text(),
													$('#schoolyear option:selected').text(),
													$('#dept option:selected').text(),
													$('#position option:selected').text(),
													DoleticUIModule.addUserHandler);
				});
			});
		} else {
			DoleticUIModule.showUserInputError();
		}
	}

	this.insertNewTeam = function() {
		if(DoleticUIModule.checkNewTeamForm()) {
		   	// retreive missing information
			TeamServicesInterface.insert(
				$('#tname').val(),
				$('#leader').val(),
				$('#division option:selected').text(),
				DoleticUIModule.addTeamHandler
			);
		} else {
			DoleticUIModule.showTeamInputError();
		}
	}

	this.insertNewAdmMembership = function(userId) {
		if(DoleticUIModule.checkNewAdmMembershipForm()) {
		   	// retreive missing information
			var handler = function() {
				DoleticUIModule.fillUserDetails(userId);
				DoleticUIModule.clearNewAdmMembershipForm();
			};
			var options = document.getElementById("docs_adm").options;
			AdmMembershipServicesInterface.insert(
				userId, // Retenir l'utilisateur concerné
				$('#sdatea').val(),
				$('#edate').val(),
				options[0].selected,
				options[1].selected,
				options[2].selected,
				$('#ag').val(),
				handler);
		} else {
			DoleticUIModule.showAdmMembershipInputError();
		}
	}

	this.insertNewIntMembership = function(userId) {
		if($('#sdatei').val().length > 0) {
		   	var handler = function() {
				DoleticUIModule.fillUserDetails(userId);
				DoleticUIModule.clearNewIntMembershipForm();
			};
		   	// retreive missing information
			var options = document.getElementById("docs_int").options;
			IntMembershipServicesInterface.insert(
				userId,
				$('#sdatei').val(),
				options[0].selected,
				options[1].selected,
				options[2].selected,
				options[3].selected,
				options[4].selected,
				handler);
		} else {
			DoleticUIModule.showIntMembershipInputError();
		}
	}

	this.insertTeamMember = function(id) {
		var select = document.getElementById("add_tmember_select"+id);
		var options = new Array();
		for(var i=0; i<select.options.length; i++) {
			if(select.options[i].selected) {
				options.push(select.options[i].value);
			}
		}
		TeamServicesInterface.insertMember(id, options, function() {

		});
	}

	this.editUser = function(id, user_id) {
		UserDataServicesInterface.getById(id, function(data) {
			// if no service error
			if(data.code == 0 && data.object != "[]") {
				$('#firstname').val(data.object.firstname);
				$('#firstname').prop('readonly', true);
				$('#lastname').val(data.object.lastname);
				$('#lastname').prop('readonly', true);
				$('#birthdate').val(data.object.birthdate);
				$('#country').val(data.object.country);
				$('#city').val(data.object.city);
				$('#address').val(data.object.address);
				$('#postalcode').val(data.object.postal_code);
				$('#tel').val(data.object.tel);
				$('#mail').val(data.object.email);
				//$('#schoolyear').val(data.object.school_year + data.object.insa_dept);
				//$('#dept').val();
				//$('#position').val();
				//$('#interv').val();

				/* A METTRE DANS UNE FONCTION SEPAREE */
				/*var genderSelect = document.getElementById('gender');
				for (var i = 0; i < genderSelect.options.length; i++) {
				    if (genderSelect.options[i].text === data.object.gender) {
				        genderSelect.selectedIndex = i;
				        break;
				    }
				}*/
				$('#adduser_btn').html("Confirmer");
				$('#adduser_btn').attr("onClick", "DoleticUIModule.updateUser("+id+", "+user_id+"); return false;");
				$('#user_form').transition('pulse');
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.editTeam = function(id) {
		TeamServicesInterface.getById(id, function(data) {
			// if no service error
			if(data.code == 0 && data.object != "[]") {
				$('#tname').val(data.object.name);

				$('#addteam_btn').html("Confirmer");
				$('#addteam_btn').attr("onClick", "DoleticUIModule.updateTeam("+id+"); return false;");
				$('#team_form').transition('pulse');
			} else {
				// use default service service error handler
				DoleticServicesInterface.handleServiceError(data);
			}
		});
	}

	this.editAdmMembership = function(id) {
		
	}

	this.editIntMembership = function(id) {
		
	}

	this.updateUser = function(id, user_id) {
		// ADD OTHER TESTS
		if(DoleticUIModule.checkNewUserForm()) {
			// Insert user data in db SELECT ?
			UserDataServicesInterface.update(id, user_id,
				$('#gender option:selected').text(),
				$('#firstname').val(), 
				$('#lastname').val(),
				$('#birthdate').val(),
				$('#tel').val(),
				$('#mail').val(),
				$('#address').val(),
				$('#city').val(),
				$('#postalcode').val(),
				$('#country option:selected').text(),
				$('#schoolyear option:selected').text(),
				$('#dept option:selected').text(),
				$('#position option:selected').text(),
				DoleticUIModule.editUserHandler);
		} else {
			DoleticUIModule.showUserInputError();
		}
		DoleticUIModule.clearNewUserForm();
	}

	this.updateTeam = function(id) {
		// ADD OTHER TESTS
		if(DoleticUIModule.checkNewTeamForm()) {
			// Update team data in DB
			TeamServicesInterface.update(id,
				$('#tname').val(),
				$('#leader').val(),
				$('#division option:selected').text(),
				DoleticUIModule.editTeamHandler
			);
		} else {
			DoleticUIModule.showTeamInputError();
		}
		DoleticUIModule.clearNewTeamForm();
	}

	this.updateAdmMembership = function(id) {
		
	}

	this.updateIntMembership = function(id) {
		
	}

	this.deleteUser = function(id, user_id) {
		// Création fonction de suppression (nécessaire pour passer une référence et nom un retour)
		var del = function() {
			UserDataServicesInterface.delete(id, user_id, function() {
				UserServicesInterface.delete(id, function(id) {
					DoleticMasterInterface.hideConfirmModal();
					DoleticMasterInterface.showSuccess("Suppression réussie !", "L'utilisateur a été supprimé avec succès !");
					DoleticUIModule.fillUsersList();
					
				});
			});
		};
		// Confirmation
		DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove user icon\"\>\<\/i\>", 
			"Etes-vous sûr de vouloir supprimer l'utilisateur ? Cette opération est irréversible.", del, DoleticMasterInterface.hideConfirmModal);
	}

	this.deleteTeam = function(id) {
		// Création fonction de suppression (nécessaire pour passer une référence et nom un retour)
		var del = function() {
			TeamServicesInterface.delete(id, function() {
				DoleticMasterInterface.hideConfirmModal();
				DoleticMasterInterface.showSuccess("Suppression réussie !", "L'équipe a été supprimée avec succès !");
				DoleticUIModule.fillTeamsList();
			});
		};
		// Confirmation
		DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove icon\"\>\<\/i\>", 
			"Etes-vous sûr de vouloir supprimer l'équipe ? Cette opération est irréversible.", del, DoleticMasterInterface.hideConfirmModal);	
	}

	this.deleteAdmMembership = function(id) {
		
	}

	this.deleteIntMembership = function(id) {
		
	}

	this.deleteTeamMember = function(id, memberId) {
		TeamServicesInterface.deleteMember(id, memberId, function(data) {
			if(data.object != -1) {
				DoleticUIModule.fillTeamsList(); // Changer pour une solution plus légère (juste une équipe)
			} else {
				DoleticMasterInterface.showError("Erreur !", "La suppression du membre a échoué.");
			}
			
		});
	}

	this.checkNewUserForm = function() {
		return  $('#firstname').val() != "" &&
				$('#lastname').val() != "" &&
				$('#birthdate').val() != "" &&
				$('#tel').val() != "" &&
				$('#mail').val() != "" &&
				$('#address').val() != "" &&
				$('#city').val() != "" &&
				$('#postalcode').val() != "" &&
				$('#country option:selected').text() != "" &&
				$('#schoolyear option:selected').text() != "" &&
				$('#dept option:selected').text() != "" &&
				$('#position option:selected').text() != "";

	}

	this.checkNewTeamForm = function() {
		return  $('#tname').val() != "" &&
				$('#leader option:selected').text() != "" &&
				$('#division option:selected').text() != "";
	}

	this.checkNewAdmMembershipForm = function() {
		return true;
	}

	this.checkNewIntMembershipForm = function() {
		return true;
	}

}