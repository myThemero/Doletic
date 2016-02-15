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
		// activate items in tabs
		$('.menu .item').tab();
		// activate checkboxes
		$('.ui.checkbox').checkbox();
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
  							<a class=\"item active\" data-tab=\"documentgen\">Générateur de document</a> \
  							<a class=\"item\" data-tab=\"mail\">Mailing listes</a> \
						</div> \
						<div class=\"ui bottom attached tab segment active\" data-tab=\"documentgen\"> \
						  <div class=\"ui top attached segment\"> \
				  			<h3><i class=\"file text outline icon\"></i> Gestion des modèles de documents</h3> \
					  		</div> \
					  		<div class=\"ui attached segment\"> \
					  			<div id=\"doc_templates\" class=\"ui celled selection list\"> \
									<!-- DOC TEMPLATES WILL GO HERE --> \
									<div class=\"item\"> \
										Proposition commerciale \
										<div class=\"ui small basic icon right floated buttons\"> \
										  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
										</div> \
									</div> \
									<div class=\"item\"> \
										 Bulletin de Livraison \
										 <div class=\"ui small basic icon right floated buttons\"> \
										  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
										</div> \
									</div> \
									<div class=\"item\"> \
										 Bulletin de Versement \
										 <div class=\"ui small basic icon right floated buttons\"> \
										  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
										</div> \
									</div> \
							  	</div> \
					  		</div> \
					  		<div class=\"ui bottom attached blue button\" onClick=\"alert('not implemented yet');\">Ajouter un document modèle</div> \
							</div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"mail\"> \
						<div class=\"ui top attached segment\"> \
				  			<h3><i class=\"mail outline icon\"></i> Gestion des mailing listes</h3> \
				  		</div> \
				  		<div class=\"ui attached segment\"> \
				  			<div id=\"mailing_lists\" class=\"ui celled selection list\"> \
								<!-- MAILING LISTS WILL GO HERE --> \
								<div class=\"item\"> \
									<div class=\"ui red horizontal label\">privée</div> \
									CA (<a href=\"mailto:ca@etic-insa.com\">ca@etic-insa.com</a>) \
									<div class=\"ui small basic icon right floated buttons\"> \
									  <button class=\"ui button\"><i class=\"table icon\"></i></button> \
									  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
									</div> \
								</div> \
								<div class=\"item\"> \
									<div class=\"ui red horizontal label\">privée</div> \
									Membres (<a href=\"mailto:membres@etic-insa.com\">membres@etic-insa.com</a>) \
									<div class=\"ui small basic icon right floated buttons\"> \
									  <button class=\"ui button\"><i class=\"table icon\"></i></button> \
									  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
									</div> \
								</div> \
								<div class=\"item\"> \
									<div class=\"ui green horizontal label\">ouverte</div> \
									Intervenants (<a href=\"mailto:intervenants@etic-insa.com\">intervenants@etic-insa.com</a>) \
									<div class=\"ui small basic icon right floated buttons\"> \
									  <button class=\"ui button\"><i class=\"table icon\"></i></button> \
									  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
									</div> \
								</div> \
								<div class=\"item\"> \
									<div class=\"ui red horizontal label\">privée</div> \
									Clients (<a href=\"mailto:clients@etic-insa.com\">clients@etic-insa.com</a>) \
									<div class=\"ui small basic icon right floated buttons\"> \
									  <button class=\"ui button\"><i class=\"table icon\"></i></button> \
									  <button class=\"ui red button\"><i class=\"red trash icon\"></i></button> \
									</div> \
								</div> \
						  	</div> \
				  		</div> \
				  		<div class=\"ui bottom attached blue button\" onClick=\"$('#maillist_modal').modal('show');\">Ajouter une mailing liste</div> \
						</div> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
				  </div> \
				</div> \
				<div id=\"maillist_modal\" class=\"ui modal\"> \
				  <div class=\"header\">Ajouter une mailing liste</div> \
				  <div class=\"content\"> \
					<form id=\"maillist_form\" class=\"ui form\"> \
					  <div class=\"two fields\"> \
						<div class=\"inline field\"> \
						  <label>Nom de la liste</label> \
						  <input id=\"name_input\" name=\"name\" placeholder=\"Nom de la mailing liste\" type=\"text\" onChange=\"DoleticUIModule.updateMaillistForm();\"> \
						</div> \
						<div class=\"inline field\"> \
						  <label id=\"mailing_mail\"></label> \
						</div> \
					  </div> \
					  <div class=\"field\"> \
					    <div id=\"can_subscribe_cb\" class=\"ui checkbox\"> \
					      <input class=\"hidden\" tabindex=\"0\" type=\"checkbox\"> \
					      <label>Inscription autorisée</label> \
					    </div> \
					  </div> \
					</form> \
				  </div> \
				  <div class=\"actions\"> \
				    <div class=\"ui red deny button\" onClick=\"DoleticUIModule.resetMaillist();\">Annuler</div> \
				    <div class=\"ui positive right labeled icon button\" onClick=\"DoleticUIModule.addMaillist();\">Ajouter<i class=\"checkmark icon\"></i> \
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

	this.nightMode = function(on) {
	    if(on) {
	   		/// \todo implement here
	    } else {
	    	/// \todo implement here
	    }
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

	this.resetMaillist = function() {
		$('#maillist_form')[0].reset();
		$('#mailing_mail').html('');
		$('#maillist_modal').modal('hide');
	}

	this.addMaillist = function() {
		alert('not implemented yet');
		this.resetMaillist();
	}

	this.updateMaillistForm = function() {
		$('#mailing_mail').html(this.formatName($('#name_input').val())+DoleticConfig.JE.mail_domain);
	}

	this.formatName = function(name) {
		return name.replace(' ', '-').toLowerCase();
	}
}
