debugger;

var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('AdminTool_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // activate items in tabs
        $('.menu .item').tab();
        // activate checkboxes
        $('.ui.checkbox').checkbox();
        // refresh mailing lists
        this.refreshMaillistList();
        // refresh doc templates lists
        this.refreshDocTemplateList();
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
							</div> \
					  	  </div> \
					  	  <div class=\"ui bottom attached blue button\" onClick=\"$('#upload_btn_doc').click();\">Ajouter un document modèle</div> \
					    </div> \
						<div class=\"ui bottom attached tab segment\" data-tab=\"mail\"> \
						  <div class=\"ui top attached segment\"> \
				  		  	<h3><i class=\"mail outline icon\"></i> Gestion des mailing listes</h3> \
				  		  </div> \
				  		  <div class=\"ui attached segment\"> \
				  			<div id=\"mailing_lists\" class=\"ui celled selection list\"> \
								<!-- MAILING LISTS WILL GO HERE --> \
						  	</div> \
				  		  </div> \
				  		  <div class=\"ui bottom attached blue button\" onClick=\"$('#maillist_modal').modal('show');\">Ajouter une mailing liste</div> \
				  		  <div class=\"ui top attached segment\"> \
				  		  	<h3>Détails <span id=\"details_title\"></span></h3> \
				  		  </div> \
				  		  <div class=\"ui attached segment\"> \
				  			<div id=\"mailing_details\" class=\"ui celled selection list\"> \
								<!-- SUBSCRIBERS ADRESSES WILL GO HERE --> \
						  	</div> \
				  		  </div> \
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
					      <input id=\"can_subscribe_input\" class=\"hidden\" tabindex=\"0\" type=\"checkbox\"> \
					      <label>Inscription autorisée</label> \
					    </div> \
					  </div> \
					</form> \
				  </div> \
				  <div class=\"actions\"> \
				    <div class=\"ui red deny button\" onClick=\"DoleticUIModule.resetMaillistForm();\">Annuler</div> \
				    <div class=\"ui positive right labeled icon button\" onClick=\"DoleticUIModule.addMaillist();\">Ajouter<i class=\"checkmark icon\"></i> \
				    </div> \
				  </div> \
				</div> \
				" + DoleticUIFactory.makeUploadForm('doc', true);
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data1) {
        if (id == 'doc') {
            // call service to retrieve upload
            UploadServicesInterface.getById(data1, function (data2) {
                if (data2.code == 0) {
                    // call service to register new document template
                    DocTemplateServicesInterface.insert(data2.object.id, data2.object.basename, data2.object.filetype, function (data3) {
                        if (data3.code == 0) {
                            // display success message
                            DoleticMasterInterface.showSuccess("Ajout réussi !", "L'ajout du document modèle est terminé !");
                            // update templates list
                            DoleticUIModule.refreshDocTemplateList();
                        } else {
                            // treat error case
                            DoleticServicesInterface.handleServiceError(data3);
                        }
                    });
                } else {
                    // treat error case
                    DoleticServicesInterface.handleServiceError(data2);
                }
            });
        } else {
            // treat error case
            this.super.uploadSuccessHandler(id, data1);
        }
    };

    this.nightMode = function (on) {
        if (on) {
            /// \todo implement here
        } else {
            /// \todo implement here
        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

// -------- mailing lists functions

    this.clearMaillistDetails = function () {
        $('#mailing_details').html('Aucun détail à afficher.');
        $('#details_title').html('');
    };

    this.refreshMaillistList = function () {
        // clear details
        this.clearMaillistDetails();
        // clear current list
        $('#mailing_lists').html('');
        // call service to retrieve lists
        MaillistServicesInterface.getAll(function (data) {
            if (data.code == 0) {
                if (data.object == "[]") {
                    $('#mailing_lists').append("<div class=\"item\">Aucune mailing liste à afficher.</div>");
                } else {
                    for (var i = data.object.length - 1; i >= 0; i--) {
                        DoleticUIModule.appendMaillistListRecord(
                            data.object[i].id,
                            data.object[i].name,
                            data.object[i].can_subscribe);
                    }
                }
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });

    };

    this.appendMaillistListRecord = function (id, name, canSubscribe) {
        var email = this.formatName(name) + DoleticConfig.JE.mail_domain;
        var tag_color = 'red';
        var tag_content = 'privée';
        if (canSubscribe) {
            tag_color = 'green';
            tag_content = 'ouverte';
        }
        var html_record = "<div class=\"item\"> \
								<div class=\"ui " + tag_color + " horizontal label\">" + tag_content + "</div> \
								" + name + " (<a href=\"mailto:" + email + "\">" + email + "</a>) \
								<div class=\"ui small basic icon right floated buttons\"> \
								  <button class=\"ui button\" onClick=\"DoleticUIModule.detailsMaillist(" + id + ",'" + name + "');\"><i class=\"table icon\"></i></button> \
								  <button class=\"ui button\" onClick=\"DoleticUIModule.editMaillist(" + id + ");\"><i class=\"write icon\"></i></button> \
								  <button class=\"ui red button\" onClick=\"DoleticUIModule.trashMaillist(" + id + ");\"><i class=\"red trash icon\"></i></button> \
								</div> \
							</div>";
        // append new record to list
        $('#mailing_lists').append(html_record);
    };

    this.resetMaillistForm = function () {
        $('#maillist_form')[0].reset();
        $('#mailing_mail').html('');
        $('#maillist_modal').modal('hide');
    };

    this.addMaillist = function () {
        // retrieve and check input
        var name = $('#name_input').val();
        var canSubscribe = $('#can_subscribe_input').prop('checked');
        // call service to add a mailing list
        MaillistServicesInterface.insert(name, canSubscribe, function (data) {
            if (data.code == 0) {
                DoleticMasterInterface.showSuccess("Création réussie !", "La mailing liste '" + name + "' a été créée avec succès.");
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
        // reset modal form
        this.resetMaillistForm();
        // refresh list
        this.refreshMaillistList();
    };

    this.detailsMaillist = function (id, name) {
        // clear details
        $('#mailing_details').html('');
        $('#details_title').html('des abonnés à la liste ' + name);
        // retrieve users
        MaillistServicesInterface.subscribed(id, function (data) {
            if (data.code == 0) {
                if (data.object == '[]') {
                    $('#mailing_details').html('Cette liste ne possède aucun abonné.');
                } else {
                    // for each id retreive user
                    for (var i = data.object.length - 1; i >= 0; i--) {
                        UserServicesInterface.getById(data.object[i], function (data) {
                            if (data.code == 0) {
                                var email = data.object.username + DoleticConfig.JE.mail_domain;
                                $('#mailing_details').append(
                                    '<div class="item"><a href="mailto:' + email + '\">' + email + '</a></div>');
                            } else {
                                DoleticServicesInterface.handleServiceError(data);
                            }
                        });
                    }
                }
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.editMaillist = function (id) {
        debugger;
        alert('Still working on this function...');
    };

    this.trashMaillist = function (id) {
        DoleticMasterInterface.showConfirmModal(
            'Suppresion ?', '<i class="help icon"></i>',
            '<p><b>Voulez-vous vraiment supprimer cette liste ?</b></p>',
            function () {
                // call service to add a mailing list
                MaillistServicesInterface.delete(id, function (data) {
                    if (data.code == 0) {
                        // show success message
                        DoleticMasterInterface.showSuccess("Suppresion réussie !", "La mailing liste a été supprimée.");
                        // refresh list
                        DoleticUIModule.refreshMaillistList();
                        // hide confirm modal
                        DoleticMasterInterface.hideConfirmModal();
                    } else {
                        DoleticServicesInterface.handleServiceError(data);
                    }
                });
            },
            function () {
                DoleticMasterInterface.hideConfirmModal();
            });
    };

    this.updateMaillistForm = function () {
        $('#mailing_mail').html(this.formatName($('#name_input').val()) + DoleticConfig.JE.mail_domain);
    };

    this.formatName = function (name) {
        return name.replace(' ', '-').toLowerCase();
    };

// -------- document templates functions

    this.fillDocumentTypes = function () {
        // clear
        $('#doc_types').html('');
        // fill it with available values
        DocTemplateServicesInterface.getDocumentTypes(function (data) {
            if (data.code == 0) {
                for (var i = data.object.length - 1; i >= 0; i--) {
                    $('#doc_types').append(
                        '<option value="' + data.object[i] + '">' + data.object[i] + '</option>');
                }
                // init document list
                DoleticUIModule.fillAvailableDocuments($('#doc_types').val());
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.refreshDocTemplateList = function () {
        // clear current list
        $('#doc_templates').html('');
        // call service to retrieve lists
        DocTemplateServicesInterface.getAll(function (data) {
            if (data.code == 0) {
                if (data.object == '[]') {
                    $('#doc_templates').append(
                        '<div class="item">Aucun modèle de document à afficher.</div>');
                } else {
                    for (var i = data.object.length - 1; i >= 0; i--) {
                        DoleticUIModule.appendDocTemplateListRecord(data.object[i]);
                    }
                }
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.appendDocTemplateListRecord = function (template) {
        var html_record = '<div class="item"> \
							  <div class="ui horizontal label">' + template.type + '</div> \
							  ' + template.name + ' \
							  <div class=\"ui small basic icon right floated buttons\"> \
								<button class=\"ui button\" onClick=\"DoleticServicesInterface.download(' + template.upload_id + ');\"><i class=\"download icon\"></i></button> \
								<button class=\"ui red button\" onClick=\"DoleticUIModule.editDocTemplate(' + template.id + ',' + template.upload_id + ');\"><i class=\"write icon\"></i></button> \
								<button class=\"ui red button\" onClick=\"DoleticUIModule.trashDocTemplate(' + template.id + ',' + template.upload_id + ');\"><i class=\"red trash icon\"></i></button> \
						      </div> \
							</div>';
        // append record to list
        $('#doc_templates').append(html_record);
    };

    this.editDocTemplate = function (id) {
        debugger;
        alert('Still working on this function...');
    };

    this.trashDocTemplate = function (id, uploadId) {
        debugger;
        alert('we are currently working on it...');
    }
};
