var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('GRC_UIModule', 'Olivier Vicente', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // activate items in tabs
        $('.menu .item').tab();
        // Load HTML templates
        DoleticUIModule.getContactsTab();
        DoleticUIModule.getCompaniesTab();
        DoleticUIModule.getStatsTab();
        // Fill tables
        DoleticUIModule.fillFirmList(true);
        window.postLoad();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"ui two column grid container\"> \
 				  	<div class=\"row\"> \
 				  		<div class=\"sixteen wide column\"> \
 				  			<div class=\"ui top attached tabular menu\"> \
   								<a class=\"item active\" data-tab=\"contacts\">Gestion des Contacts</a> \
   								<a class=\"item\" data-tab=\"companies\">Gestion des Sociétés</a> \
								<a class=\"item\" data-tab=\"stats\">Statistiques</a> \
 							</div> \
 							<div class=\"ui bottom attached tab segment active\" data-tab=\"contacts\"> \
								<div id=\"contactsTab\"> \
								</div> \
 					    	</div> \
 							<div class=\"ui bottom attached tab segment\" data-tab=\"companies\"> \
								<div id=\"companiesTab\"> \
								</div> \
                        	</div> \
							<div class=\"ui bottom attached tab segment\" data-tab=\"stats\"> \
								<div id=\"statsTab\"> \
								</div> \
							</div> \
						</div> \
 					</div> \
	 				<div class=\"row\"> \
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

        } else {

        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    /**
     *    Load the HTML code of the Contacts Tab
     */
    this.getContactsTab = function () {
        $('#contactsTab').load("../modules/grc/ui/templates/contactsTab.html", function() {
            $('#contact_modal_btn').remove();
        });
    };

    /**
     *    Load the HTML code of the Companies Tab
     */
    this.getCompaniesTab = function () {
        $('#companiesTab').load("../modules/grc/ui/templates/companiesTab.html", function() {
            $('#company_modal_btn').remove();
        });
    };

    /**
     *    Load the HTML code of the Stats Tab
     */
    this.getStatsTab = function () {
        $('#statsTab').load("../modules/grc/ui/templates/statsTab.html");
    };

    /**
     *    Add the gender selector
     */
    this.fillFirmList = function (fillContact) {
        var showOld = $('#toggle_old_firms').prop('checked');
        FirmServicesInterface.getAll(function (data) {
            window.firm_list = [];
            $('#company_table_container').html('');
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                var content = "<table class=\"ui very basic celled table\" id=\"company_table\"> \
                <thead> \
                    <tr>\
                        <th>Nom</th> \
                        <th>SIRET</th> \
                        <th>Type</th> \
                        <th>Adresse</th> \
                        <th>Code postal</th> \
                        <th>Ville</th> \
                        <th>Pays</th> \
                        <th>Actions</th> \
                    </tr>\
                </thead>\
                <tfoot> \
                    <tr>\
                        <th>Nom</th> \
                        <th>SIRET</th> \
                        <th>Type</th> \
                        <th>Adresse</th> \
                        <th>Code postal</th> \
                        <th>Ville</th> \
                        <th>Pays</th> \
                        <th></th> \
                    </tr>\
                </tfoot>\
                <tbody id=\"company_body\">";

                var filters = [
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.reset_filter
                ];
                var selector_content = '';
                var counter = 0;
                for (var i = 0; i < data.object.length; i++) {
                    window.firm_list[data.object[i].id] = data.object[i];
                    if (showOld || counter < 100) {
                        content += "<tr><td>" + data.object[i].name + "</td> \
			      					<td>" + data.object[i].siret + "</td> \
			      					<td>" + data.object[i].type + "</td> \
			      					<td>" + data.object[i].address + "</td> \
			      					<td>" + data.object[i].postal_code + "</td>\
			    					<td>" + data.object[i].city + "</td>\
                                    <td>" + data.object[i].country + "</td> \
			    				<td> \
			    					<i>Aucune</i>\
			    				</td> \
			    				</tr>";
                    }
                    selector_content += '<div class="item" data-value="' + data.object[i].id + '">' + data.object[i].name + '</div>';
                    counter++;
                }
                content += "</tbody></table>";
                $('#company_table_container').append(content);
                DoleticMasterInterface.makeDataTables('company_table', filters);
                if (fillContact) {
                    DoleticUIModule.fillContactList();
                }
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillContactList = function () {
        var showOld = $('#toggle_old_contacts').prop('checked');
        ContactServicesInterface.getAll(function (data) {
            $('#contact_table_container').html('');
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                var content = "<table class=\"ui very basic celled table\" id=\"contact_table\"> \
                <thead> \
                    <tr>\
                        <th>Nom/Email</th> \
                        <th>Type</th> \
                        <th>Téléphone</th> \
                        <th>Mobile</th> \
                        <th>Société</th> \
                        <th>Role</th> \
                        <th>Actions</th> \
                    </tr>\
                </thead>\
                <tfoot> \
                    <tr>\
                        <th>Nom/Email</th> \
                        <th>Type</th> \
                        <th>Téléphone</th> \
                        <th>Mobile</th> \
                        <th>Société</th> \
                        <th>Role</th> \
                        <th></th> \
                    </tr>\
                </tfoot>\
                <tbody id=\"company_body\">";

                var filters = [
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.reset_filter
                ];
                var counter = 0;
                for (var i = 0; i < data.object.length && (showOld || counter<100); i++) {
                    content += "<tr><td> \
			        				<h4 class=\"ui header\"> \
			          				<div class=\"content\">" + data.object[i].firstname + " " + data.object[i].lastname +
                        "<div class=\"sub header\"><a href=\"mailto:" + data.object[i].email + "\" target=\"_blank\">" + data.object[i].email + "</a></div> \
			        				</div> \
			      					</h4></td> \
			      					<td>" + data.object[i].category + "</td> \
			      					<td>" + data.object[i].phone + "</td> \
			      					<td>" + data.object[i].cellphone + "</td> \
			      					<td>" + (typeof window.firm_list[data.object[i].firm_id] !== 'undefined' ? window.firm_list[data.object[i].firm_id].name : '<i>Aucune</i>') + "</td> \
			    				    <td>" + data.object[i].role + "</td> \
			    				    <td> \
			    					<i>Aucune</i>\
			    				</td> \
			    				</tr>";
                    counter++;
                }
                content += "</tbody></table>";
                $('#contact_table_container').append(content);
                DoleticMasterInterface.makeDataTables('contact_table', filters);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };
};
