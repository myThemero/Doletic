var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('HR_UIModule', 'Nicolas Sorin', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {

        this.super.render(htmlNode, this);

        window.currentDetails = -1;

        // Load HTML templates
        DoleticUIModule.getStatsTab();
        DoleticUIModule.getMembersTab();

        // hide user details tab
        $('#det').hide();
        //Draw graphs
        DoleticUIModule.drawGraphs();
        DoleticUIModule.fillValueIndicators();
        // fill user and team list. User first to fill user_list global array
        DoleticUIModule.fillUsersList();
        // activate items in tabs
        $('.menu .item').tab();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"ui two column grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
				  	<div class=\"sixteen wide column\" id=\"page_content\"> \
				  		<div class=\"ui top attached tabular menu\" id=\"tabmenu\" > \
  							<a class=\"item active\" data-tab=\"stats\">Statistiques</a> \
  							<a class=\"item\" id=\"memlist\" data-tab=\"memberlist\">Liste des membres</a> \
						</div> \
						<div class=\"ui bottom attached tab segment active\" data-tab=\"stats\"> \
							<div id=\"statsTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
					    </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"memberlist\"> \
							<div id=\"membersTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
						</div> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
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
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    /**
     *    Load the HTML code of the Stats Tab
     */
    this.getStatsTab = function () {
        $('#agr_form_modal').remove(); // Necessary to avoid duplicate (look for better solution)
        $('#statsTab').load("../modules/hr/ui/templates/statsTab.html", function () {
            $('#ag_container').prev('.ui.horizontal.divider').remove();
            $('#ag_container').remove();
        });
    };

    /**
     *    Load the HTML code of the Members Tab
     */
    this.getMembersTab = function () {
        $('#user_form_modal').remove(); // Necessary to avoid duplicate (look for better solution)
        $('#membersTab').load("../modules/hr/ui/templates/membersTab.html", function () {
            DoleticMasterInterface.makeDefaultCalendar('birthdate_calendar');
            $('#toggle_old').change(DoleticUIModule.fillUsersList);
            $('#adduser_modal_btn').remove();
        });
    };

    this.hasInputError = false;

    this.fillUsersList = function () {
        // Load old members
        var showOld = $('#toggle_old').prop('checked');
        UserDataServicesInterface.getAll(function (data) {
            // Delete and recreate table so Datatables is reinitialized
            $("#user_table_container").html("");
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                // Store data in global array
                window.user_list = [];
                // iterate over values to build options
                var content = "<table class=\"ui very basic celled table\" id=\"user_table\"> \
                <thead> \
                    <tr>\
                        <th>Nom/Mail</th> \
                        <th>Poste</th> \
                        <th>Pôle</th> \
                        <th>Téléphone</th> \
                        <th>Année</th> \
                        <th>Actions</th> \
                    </tr>\
                </thead>\
                <tfoot> \
                    <tr>\
                        <th>Nom</th> \
                        <th>Poste</th> \
                        <th>Pôle</th> \
                        <th>Téléphone</th> \
                        <th>Année</th> \
                        <th></th> \
                    </tr>\
                </tfoot>\
                <tbody id=\"user_body\">";

                var filters = [
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.reset_filter
                ];
                for (var i = 0; i < data.object.length; i++) {
                    window.user_list[data.object[i].id] = data.object[i];

                    if (!(data.object[i].old && !showOld)) {

                        if (data.object[i].disabled) {

                        } else {
                            content += "<tr><td> \
			        				<h4 class=\"ui header\"> \
			          				<div class=\"content\">" + data.object[i].firstname + " " + data.object[i].lastname +
                                "<div class=\"sub header\"><a href=\"mailto:" + data.object[i].email + "\" target=\"_blank\">" + data.object[i].email + "</a></div> \
			        				</div> \
			      					</h4></td> \
			      					<td>" + data.object[i].position[0].label + "</td> \
			      					<td>" + data.object[i].position[0].division + "</td> \
			      					<td>" + data.object[i].tel + "</td> \
			      					<td>" + data.object[i].school_year + data.object[i].insa_dept + "</td> \
			    				<td><i>Aucune</i></td> \
			    				</tr>";
                        }
                    }
                }
                content += "</tbody></table>";
                $('#user_table_container').append(content);
                DoleticMasterInterface.makeDataTables('user_table', filters);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.drawGraphs = function () {
        IndicatorServicesInterface.processAllGraphByModule('hr', function (data) {
            //console.log(data.object);
            DoleticMasterInterface.drawGraphs(data.object, 'graphs');
        });
    };

    this.fillValueIndicators = function () {
        IndicatorServicesInterface.processAllValueByModule('hr', function (data) {
            //console.log(data.object);
            DoleticMasterInterface.fillValueIndicators(data.object, 'indicators_body');
        });
    };

    this.fillTableIndicators = function () {
        IndicatorServicesInterface.processAllTableByModule('hr', function (data) {
            //console.log(data.object);
            DoleticMasterInterface.fillTableIndicators(data.object, 'tables');
            $('#indictab_0 .indicval_label').click(function () {
                $('#user_table_Pôle').dropdown('set selected', $(this).html()).change();
                $('#memlist').click();
            });
            $('#indictab_1 .indicval_label').click(function () {
                $('#user_table_Année').val($(this).html()).change();
                $('#memlist').click();
            });
        });
    };
};