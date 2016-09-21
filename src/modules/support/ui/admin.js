var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('Support_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // refresh lists
        this.refreshTicketLists();
        window.postLoad();
    };
    /**
     *    Override build function
     */
    this.build = function () {
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
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data) {
        this.super.uploadSuccessHandler(id, data);
    };

    this.nightMode = function (on) {
        if (on) {
            $('.ui.button.treat').attr('class', 'ui button treat inverted');
            $('.ui.horizontal.divider').attr('class', 'ui horizontal divider inverted');
            $('#open_ticket_list').attr('class', 'ui very relaxed celled selection list inverted');
            $('#wip_ticket_list').attr('class', 'ui very relaxed celled selection list inverted');
        } else {
            $('.ui.button.treat.inverted').attr('class', 'ui button treat');
            $('.ui.horizontal.divider.inverted').attr('class', 'ui horizontal divider');
            $('#open_ticket_list').attr('class', 'ui very relaxed celled selection list');
            $('#wip_ticket_list').attr('class', 'ui very relaxed celled selection list');
        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    this.fillList = function (list, action, json) {
        // create content var to build html
        var content = "";
        // iterate over values to build options
        for (var i = 0; i < json.length; i++) {
            content += "<div class=\"item\"> \
							<div class=\"right floated content\"> \
      							<div class=\"ui button treat\" onClick=\"DoleticUIModule.treatTicket('" + json[i].id + "');\">" + action + "</div> \
    						</div> \
						  <div class=\"middle aligned content\"> \
						    <a class=\"header\">" + json[i].subject + "</a> \
						    <div class=\"description\">" + json[i].data + "</div>  \
						  </div> \
						</div>";
        }
        // insert html content
        $(list).html(content);
        // update night mode
        DoleticUISettings.applySettings();
    };
    /**
     *    Passe le ticket dans l'etat "en traitement"
     */
    this.treatTicket = function (id) {
        TicketServicesInterface.nextTicketStatus(id, DoleticUIModule.refreshTicketLists);
    };
    /**
     *    Refresh lists after ticket status change
     */
    this.refreshTicketLists = function () {
        // fill open ticket list
        DoleticUIModule.fillOpenTicketsList();
        // fill wip ticket list
        DoleticUIModule.fillWorkInProgressTicketsList();
    };

    this.fillOpenTicketsList = function () {
        // clear list first
        $('#open_ticket_list').html('');
        // retrieve tickets
        TicketServicesInterface.getTicketsByStatus(1, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                // fill list
                DoleticUIModule.fillList('#open_ticket_list', 'Traiter', data.object);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };
    this.fillWorkInProgressTicketsList = function () {
        // clear list first
        $('#wip_ticket_list').html('');
        // retrieve tickets
        TicketServicesInterface.getTicketsByStatus(2, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                // fill list
                DoleticUIModule.fillList('#wip_ticket_list', 'Résolu !', data.object);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    }

};