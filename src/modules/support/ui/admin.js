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
		// fill open ticket list
		TicketServicesInterface.getTicketsByStatus(1,DoleticUIModule.fillOpenTicketsList);
		// fill wip ticket list
		TicketServicesInterface.getTicketsByStatus(2,DoleticUIModule.fillWorkInProgressTicketsList);
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
						<div class=\"ui horizontal divider\"><i class=\"red radio icon\"></i> Tickets ouverts</div> \
						<div id=\"open_ticket_list\" class=\"ui very relaxed celled selection list\"> \
						  <!-- OPEN TICKETS WILL GO HERE --> \
						</div> \
					</div> \
					<div class=\"three wide column\"> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
				  <div class=\"three wide column\"> \
					</div> \
					<div class=\"ten wide column\"> \
						<div class=\"ui horizontal divider\"><i class=\"orange spinner icon\"></i> Tickets en traitement</div> \
						<div id=\"wip_ticket_list\" class=\"ui very relaxed celled selection list\"> \
						  <!-- WORK IN PROGRESS TICKETS WILL GO HERE --> \
						</div> \
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

	this.fillList = function(list, action, json) {
		// create content var to build html
		var content = "";
		// iterate over values to build options
		for (var i = 0; i < json.length; i++) {
			content += "<div class=\"item\"> \
							<div class=\"right floated content\"> \
      							<div class=\"ui button\" onClick=\"DoleticUIModule.treatTicket('"+json[i].id+"');\">"+action+"</div> \
    						</div> \
						  <div class=\"middle aligned content\"> \
						    <a class=\"header\">"+json[i].subject+"</a> \
						    <div class=\"description\">"+json[i].data+"</div>  \
						  </div> \
						</div>";
		};
		// insert html content
		$(list).html(content);
	}
	/**
	 *	Passe le ticket dans l'etat "en traitement"
	 */
	this.treatTicket = function(id) {
		TicketServicesInterface.nextTicketStatus(id, DoleticUIModule.refreshLists);
	}
	/**
	 *	Refresh lists after ticket status change
	 */
	this.refreshLists = function(data) {
		// if no service error
		if(data.code == 0 && data.data != "[]") {
			// fill open ticket list
			TicketServicesInterface.getTicketsByStatus(1,DoleticUIModule.fillOpenTicketsList);
			// fill wip ticket list
			TicketServicesInterface.getTicketsByStatus(2,DoleticUIModule.fillWorkInProgressTicketsList);
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		};
	}

	this.fillOpenTicketsList = function(data) {
		// if no service error
		if(data.code == 0 && data.data != "[]") {
			// fill list
			DoleticUIModule.fillList('#open_ticket_list', 'Traiter', data.data);
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		};
	}
	this.fillWorkInProgressTicketsList = function(data) {
		// if no service error
		if(data.code == 0 && data.data != "[]") {
			// fill list
			DoleticUIModule.fillList('#wip_ticket_list', 'Résolu !', data.data);
		} else {
			// use default service service error handler
			DoleticServicesInterface.handleServiceError(data);
		};
	}

}