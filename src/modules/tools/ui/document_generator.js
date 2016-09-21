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
        // fill document types
        this.fillDocumentTypes();
        // fill study identifiers
        this.fillStudyIdentifiers();
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
				  	<div class=\"six centered wide column\"> \
				  		<div class=\"ui segment\"> \
					  		<div class=\"ui horizontal divider\"><i class=\"file outline icon\"></i> Format du documents</div> \
							<div class=\"ui form\"> \
 							  <div class=\"field\"> \
							    <select id=\"doc_types\" onChange=\"DoleticUIModule.fillAvailableDocuments($(this).val());\"> \
							      <!-- OPTIONS (DOCUMENT TYPES) WILL GO HERE --> \
							    </select> \
							  </div> \
                            </div> \
					  		<div class=\"ui horizontal divider\"><i class=\"file text outline icon\"></i> Choix des documents</div> \
				  			<div id=\"available_docs\" class=\"ui list\"> \
  								<!-- AVAILABLE DOCUMENTS WILL BE ADDED HERE --> \
							</div> \
							<div class=\"ui horizontal divider\"><i class=\"suitcase icon\"></i> Choix de l'étude</div> \
							<div class=\"ui form\"> \
 							  <div class=\"field\"> \
							    <select id=\"study_number\"> \
							      <!-- OPTIONS (STUDY IDENTIFIERS) WILL GO HERE --> \
							    </select> \
							  </div> \
                            </div> \
							<div class=\"ui horizontal divider\"><i class=\"download icon\"></i> Téléchargement</div> \
							<button class=\"fluid ui button\">Télécharger les documents</button> \
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
            /// \todo implement here
        } else {
            /// \todo implement here
        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    this.fillDocumentTypes = function () {
        // clear
        $('#doc_types').html('');
        // fill it with available values
        DocTemplateServicesInterface.getDocumentTypes(function (data) {
            if (data.code == 0) {
                for (var i = data.object.length - 1; i >= 0; i--) {
                    $('#doc_types').append('<option value="' + data.object[i] + '">' + data.object[i] + '</option>');
                }
                // init document list
                DoleticUIModule.fillAvailableDocuments($('#doc_types').val());
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.fillStudyIdentifiers = function () {
        // clear
        $('#study_number').html('');
        // fill it with study numbers
        /// \todo implement here
        alert('Still working on it... module study must be developped');
    };

    this.fillAvailableDocuments = function (type) {
        // clear
        $('#available_docs').html('');
        // call service to retrieve files
        DocTemplateServicesInterface.getDocumentsByType(type, function (data) {
            if (data.code == 0) {
                if (data.object == '[]') {
                    $('#available_docs').html('<div class=\"item\">Aucun document pour ce format.</div>');
                } else {
                    for (var i = data.object.length - 1; i >= 0; i--) {
                        // create html list item
                        var html = '<div class="item"> \
  									  <div class="ui checkbox"> \
  										<input type="checkbox" name="' + data.object[i].id + '"> \
  										<label>' + data.object[i].name + '</label> \
  									  </div> \
  								    </div>';
                        // append document to list
                        $('#available_docs').append(html);
                    }
                }
            } else {
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    }

};
