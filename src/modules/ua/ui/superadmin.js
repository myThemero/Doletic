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
        DoleticUIModule.getProjectsTab();
        DoleticUIModule.getSollicTab();
        DoleticUIModule.getDisabledTab();
        DoleticUIModule.getArchivedTab();
        DoleticUIModule.getDetailsTab();

        // hide project details tab
        $('#det').hide();
        // display indicators
        // DoleticUIModule.drawGraphs();
        // DoleticUIModule.fillValueIndicators();
        // DoleticUIModule.fillTableIndicators();
        // // fill projects list
        DoleticUIModule.fillProjectsList();
        // // fill country field
        // DoleticUIModule.fillContactSelector();
        // // fill gender field
        // DoleticUIModule.fillFirmSelector();
        // // fill INSA dept field
        // DoleticUIModule.fillUserSelectors();
        // // fill position field
        // DoleticUIModule.fillOriginSelector();
        // // fill school year field
        // DoleticUIModule.fillFieldSelector();
        // //fill division field
        // DoleticUIModule.fillUaTeamSelector();
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
  							<a class=\"item\" id=\"sollist\" data-tab=\"solliclist\">Sollicitations</a> \
  							<a class=\"item\" id=\"prolist\" data-tab=\"projectlist\">Etudes en cours</a> \
  							<a class=\"item\" id=\"dislist\" data-tab=\"disabledlist\">Etudes en attente</a> \
  							<a class=\"item\" id=\"arclist\" data-tab=\"archivedlist\">Etudes archivées</a> \
  							<a class=\"item\" id=\"det\" data-tab=\"projectdetails\">Détails de l'étude</a> \
						</div> \
						<div class=\"ui bottom attached tab segment active\" data-tab=\"stats\"> \
							<div id=\"statsTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
					    </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"solliclist\"> \
							<div id=\"sollicTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
						</div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"projectlist\"> \
							<div id=\"projectsTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
						</div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"disabledlist\"> \
							<div id=\"disabledTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
						</div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"archivedlist\"> \
							<div id=\"archivedTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
						</div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"projectdetails\"> \
							<div id=\"detailsTab\">\
							</div>\
						</div> \
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
        $('#statsTab').load("../modules/ua/ui/templates/statsTab.html");
    };

    /**
     *    Load the HTML code of the Members Tab
     */
    this.getProjectsTab = function () {
        $('#projectsTab').load("../modules/ua/ui/templates/projectsTab.html");
    };

    /**
     *    Load the HTML code of the Members Tab
     */
    this.getSollicTab = function () {
        $('#project_form_modal').remove(); // Necessary to avoid duplicate (look for better solution)
        $('#sollicTab').load("../modules/ua/ui/templates/sollicTab.html");
    };

    /**
     *    Load the HTML code of the Members Tab
     */
    this.getDisabledTab = function () {
        $('#disabledTab').load("../modules/ua/ui/templates/disabledTab.html");
    };

    /**
     *    Load the HTML code of the Teams Tab
     */
    this.getArchivedTab = function () {
        $('#archivedTab').load("../modules/ua/ui/templates/archivedTab.html");
    };
    /**
     *    Load the HTML code of the Details Tab
     */
    this.getDetailsTab = function () {
        $('#detailsTab').load("../modules/ua/ui/templates/detailsTab.html");
    };

    this.hasInputError = false;

    this.fillCountrySelector = function () {
        UserDataServicesInterface.getAllCountries(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '';
                //DoleticUIModule.country_list = data.object;
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += '<div class="item" data-value="' + data.object[i] + '">' + data.object[i] + '</div>';
                }
                // insert html content
                $('#country_search .menu').html(content).dropdown();
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillGenderSelector = function () {
        UserDataServicesInterface.getAllGenders(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '';
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += '<div class="item" data-value="' + data.object[i] + '">' + data.object[i] + '</div>';
                }
                // insert html content
                $('#gender_search .menu').html(content).dropdown();
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillINSADeptSelector = function () {
        UserDataServicesInterface.getAllINSADepts(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '';
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += '<div class="item" data-value="' + data.object[i].label + '">' + data.object[i].label + '</div>';
                }
                // insert html content
                $('#dept_search .menu').html(content).dropdown();
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillSchoolYearSelector = function () {
        UserDataServicesInterface.getAllSchoolYears(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '';
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += '<div class="item" data-value="' + data.object[i] + '">' + data.object[i] + '</div>';
                }
                // insert html content
                $('#schoolyear_search .menu').html(content);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillPositionSelector = function () {
        UserDataServicesInterface.getAllPositions(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                //var content = "<option value=\"\">Poste...</option>";
                var content = "";
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    if (data.object[i] == "Ancien membre") {
                        content += '<div id="old_position" class="disabled item" data-value="' + data.object[i] + '">' + data.object[i] + '</div>';
                    } else {
                        content += '<div class="item" data-value="' + data.object[i] + '">' + data.object[i] + '</div>';
                    }
                }
                // insert html content
                $('#position_search .menu').html(content);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillDivisionSelector = function () {
        UserDataServicesInterface.getAllDivisions(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '';
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += '<div class="item" data-value="' + data.object[i] + '">' + data.object[i] + '</div>';
                }
                // insert html content
                $('#division_search .menu').html(content);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };
    this.fillAGSelector = function () {
        UserDataServicesInterface.getAllAgs(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '<div class="item" data-value=""></div>';
                var table_content = '<table class="ui very basic single line striped table" id="agr_table">\
										<thead><tr><th>Date</th><th>Présents</th><th>Actions</th></tr></thead>\
										<tbody id="agr_body">';
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += '<div class="item" data-value="' + data.object[i].ag + '">' + data.object[i].ag + '</div>';
                    table_content += "<tr><td>" + data.object[i].ag + "</td><td>" + data.object[i].presence + "</td><td><button class=\"ui icon button\"onClick=\"DoleticUIModule.deleteAGR('" + data.object[i].ag + "'); return false;\"> \
			  									<i class=\"remove icon\"></i>Retirer \
											</button></td></tr>";
                }
                table_content += '</tbody></table>';
                // insert html content
                $('#ag_search .menu').html(content);
                $('#agr_table_container').html(table_content);
                DoleticMasterInterface.makeDataTables('agr_table', []);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillProjectsList = function () {
        // Load old members
        ProjectServicesInterface.getAll(function (data) {
            // Delete and recreate table so Datatables is reinitialized
            $("#sollic_table_container").html("");
            $("#project_table_container").html("");
            $("#disabled_table_container").html("");
            $("#archived_table_container").html("");
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                var headFoot = "<thead> \
            <tr>\
            <th>Numéro</th> \
            <th>Nom</th> \
            <th>Domaine</th> \
            <th>Société</th> \
            <th>Date</th> \
            <th>Chargé(s) d'affaires</th> \
                        <th>Consultant(s)</th> \
                        <th>Corresp. qualité</th> \
                        <th>Actions</th> \
                    </tr>\
                </thead>\
                <tfoot> \
                    <tr>\
                        <th>Numéro</th> \
                        <th>Nom</th> \
                        <th>Domaine</th> \
                        <th>Société</th> \
                        <th>Date</th> \
                        <th>Chargé(s) d'affaires</th> \
            <th>Consultant(s)</th> \
            <th>Corresp. qualité</th> \
            <th></th> \
            </tr>\
            </tfoot>\ ";
                // iterate over values to build options
                var sollic_content = "<table class=\"ui very basic celled table\" id=\"sollic_table\">" + headFoot + "<tbody id=\"sollic_body\">";
                var project_content = "<table class=\"ui very basic celled table\" id=\"project_table\">" + headFoot + "<tbody id=\"project_body\">";
                var disabled_content = "<table class=\"ui very basic celled table\" id=\"disabled_table\">" + headFoot + "<tbody id=\"disabled_body\">";
                var archived_content = "<table class=\"ui very basic celled table\" id=\"archived_table\">" + headFoot + "<tbody id=\"archived_body\">";

                var filters = [
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.select_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.input_filter,
                    DoleticMasterInterface.reset_filter
                ];

                for (var i = 0; i < data.object.length; i++) {
                    var row = "<tr>" +
                        "<td><button class=\"ui button\" data-tooltip=\"Détails de l'étude " + data.object[i].number + "\">" + data.object[i].number + "</button></td>" +
                        "<td>" + data.object[i].name + "</td>" +
                        "<td>" + data.object[i].field + "</td>" +
                        "<td>" + data.object[i].firm_id + "</td>" +
                        "<td>" + data.object[i].creation_date + "</td>" +
                        "<td>" + data.object[i].chadaff_id + "</td>" + // Change this
                        "<td>" + data.object[i].int_id + "</td>" + // Change this
                        "<td>" + data.object[i].auditor_id + "</td>"; // Change this

                    if (data.object[i].disabled) {
                        disabled_content += row + "<td><div class=\"ui icon buttons\">" +
                            "<button class=\"ui icon button\" data-tooltip=\"Modifier\" onClick=\"DoleticUIModule.editProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"write icon\"></i>" +
                            "</button>" +
                            "<button class=\"ui icon button\" data-tooltip=\"Réactiver\" onClick=\"DoleticUIModule.restoreProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"remove icon\"></i>" +
                            "</button>" +
                            "</div>" +
                            "</tr>";
                    } else if (data.object[i].archived) {
                        archived_content += row + "<td><div class=\"ui icon buttons\">" +
                            "<button class=\"ui icon button\" data-tooltip=\"Restaurer\" onClick=\"DoleticUIModule.unarchiveProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"write icon\"></i>" +
                            "</button>" +
                            "</div>" +
                            "</tr>";
                    } else if (data.object[i].sign_date == null) {
                        sollic_content += row + "<td><div class=\"ui icon buttons\">" +
                            "<button class=\"ui icon button\" data-tooltip=\"Modifier\" onClick=\"DoleticUIModule.editProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"write icon\"></i>" +
                            "</button>" +
                            "<button class=\"ui icon button\" data-tooltip=\"Désactiver\" onClick=\"DoleticUIModule.disableProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"remove icon\"></i>" +
                            "</button>" +
                            "</div>" +
                            "</tr>";
                    } else {
                        project_content += row + "<td><div class=\"ui icon buttons\">" +
                            "<button class=\"ui icon button\" data-tooltip=\"Modifier\" onClick=\"DoleticUIModule.editProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"write icon\"></i>" +
                            "</button>" +
                            "<button class=\"ui icon button\" data-tooltip=\"Désactiver\" onClick=\"DoleticUIModule.disableProject(" + data.object[i].number + "); return false;\">" +
                            "<i class=\"remove icon\"></i>" +
                            "</button>" +
                            "</div>" +
                            "</tr>";
                    }
                }
                sollic_content += "</tbody></table>";
                project_content += "</tbody></table>";
                disabled_content += "</tbody></table>";
                archived_content += "</tbody></table>";
                $('#sollic_table_container').append(sollic_content);
                $('#project_table_container').append(project_content);
                $('#disabled_table_container').append(disabled_content);
                $('#archived_table_container').append(archived_content);
                DoleticMasterInterface.makeDataTables('sollic_table', filters);
                DoleticMasterInterface.makeDataTables('project_table', filters);
                DoleticMasterInterface.makeDataTables('disabled_table', filters);
                DoleticMasterInterface.makeDataTables('archived_table', filters);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.drawGraphs = function () {
        IndicatorServicesInterface.processAllGraphByModule('ua', function (data) {
            DoleticMasterInterface.drawGraphs(data.object, 'graphs');
        });
    };

    this.fillValueIndicators = function () {
        IndicatorServicesInterface.processAllValueByModule('ua', function (data) {
            DoleticMasterInterface.fillValueIndicators(data.object, 'indicators_body');
        });
    };

    this.fillTableIndicators = function () {
        IndicatorServicesInterface.processAllTableByModule('ua', function (data) {
            DoleticMasterInterface.fillTableIndicators(data.object, 'tables');
        });
    };


    this.fillProjectDetails = function (number) {
        // activate items in tabs
        $('.menu .item').tab();
        $('.dropdown').dropdown();

        UaDBServicesInterface.getFullProjectByNumber(number, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                $('#det').show();
                var htmlTitle = "Détails de l'étude " + data.object.number;
                if (data.object.disabled) {
                    htmlTitle += " (désactivé)";
                }
                $('#det').html(htmlTitle);
                $('#det').click();
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
        window.currentDetails = number;
    };

    this.clearNewProjectForm = function () {
        $('#user_form')[0].reset();
        $('#user_form h4').html("Ajout d'un membre");
        $('#firstname').prop('readonly', false);
        $('#lastname').prop('readonly', false);
        $('#old_position').addClass('disabled');
        $('#user_form .dropdown').dropdown('restore defaults');

        $('#adduser_btn').html("Ajouter");
        $('#adduser_btn').attr("onClick", "DoleticUIModule.insertNewUser(); return false;");
    };

    this.clearNewTeamForm = function () {
        $('#team_form')[0].reset();
        $('#team_form h4').html("Ajout d'une équipe");
        $('#team_form .dropdown').dropdown('restore defaults');
        $('#addteam_btn').html("Ajouter");
        $('#addteam_btn').attr("onClick", "DoleticUIModule.insertNewTeam(); return false;");
    };

    this.clearNewAdmMembershipForm = function (userId) {
        $('#admm_form')[0].reset();
        $('#admm_form h4').html("Ajout d'une adhésion");
        $('#admm_form .dropdown').dropdown('restore defaults');
        $('#admm_btn').html("Ajouter");
        $('#admm_btn').attr("onClick", "DoleticUIModule.insertNewAdmMembership(" + userId + "); return false;");
    };

    this.clearNewIntMembershipForm = function (userId) {
        $('#intm_form')[0].reset();
        $('#intm_form h4').html("Ajout d'une adhésion");
        $('#intm_form .dropdown').dropdown('restore defaults');
        $('#intm_btn').html("Ajouter");
        $('#intm_btn').attr("onClick", "DoleticUIModule.insertNewIntMembership(" + userId + "); return false;");
    };

    this.clearNewAGRForm = function () {
        $('#agr_date').val("");
        $('#agr_pr').val("");
    };

    this.insertNewUser = function () {
        // ADD OTHER TESTS
        if (DoleticUIModule.checkNewUserForm()) {
            // generate credentials according to db
            UserServicesInterface.generateCredentials($('#firstname').val().trim(), $('#lastname').val().trim(), function (data) {
                // Insert new user in db
                UserServicesInterface.insert(data.object.username, data.object.pass, function (data) {
                    // Insert user data in db SELECT ?
                    UserDataServicesInterface.insert(data.object,
                        $('#gender_search').dropdown('get value'),
                        $('#firstname').val(),
                        $('#lastname').val(),
                        $('#birthdate').val(),
                        $('#tel').val(),
                        $('#mail').val(),
                        $('#address').val(),
                        $('#city').val(),
                        $('#postalcode').val(),
                        $('#country_search').dropdown('get value'),
                        $('#schoolyear_search').dropdown('get value'),
                        $('#dept_search').dropdown('get value'),
                        $('#position_search').dropdown('get value'),
                        $('#ag_search').dropdown('get value'),
                        DoleticUIModule.addUserHandler);
                });
            });
        }
    };

    this.insertNewTeam = function () {
        if (DoleticUIModule.checkNewTeamForm()) {
            // retreive missing information
            TeamServicesInterface.insert(
                $('#tname').val(),
                $('#leader_search').dropdown('get value'),
                $('#division_search').dropdown('get value'),
                DoleticUIModule.addTeamHandler
            );
        }
    };

    this.insertNewAdmMembership = function (userId) {
        if (DoleticUIModule.checkNewAdmMembershipForm()) {
            // retrieve missing information
            var options = document.getElementById("docs_adm").options;
            AdmMembershipServicesInterface.insert(
                userId, // Retenir l'utilisateur concerné
                $('#sdatea').val(),
                $('#edate').val(),
                Boolean(options[0].selected),
                Boolean(options[1].selected),
                Boolean(options[2].selected),
                function (data) {
                    DoleticUIModule.addAdmMembershipHandler(userId, data);
                });
        }
    };

    this.insertNewIntMembership = function (userId) {
        if (DoleticUIModule.checkNewIntMembershipForm()) {
            // retrieve missing information
            var options = document.getElementById("docs_int").options;
            IntMembershipServicesInterface.insert(
                userId,
                $('#sdatei').val(),
                Boolean(options[0].selected),
                Boolean(options[1].selected),
                Boolean(options[2].selected),
                Boolean(options[3].selected),
                Boolean(options[4].selected),
                $('#secu_int').val(),
                function (data) {
                    DoleticUIModule.addIntMembershipHandler(userId, data);
                });
        }
    };

    this.insertNewAGR = function () {
        if (DoleticUIModule.checkNewAGRForm()) {
            var ag = $("#agr_date").val();
            var pr = $("#agr_pr").val();
            UserDataServicesInterface.insertAg(ag, pr, function (data) {
                // if no service error
                if (data.code == 0) {
                    DoleticUIModule.clearNewAGRForm();
                    // alert user that creation is a success
                    DoleticMasterInterface.showSuccess("Création réussie !", "L'AGR a été ajoutée avec succès !");
                    // fill AGR list
                    DoleticUIModule.fillAGSelector();
                    DoleticUIModule.cancelNewAGRForm();
                } else {
                    // use default service service error handler
                    DoleticServicesInterface.handleServiceError(data);
                }
            });
        }
    };

    this.insertTeamMember = function (id) {
        var options = $('#add_tmember_select' + id).dropdown('get value').split(',');
        TeamServicesInterface.insertMember(id, options, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticUIModule.updateTeamModal(id);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.editUser = function (id, user_id) {
        $('#user_form h4').html("Edition d'un membre");
        UserDataServicesInterface.getById(id, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                $('#old_position').removeClass('disabled');
                $('#firstname').val(data.object.firstname);
                $('#firstname').prop('readonly', true);
                $('#lastname').val(data.object.lastname);
                $('#lastname').prop('readonly', true);
                $('#birthdate').val(data.object.birthdate);
                $('#city').val(data.object.city);
                $('#address').val(data.object.address);
                $('#postalcode').val(data.object.postal_code);
                $('#tel').val(data.object.tel);
                $('#mail').val(data.object.email);
                $('#schoolyear_search').dropdown("set selected", data.object.school_year);
                $('#dept_search').dropdown("set selected", data.object.insa_dept);
                $('#gender_search').dropdown("set selected", data.object.gender);
                $('#position_search').dropdown("set selected", data.object.position[0].label);
                $('#country_search').dropdown("set selected", data.object.country);
                $('#ag_search').dropdown("set selected", data.object.ag);
                $('#adduser_btn').html("Confirmer");
                $('#adduser_btn').attr("onClick", "DoleticUIModule.updateUser(" + id + ", " + user_id + "); return false;");
                $('#user_form_modal').modal('show');
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.editTeam = function (id) {
        $('#team_form h4').html("Edition d'une équipe");
        TeamServicesInterface.getById(id, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                $('#tname').val(data.object.name);
                $('#division').dropdown("set selected", data.object.division);
                $('#leader').dropdown("set selected", data.object.leader_id);
                $('#addteam_btn').html("Confirmer");
                $('#addteam_btn').attr("onClick", "DoleticUIModule.updateTeam(" + id + "); return false;");
                $('#team_form_modal').modal('show');
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.editAdmMembership = function (id, userId) {
        $('#admm_form_modal').modal('show');
        $('#admm_form h4').html("Edition d'une adhésion");
        AdmMembershipServicesInterface.getById(id, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                $('#sdatea').val(data.object.start_date);
                $('#edate').val(data.object.end_date);
                $('#ag').dropdown("set selected", Number(data.object.ag) - 1);
                var options = [];
                if (data.object.fee == 1) {
                    options.push("0");
                }
                if (data.object.form == 1) {
                    options.push("1");
                }
                if (data.object.certif == 1) {
                    options.push("2");
                }
                $('#docs_adm').dropdown("set exactly", options);
                $('#admm_btn').html("Confirmer");
                $('#admm_btn').attr("onClick", "DoleticUIModule.updateAdmMembership(" + id + ", " + userId + "); return false;");
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.editIntMembership = function (id, userId) {
        $('#intm_form_modal').modal('show');
        $('#intm_form h4').html("Edition d'une adhésion");
        IntMembershipServicesInterface.getById(id, function (data) {
            // if no service error
            if (data.code == 0 && data.object != "[]") {
                $('#sdatei').val(data.object.start_date);
                var options = [];
                if (data.object.fee == 1) {
                    options.push("0");
                }
                if (data.object.form == 1) {
                    options.push("1");
                }
                if (data.object.certif == 1) {
                    options.push("2");
                }
                if (data.object.rib == 1) {
                    options.push("3");
                }
                if (data.object.identity == 1) {
                    options.push("4");
                }
                $('#secu_int').val(data.object.secu_number);
                $('#docs_int').dropdown("set exactly", options);
                $('#intm_btn').html("Confirmer");
                $('#intm_btn').attr("onClick", "DoleticUIModule.updateIntMembership(" + id + ", " + userId + "); return false;");
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.updateUser = function (id, user_id) {
        // ADD OTHER TESTS
        if (DoleticUIModule.checkNewUserForm()) {
            // Insert user data in db SELECT ?
            UserDataServicesInterface.update(id, user_id,
                $('#gender_search').dropdown('get value'),
                $('#firstname').val(),
                $('#lastname').val(),
                $('#birthdate').val(),
                $('#tel').val(),
                $('#mail').val(),
                $('#address').val(),
                $('#city').val(),
                $('#postalcode').val(),
                $('#country_search').dropdown('get value'),
                $('#schoolyear_search').dropdown('get value'),
                $('#dept_search').dropdown('get value'),
                $('#position_search').dropdown('get value'),
                $('#ag_search').dropdown('get value'),
                DoleticUIModule.editUserHandler
            );
        }
    };

    this.updateTeam = function (id) {
        // ADD OTHER TESTS
        if (DoleticUIModule.checkNewTeamForm()) {
            // Update team data in DB
            TeamServicesInterface.update(id,
                $('#tname').val(),
                $('#leader_search').dropdown('get value'),
                $('#division_search').dropdown('get value'),
                DoleticUIModule.editTeamHandler
            );
        }
    };

    this.updateAdmMembership = function (id, userId) {
        // ADD OTHER TESTS
        if (DoleticUIModule.checkNewAdmMembershipForm()) {
            // retrieve missing information
            var options = document.getElementById("docs_adm").options;
            AdmMembershipServicesInterface.update(id,
                userId, // Retenir l'utilisateur concerné
                $('#sdatea').val(),
                $('#edate').val(),
                Boolean(options[0].selected),
                Boolean(options[1].selected),
                Boolean(options[2].selected),
                function (data) {
                    DoleticUIModule.editAdmMembershipHandler(userId, data);
                }
            );
        }
    };

    this.updateIntMembership = function (id, userId) {
        // ADD OTHER TESTS
        if (DoleticUIModule.checkNewIntMembershipForm()) {
            // retrieve missing information
            var options = document.getElementById("docs_int").options;
            IntMembershipServicesInterface.update(id,
                userId, // Retenir l'utilisateur concerné
                $('#sdatei').val(),
                Boolean(options[0].selected),
                Boolean(options[1].selected),
                Boolean(options[2].selected),
                Boolean(options[3].selected),
                Boolean(options[4].selected),
                $('#secu_int').val(),
                function (data) {
                    DoleticUIModule.editIntMembershipHandler(userId, data);
                }
            );
        }
    };

    this.deleteUser = function (id, user_id) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove user icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir supprimer l'utilisateur ? Cette opération est irréversible.",
            function () {
                DoleticUIModule.deleteUserHandler(id, user_id);
            },
            DoleticMasterInterface.hideConfirmModal);
    };

    this.deleteTeam = function (id) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir supprimer l'équipe ? Cette opération est irréversible.",
            function () {
                DoleticUIModule.deleteTeamHandler(id);
            },
            DoleticMasterInterface.hideConfirmModal);
    };

    this.deleteAdmMembership = function (id, userId) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir supprimer l'adhésion ? Cette opération est irréversible.",
            function () {
                DoleticUIModule.deleteAdmMembershipHandler(id, userId);
            },
            DoleticMasterInterface.hideConfirmModal
        );
    };

    this.deleteIntMembership = function (id, userId) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir supprimer l'adhésion ? Cette opération est irréversible.",
            function () {
                DoleticUIModule.deleteIntMembershipHandler(id, userId);
            },
            DoleticMasterInterface.hideConfirmModal
        );
    };

    this.deleteAGR = function (ag) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la suppression", "\<i class=\"remove icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir supprimer l'AGR ? Cette opération est irréversible.",
            function () {
                DoleticUIModule.deleteAGRHandler(ag);
            },
            DoleticMasterInterface.hideConfirmModal
        );
    };

    this.deleteTeamMember = function (id, memberId) {
        TeamServicesInterface.deleteMember(id, memberId, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticUIModule.updateTeamModal(id);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.disableUser = function (id, user_id) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la désactivation", "\<i class=\"remove icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir désactiver cet utilisateur ? Il ou elle ne pourra plus se connecter à Doletic jusqu'à réactivation.",
            function () {
                DoleticUIModule.disableUserHandler(id, user_id);
            },
            DoleticMasterInterface.hideConfirmModal);

    };

    this.restoreUser = function (id, user_id) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer la réactivation", "\<i class=\"remove icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir réactiver cet utilisateur ? Il ou elle pourra de nouveau accéder à Doletic.",
            function () {
                DoleticUIModule.restoreUserHandler(id, user_id);
            },
            DoleticMasterInterface.hideConfirmModal);
    };

    this.tagOld = function (id) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer le marquage", "\<i class=\"student icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir marquer cet utilisateur ? Il ou elle ne sera plus comptabilisé(e) dans les statistiques.",
            function () {
                DoleticUIModule.tagOldUserHandler(id);
            },
            DoleticMasterInterface.hideConfirmModal);
    };

    this.untagOld = function (id) {
        // Confirmation
        DoleticMasterInterface.showConfirmModal("Confirmer le marquage", "\<i class=\"add user icon\"\>\<\/i\>",
            "Etes-vous sûr de vouloir marquer cet utilisateur ? Il ou elle sera de nouveau comptabilisé(e) dans les statistiques.",
            function () {
                DoleticUIModule.untagOldUserHandler(id);
            },
            DoleticMasterInterface.hideConfirmModal);
    };

    this.updateTeamModal = function (id) {
        $("#add_tmember_select" + id).dropdown('restore defaults');
        TeamServicesInterface.getTeamMembers(id, function (data) {
            window.team_list[id].members = data.object;
            var html = "";
            for (var i = 0; i < data.object.length; i++) {
                html += "		<tr><td> \
							  <i class=\"large user middle aligned icon\"></i></td><td>\
							  <div class=\"content\">\
							    <div class=\"header\"><strong>" + window.user_list[data.object[i]].firstname + " "
                    + window.user_list[data.object[i]].lastname;
                html += "</strong></div><div class=\"description\">" + window.user_list[data.object[i]].position[0].label + "</div>\
							  </div>";

                if (data.object[i] != window.team_list[id].leader_id) {
                    html += "<td><button class=\"ui small icon button\"onClick=\"DoleticUIModule.deleteTeamMember(" + id + ", " + data.object[i] + "); return false;\"> \
		  									<i class=\"remove icon\"></i>Retirer \
								</button></td>";
                } else {
                    html += "<td> (Chef d'équipe) </td>";
                }

                html += "</td></tr>";
                $("#members_" + id).html(html);
            }
        });
    };

    this.showNewUserForm = function () {
        DoleticUIModule.clearNewUserForm();
        $('#user_form_modal').modal('show');
    };

    this.cancelNewUserForm = function () {
        DoleticUIModule.clearNewUserForm();
        $('#user_form_modal').modal('hide');
    };

    this.showNewTeamForm = function () {
        DoleticUIModule.clearNewTeamForm();
        $('#team_form_modal').modal('show');
    };

    this.cancelNewTeamForm = function () {
        DoleticUIModule.clearNewTeamForm();
        $('#team_form_modal').modal('hide');
    };

    this.showNewAdmMembershipForm = function (id) {
        DoleticUIModule.clearNewAdmMembershipForm(id);
        $('#admm_form_modal').modal('show');
    };

    this.cancelNewAdmMembershipForm = function (id) {
        DoleticUIModule.clearNewAdmMembershipForm(id);
        $('#admm_form_modal').modal('hide');
    };

    this.showNewIntMembershipForm = function (id) {
        DoleticUIModule.clearNewIntMembershipForm(id);
        $('#intm_form_modal').modal('show');
    };

    this.cancelNewIntMembershipForm = function (id) {
        DoleticUIModule.clearNewIntMembershipForm(id);
        $('#intm_form_modal').modal('hide');
    };

    this.showNewAGRForm = function () {
        DoleticUIModule.clearNewAGRForm();
        $('#agr_form_modal').modal('show');
    };

    this.cancelNewAGRForm = function () {
        DoleticUIModule.clearNewAGRForm();
        $('#agr_form_modal').modal('hide');
    };

    this.checkNewUserForm = function () {
        $('#user_form .field').removeClass("error");
        var valid = true;
        var errorString = "";
        if (!DoleticMasterInterface.checkName($('#firstname').val())) {
            $('#firstname_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkName($('#lastname').val())) {
            $('#lastname_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkDate($('#birthdate').val())) {
            $('#birthdate_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkTel($('#tel').val())) {
            $('#tel_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkMail($('#mail').val())) {
            $('#mail_field').addClass("error");
            valid = false;
        }
        if ($('#address').val() == "") {
            $('#address_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkName($('#city').val())) {
            $('#city_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkPostalCode($('#postalcode').val())) {
            $('#postalcode_field').addClass("error");
            valid = false;
        }
        if ($('#gender_search').dropdown('get value') == "") {
            $('#gender_field').addClass("error");
            valid = false;
        }
        if ($('#country_search').dropdown('get value') == "") {
            $('#country_field').addClass("error");
            valid = false;
        }
        if ($('#schoolyear_search').dropdown('get value') == "") {
            $('#schoolyear_field').addClass("error");
            valid = false;
        }
        if ($('#dept_search').dropdown('get value') == "") {
            $('#dept_field').addClass("error");
            valid = false;
        }
        if ($('#position_search').dropdown('get value') == "") {
            $('#position_field').addClass("error");
            valid = false;
        }
        if (!valid) {
            $('#user_form').transition('shake');
            DoleticMasterInterface.showError("Erreur !", "Merci de corriger les champs affichés en rouge.");
        }
        return valid;
    };

    this.checkNewTeamForm = function () {
        $('#team_form .field').removeClass("error");
        var valid = true;
        if ($('#tname').val() == "") {
            $('#tname_field').addClass("error");
            valid = false;
        }
        if ($('#leader_search').dropdown('get value') == "") {
            $('#leader_field').addClass("error");
            valid = false;
        }
        if ($('#division_search').dropdown('get value') == "") {
            $('#division_field').addClass("error");
            valid = false;
        }
        if (!valid) {
            $('#team_form').transition('shake');
            DoleticMasterInterface.showError("Erreur !", "Merci de corriger les champs affichés en rouge.");
        }
        return valid;
    };

    this.checkNewAdmMembershipForm = function () {
        $('#admm_form .field').removeClass("error");
        var valid = true;
        if (!DoleticMasterInterface.checkDate($('#sdatea').val())) {
            $('#sdatea_field').addClass("error");
            valid = false;
        }
        if (!DoleticMasterInterface.checkDate($('#edate').val())) {
            $('#edate_field').addClass("error");
            valid = false;
        }
        if (!valid) {
            $('#admm_form').transition('shake');
            DoleticMasterInterface.showError("Erreur !", "Merci de corriger les champs affichés en rouge.");
        }
        return valid;
    };

    this.checkNewIntMembershipForm = function () {
        $('#intm_form .field').removeClass("error");
        var valid = true;
        if (!DoleticMasterInterface.checkDate($('#sdatei').val())) {
            $('#sdatei_field').addClass("error");
            valid = false;
        }
        if (!/^\d{13}$/.test($('#secu_int').val().trim())) {
            $('#secu_int').addClass("error");
            valid = false;
        }
        if (!valid) {
            $('#intm_form').transition('shake');
            DoleticMasterInterface.showError("Erreur !", "Merci de corriger les champs affichés en rouge.");
        }
        return valid;
    };

    this.checkNewAGRForm = function () {
        var valid = true;
        if (!DoleticMasterInterface.checkDate($('#agr_date').val())) {
            $('#agr_date').addClass("error");
            valid = false;
        }
        if (!$('#agr_pr').val() < 0) {
            $('#agr_pr').addClass("error");
            valid = false;
        }
        return valid;
    };


    // --- HANDLERS
    this.addUserHandler = function (data) {
        // if no service error
        if (data.code == 0) {
            // clear ticket form
            DoleticUIModule.cancelNewUserForm();
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Création réussie !", "L'utilisateur a été créé avec succès !");
            // fill ticket list
            DoleticUIModule.fillUsersList();
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.editUserHandler = function (data) {
        // if no service error
        if (data.code == 0) {
            // clear ticket form
            DoleticUIModule.cancelNewUserForm();
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Edition réussie !", "L'utilisateur a été modifié avec succès !");
            // fill ticket list
            DoleticUIModule.fillUsersList();
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.disableUserHandler = function (id, userId) {
        UserServicesInterface.disable(userId, function (data) {
            // if no service error
            if (data.code == 0) {
                UserDataServicesInterface.disable(id, function (data) {
                    // if no service error
                    if (data.code == 0) {
                        DoleticMasterInterface.hideConfirmModal();
                        DoleticMasterInterface.showSuccess("Désactivation réussie !", "L'utilisateur a été désactivé.");
                        DoleticUIModule.fillUsersList();
                        if (window.currentDetails == userId) {
                            $('#det').html($('#det').html() + " (désactivé)");
                        }
                    } else {

                    }
                });

            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.restoreUserHandler = function (id, userId) {
        UserServicesInterface.restore(userId, function (data) {
            // if no service error
            if (data.code == 0) {
                UserDataServicesInterface.enable(id, function (data) {
                    // if no service error
                    if (data.code == 0) {
                        DoleticMasterInterface.hideConfirmModal();
                        DoleticMasterInterface.showSuccess("Réactivation réussie !", "L'utilisateur a été réactivé.");
                        DoleticUIModule.fillUsersList();
                        if (window.currentDetails == userId) {
                            $('#det').html($('#det').html().replace(" (désactivé)", ""));
                        }
                    } else {
                        // use default service service error handler
                        DoleticServicesInterface.handleServiceError(data);
                    }
                });
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.tagOldUserHandler = function (id) {
        UserDataServicesInterface.tagOld(id, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticMasterInterface.hideConfirmModal();
                DoleticMasterInterface.showSuccess("Opération réussi !", "L'utilisateur a été marqué comme ancien membre.");
                DoleticUIModule.fillUsersList();
            } else {

            }
        });
    };

    this.untagOldUserHandler = function (id) {
        UserDataServicesInterface.untagOld(id, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticMasterInterface.hideConfirmModal();
                DoleticMasterInterface.showSuccess("Opération réussie !", "L'utilisateur a été marqué comme membre actuel.");
                DoleticUIModule.fillUsersList();
            } else {

            }
        });
    };

    this.deleteUserHandler = function (id, userId) {
        UserDataServicesInterface.delete(id, userId, function (data) {
            // if no service error
            if (data.code == 0) {
                UserServicesInterface.delete(id, function (data) {
                    // if no service error
                    if (data.code == 0) {
                        DoleticMasterInterface.hideConfirmModal();
                        DoleticMasterInterface.showSuccess("Suppression réussie !", "L'utilisateur a été supprimé avec succès !");
                        DoleticUIModule.fillUsersList();
                        DoleticUIModule.fillTeamsList();
                        if (window.currentDetails = userId) {
                            $("#det").hide();
                        }
                    } else {
                        // use default service service error handler
                        DoleticServicesInterface.handleServiceError(data);
                    }
                });
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.addTeamHandler = function (data) {
        // if no service error
        if (data.code == 0) {
            // clear ticket form
            DoleticUIModule.cancelNewTeamForm();
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Création réussie !", "L'équipe a été créée avec succès !");
            // fill ticket list
            DoleticUIModule.fillTeamsList();
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.editTeamHandler = function (data) {
        // if no service error
        if (data.code == 0) {
            // clear ticket form
            DoleticUIModule.cancelNewTeamForm();
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Edition réussie !", "L'équipe a été modifiée avec succès !");
            // fill ticket list
            DoleticUIModule.fillTeamsList();
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.deleteTeamHandler = function (id) {
        TeamServicesInterface.delete(id, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticMasterInterface.hideConfirmModal();
                DoleticMasterInterface.showSuccess("Suppression réussie !", "L'équipe a été supprimée avec succès !");
                DoleticUIModule.fillTeamsList();
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.addAdmMembershipHandler = function (userId, data) {
        // if no service error
        if (data.code == 0) {
            DoleticUIModule.cancelNewAdmMembershipForm(userId);
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Création réussie !", "L'adhésion a été ajoutée avec succès !");
            // fill AdmMemberships list
            DoleticUIModule.fillAdmMemberships(userId);
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.editAdmMembershipHandler = function (userId, data) {
        // if no service error
        if (data.code == 0) {
            DoleticUIModule.cancelNewAdmMembershipForm(userId);
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Edition réussie !", "L'adhésion a été modifiée avec succès !");
            // fill AdmMemberships list
            DoleticUIModule.fillAdmMemberships(userId);
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.deleteAdmMembershipHandler = function (id, userId) {
        AdmMembershipServicesInterface.delete(id, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticMasterInterface.hideConfirmModal();
                DoleticMasterInterface.showSuccess("Suppression réussie !", "L'adhésion a été supprimée avec succès !");
                DoleticUIModule.fillAdmMemberships(userId);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.addIntMembershipHandler = function (userId, data) {
        // if no service error
        if (data.code == 0) {
            DoleticUIModule.cancelNewIntMembershipForm(userId);
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Création réussie !", "L'adhésion a été ajoutée avec succès !");
            // fill IntMemberships list
            DoleticUIModule.fillIntMemberships(userId);
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.editIntMembershipHandler = function (userId, data) {
        // if no service error
        if (data.code == 0) {
            DoleticUIModule.cancelNewIntMembershipForm(userId);
            // alert user that creation is a success
            DoleticMasterInterface.showSuccess("Edition réussie !", "L'adhésion a été modifiée avec succès !");
            // fill IntMemberships list
            DoleticUIModule.fillIntMemberships(userId);
        } else {
            // use default service service error handler
            DoleticServicesInterface.handleServiceError(data);
        }
    };

    this.deleteIntMembershipHandler = function (id, userId) {
        IntMembershipServicesInterface.delete(id, function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticMasterInterface.hideConfirmModal();
                DoleticMasterInterface.showSuccess("Suppression réussie !", "L'adhésion a été supprimée avec succès !");
                DoleticUIModule.fillIntMemberships(userId);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.deleteAGRHandler = function (ag) {
        UserDataServicesInterface.deleteAg(String(ag), function (data) {
            // if no service error
            if (data.code == 0) {
                DoleticMasterInterface.hideConfirmModal();
                DoleticMasterInterface.showSuccess("Suppression réussie !", "L'AGR a été supprimée avec succès !");
                DoleticUIModule.fillAGSelector();
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    }

};