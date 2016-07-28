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
        $('.dropdown').dropdown();
        // Load HTML templates
        DoleticUIModule.getContactsTab();
        DoleticUIModule.getCompaniesTab();
        DoleticUIModule.getStatsTab();
        // Fill the content list
        //DoleticUIModule.fillContactList();
        //DoleticUIModule.fillCompanyList();
        // Fill all the selectors
        DoleticUIModule.fillCategoriesSelector();
        DoleticUIModule.fillCompaniesSelector();
        DoleticUIModule.fillCountriesSelector();
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
        $('#contactsTab').load("../modules/grc/ui/templates/contactsTab.html");
    };

    /**
     *    Load the HTML code of the Companies Tab
     */
    this.getCompaniesTab = function () {
        $('#companiesTab').load("../modules/grc/ui/templates/companiesTab.html");
    };

    /**
     *    Load the HTML code of the Stats Tab
     */
    this.getStatsTab = function () {
        $('#statsTab').load("../modules/grc/ui/templates/statsTab.html");
    };

    /**
     *    Fill the Contact List
     */
    this.fillContactList = function () {
        /*ContactServicesInterface.getAll(function(data) {
         // if no service error
         if(data.code == 0 && data.object != "[]") {
         console.log("DATA GET ALL CONTACT :" + data.object);
         // create content var to build html
         var content = "";
         // iterate over values to build options
         for (var i = 0; i < data.object.length; i++) {
         content += "<tr><td>"+data.object[i].name+"</td> \
         <td>"+data.object[i].leader_id +"</td> \
         <td>" + data.object[i].division + "</td> \
         <td>TEMP</td> \
         <td> \
         <div class=\"ui icon buttons\"> \
         <button class=\"ui icon button\"> \
         <i class=\"write icon\"></i> \
         </button> \
         <button class=\"ui icon button\"> \
         <i class=\"remove user icon\"></i> \
         </button></td> \
         </div> \
         </tr>";
         };
         // insert html content
         $('#contact_body').html(content);
         } else {
         // use default service service error handler
         DoleticServicesInterface.handleServiceError(data);
         }
         });*/
    };

    /**
     *    Clear all the field from the Contact Form
     */
    this.clearNewContactForm = function () {
        $('#firstname').val('');
        $('#lastname').val('');
        $('#tel').val('');
        $('#mail').val('');
        // clear error
        if (this.hasInputError) {
            // disable has error
            this.hasInputError = false;
            // change input style
            $('#contact_form').attr('class', 'ui form segment');
            $('#firstname_field').attr('class', 'required field');
            $('#lastname_field').attr('class', 'required field');
            $('#tel_field').attr('class', 'required field');
            $('#email_field').attr('class', 'required field');
            // remove error elements
            $('#subject_error').remove();
            $('#data_error').remove();
        }
    };

    /**
     *    Add a new Contact
     */
    this.insertNewContact = function () {
        if (DoleticUIModule.checkNewContactForm()) {
            ContactServicesInterface.insert(
                null,
                $('#firstname').val(),
                $('#lastname').val(),
                null,
                $('#mail').val(),
                $('#tel').val(),
                $('#categories').val(),
                null,
                DoleticUIModule.sendHandler
            );
        } else {
            DoleticUIModule.showInputError();
        }
    };

    /**
     *    Fill the Company List
     */
    this.fillCompanyList = function () {

    };

    /**
     *    Clear all the field from the Company Form
     */
    this.clearNewCompanyForm = function () {
        $('#siret').val('');
        $('#firstname').val('');
        $('#lastname').val('');
        $('#type').val('');
        $('#adress').val('');
        $('#postalCode').val('');
        $('#city').val('');
        // clear error
        if (this.hasInputError) {
            // disable has error
            this.hasInputError = false;
            // change input style
            $('#contact_form').attr('class', 'ui form segment');
            $('#siret_field').attr('class', 'required field');
            $('#type_field').attr('class', 'required field');
            $('#adress_field').attr('class', 'required field');
            $('#postalCode_field').attr('class', 'required field');
            $('#city_field').attr('class', 'required field');
            // remove error elements
            $('#subject_error').remove();
            $('#data_error').remove();
        }
    };

    /**
     *    Add a new Company
     */
    this.insertNewCompany = function () {
        if (DoleticUIModule.checkNewCompanyForm()) {
            FirmServicesInterface.insert(
                $('#siret').val(),
                $('#lastname').val(),
                $('#adress').val(),
                $('#postalCode').val(),
                $('#city').val(),
                $('#country option:selected').text(),
                $('#type').val(),
                null,
                DoleticUIModule.sendHandler
            );
        } else {
            DoleticUIModule.showInputError();
        }
    };

    /**
     *    Fill the categories selector
     */
    this.fillCategoriesSelector = function () {

    };

    /**
     *    Add the companies selector
     */
    this.fillCompaniesSelector = function () {

    };

    /**
     *    Add the countries selector
     */
    this.fillCountriesSelector = function () {
        UserDataServicesInterface.getAllCountries(function (data) {
            // if no service error
            if (data.code == 0) {
                // create content var to build html
                var content = "";
                //DoleticUIModule.country_list = data.object;
                // iterate over values to build options
                for (var i = 0; i < data.object.length; i++) {
                    content += "<option value=\"" + (i + 1) + "\">" + data.object[i] + "</option>\n";
                }
                // insert html content
                $('#country').html(content);
            } else {
                // use default service service error handler
                DoleticServicesInterface.handleServiceError(data);
            }
        });
    };

    this.checkNewContactForm = function () {
        return true;
    };

    this.checkNewCompanyForm = function () {
        return true;
    }
};
