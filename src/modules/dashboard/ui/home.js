var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('Home_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // Retrieve user data

        DoleticUIModule.getDashboard(function() {
            DoleticUIModule.fillGenderSelector();
            DoleticUIModule.fillCountrySelector();
            DoleticUIModule.fillINSADeptSelector();
            DoleticUIModule.fillSchoolYearSelector();
            DoleticMasterInterface.makeDefaultCalendar('birthdate_calendar');
        });
        DoleticUIModule.fillUserData();
        DoleticUIModule.fillUserProjects();
        DoleticUIModule.refreshAvatar();
        window.postLoad();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"ui two column grid container\" id=\"dashboard_container\"></div>" + DoleticUIFactory.makeUploadForm('avatar', true);
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data) {
        // treat avatar upload success event
        if (id == 'avatar') {
            DoleticServicesInterface.updateAvatar(data, function (data) {
                if (data.code == 0) {
                    DoleticUIModule.refreshAvatar();
                } else {
                    DoleticServicesInterface.handleServiceError(data);
                }
            });
        } else {
            this.super.uploadSuccessHandler(id, data);
        }
    };

    this.nightMode = function (on) {
        if (on) {
            $('#login_form').attr('class', 'ui form segment inverted');
        } else {
            $('#login_form').attr('class', 'ui form segment');
        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    this.getDashboard = function(callback) {
        $('#dashboard_container').load("../modules/dashboard/ui/templates/dashboard.html", callback);
    };

    /**
     *
     */
    this.refreshAvatar = function () {
        DoleticServicesInterface.getAvatar(function (data) {
            if (data.code == 0) {
                $('#user_avatar').attr('src', data.object);
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };
    /**
     *
     */
    this.fillUserData = function () {
        DoleticServicesInterface.getCurrentUser(function (data) {
            if (data.code == 0) {
                // set last connection timestamp
                $('#form_header').html('Edition du profil de ' + data.object.username);
                $('#uname').val(data.object.username);
                var date = new Date(data.object.last_connect_timestamp);
                $('#lastco_timestamp').html(
                    DoleticUIFactory.padleft(String(date.getDate()), 2, '0') + '/' +
                    DoleticUIFactory.padleft(String(date.getMonth()), 2, '0') + '/' +
                    DoleticUIFactory.padleft(String(date.getFullYear()), 4, '0') + " à " +
                    DoleticUIFactory.padleft(String(date.getHours()), 2, '0') + ':' +
                    DoleticUIFactory.padleft(String(date.getMinutes()), 2, '0') + ':' +
                    DoleticUIFactory.padleft(String(date.getSeconds()), 2, '0'));
                // retrieve user data
                UserDataServicesInterface.getById(data.object.id, function (data) {
                    if (data.code == 0) {
                        // set user name
                        $('#user_fulname').html(data.object.firstname + ' ' + data.object.lastname);
                        $('#firstname').val(data.object.firstname);
                        $('#lastname').val(data.object.lastname);
                        $('#birthdate').val(data.object.birthdate);
                        $('#gender_search').dropdown('set selected', data.object.gender);
                        $('#schoolyear_search').dropdown('set selected', data.object.school_year);
                        $('#dept_search').dropdown('set selected', data.object.insa_dept);
                        $('#country').dropdown('set selected', data.object.country);
                        $('#mail').val(data.object.email);
                        $('#tel').val(data.object.tel);
                        $('#address').val(data.object.address);
                        $('#postalcode').val(data.object.postal_code);
                        $('#city').val(data.object.city);
                    } else {
                        // use doletic services interface to display error
                        DoleticServicesInterface.handleServiceError(data);
                    }
                });
                // retrieve user last position
                UserDataServicesInterface.getUserLastPos(data.object.id, function (data) {
                    if (data.code == 0) {
                        // set user name
                        $('#user_position').html(data.object.label);
                    } else {
                        // use doletic services interface to display error
                        DoleticServicesInterface.handleServiceError(data);
                    }
                });
            } else {
                // use doletic services interface to display error
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillUserProjects = function () {
        DoleticServicesInterface.getCurrentUser(function (data) {
            if (data.code == 0) {
                var userId = data.object.id;
                ProjectServicesInterface.getByChadaff(userId, function (data) {
                    if (data.code == 0) {
                        var filters = [
                            DoleticMasterInterface.input_filter,
                            DoleticMasterInterface.input_filter,
                            DoleticMasterInterface.select_filter,
                            DoleticMasterInterface.select_filter
                        ];
                        var content = '<table class="ui very basic celled table" id="project_table">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>Numéro</th>' +
                            '<th>Nom</th>' +
                            '<th>Rôle</th>' +
                            '<th>Statut</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tfoot>' +
                            '<tr>' +
                            '<th>Numéro</th>' +
                            '<th>Nom</th>' +
                            '<th>Rôle</th>' +
                            '<th>Statut</th>' +
                            '</tr>' +
                            '</tfoot>' +
                            '<tbody id="project_body">';
                        var click = $('#submenu_ua').attr('onclick');
                        for (var i = 0; i < data.object.length; i++) {
                            var onClick = click.substr(0, click.length-2) + ", 'DoleticUIModule.fillProjectDetails(" + data.object[i].number + ");');";
                            content += '<tr>' +
                                "<td><button onClick=\"" + onClick + "\" class=\"ui button\" data-tooltip=\"Détails de l'étude " + data.object[i].number + "\">" + data.object[i].number + "</button></td>" +
                                '<td>' + data.object[i].name + '</td>' +
                                '<td>Chargé d\'affaires</td>' +
                                '<td>' + data.object[i].status + '</td>' +
                                '</tr>';
                        }
                        ProjectServicesInterface.getByAuditor(userId, function (data) {
                            if (data.code == 0) {
                                var click = $('#submenu_ua').attr('onclick');
                                for (var i = 0; i < data.object.length; i++) {
                                    var onClick = click.substr(0, click.length-2) + ", 'DoleticUIModule.fillProjectDetails(" + data.object[i].number + ");');";
                                    content += '<tr>' +
                                        "<td><button onClick=\"" + onClick + "\" class=\"ui button\" data-tooltip=\"Détails de l'étude " + data.object[i].number + "\">" + data.object[i].number + "</button></td>" +
                                        '<td>' + data.object[i].name + '</td>' +
                                        '<td>Correspondant qualité</td>' +
                                        '<td>' + data.object[i].status + '</td>' +
                                        '</tr>';
                                }
                                ProjectServicesInterface.getByInt(userId, function (data) {
                                    if (data.code == 0) {
                                        var click = $('#submenu_ua').attr('onclick');
                                        for (var i = 0; i < data.object.length; i++) {
                                            var onClick = click.substr(0, click.length-2) + ", 'DoleticUIModule.fillProjectDetails(" + data.object[i].number + ");');";
                                            content += '<tr>' +
                                                "<td><button onClick=\"" + onClick + "\" class=\"ui button\" data-tooltip=\"Détails de l'étude " + data.object[i].number + "\">" + data.object[i].number + "</button></td>" +
                                                '<td>' + data.object[i].name + '</td>' +
                                                '<td>Consultant</td>' +
                                                '<td>' + data.object[i].status + '</td>' +
                                                '</tr>';
                                        }
                                        content += '</tbody></table>';
                                        $('#project_table_container').html(content);
                                        DoleticMasterInterface.makeDataTables('project_table', filters);
                                    } else {
                                        // use doletic services interface to display error
                                        DoleticServicesInterface.handleServiceError(data);
                                    }
                                });
                            } else {
                                // use doletic services interface to display error
                                DoleticServicesInterface.handleServiceError(data);
                            }
                        });
                    } else {
                        // use doletic services interface to display error
                        DoleticServicesInterface.handleServiceError(data);
                    }
                });
            } else {
                // use doletic services interface to display error
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.showEditProfileForm = function() {
        $('#profile_form_modal').modal('show');
    };

    this.cancelProfileForm = function() {
        $('#profile_form_modal').modal('hide');
    };

    this.showEditPassForm = function() {
        $('#pass_form_modal').modal('show');
    };

    this.cancelPassForm = function() {
        $('#oldpass, #newpass, #confirm').val('');
        $('#pass_form_modal').modal('hide');
    };

    this.updateProfile = function() {
        if(DoleticUIModule.checkProfileForm()) {
            UserDataServicesInterface.updateOwn(
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
                function(data) {
                    if (data.code == 0) {
                        DoleticUIModule.cancelProfileForm();
                        DoleticMasterInterface.showSuccess('Succès !', 'Votre profil a été modifié avec succès !');
                        DoleticUIModule.fillUserData();
                    } else {
                        // use default service service error handler
                        DoleticServicesInterface.handleServiceError(data);
                    }

                }
            );
        }
    };

    this.updatePassword = function() {
        if(DoleticUIModule.checkPassForm()) {
            DoleticServicesInterface.updatePassword(
                $('#uname').val(),
                $('#oldpass').val(),
                $('#newpass').val(),
                function(data) {
                    if (data.code == 0) {
                        DoleticUIModule.cancelPassForm();
                        DoleticMasterInterface.showSuccess('Succès !', 'Mot de passe mis à jour avec succès !');
                    } else {
                        // use default service service error handler
                        DoleticServicesInterface.handleServiceError(data);
                    }
                }
            );
        }
    };

    this.checkPassForm = function () {
        $('#pass_form .field').removeClass("error");
        var valid = true;
        if ($('#newpass').val() != $('#confirm').val()) {
            $('#newpass_field').addClass("error");
            $('#confirm_field').addClass("error");
            valid = false;
            $('#pass_form').transition('shake');
        }
        return valid;
    };

    this.checkProfileForm = function () {
        $('#profile_form .field').removeClass("error");
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
        if (!valid) {
            $('#profile_form').transition('shake');
            DoleticMasterInterface.showError("Erreur !", "Merci de corriger les champs affichés en rouge.");
        }
        return valid;
    };

    this.fillCountrySelector = function () {
        UserDataServicesInterface.getAllCountries(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = '';
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
};