var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('GRC_UIModule', 'Olivier Vicente', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
        // activate items in tabs
		$('.menu .item').tab();
		// Load HTML templates
		DoleticUIModule.getContactsTab();
		DoleticUIModule.getCompaniesTab();
		DoleticUIModule.getStatsTab();
		// Fill all the selectors
		DoleticUIModule.fillCategoriesSelector();
		DoleticUIModule.fillCompaniesSelector();
		DoleticUIModule.fillCountriesSelector();
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
 	}
	/**
	 *	Override uploadSuccessHandler
	 */
	this.uploadSuccessHandler = function(id, data) {
		this.super.uploadSuccessHandler(id, data);
	}

	this.nightMode = function(on) {
	    if(on) {

	    } else {

	    }
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

	/**
	 *	Load the HTML code of the Contacts Tab
	 */
	this.getContactsTab = function() {
		$('#contactsTab').load("../modules/grc/ui/templates/contactsTab.html");
	}

	/**
	 *	Load the HTML code of the Companies Tab
	 */
	this.getCompaniesTab = function() {
		$('#companiesTab').load("../modules/grc/ui/templates/companiesTab.html");
	}

	/**
	 *	Load the HTML code of the Stats Tab
	 */
	 this.getStatsTab = function() {
		 $('#statsTab').load("../modules/grc/ui/templates/statsTab.html");
	 }

	 /**
 	 *	Clear all the field from the Contact Form
 	 */
	 this.clearNewContactForm = function() {
 		$('#firstname').val('');
 		$('#lastname').val('');
 		$('#tel').val('');
 		$('#mail').val('');
 		// clear error
 		if(this.hasInputError) {
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
 	}

	/**
	*	Add a new Contact
	*/
	this.insertNewContact = function() {

	}

	/**
	*	Clear all the field from the Company Form
	*/
	this.clearNewCompanyForm = function() {
	   $('#siret').val('');
	   $('#lastname').val('');
	   $('#type').val('');
	   $('#adress').val('');
	   $('#postalCode').val('');
	   $('#city').val('');
	   // clear error
	   if(this.hasInputError) {
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
   }

	/**
	*	Add a new Company
	*/
	this.insertNewCompany = function() {

	}

	/**
	*	Fill the categories selector
	*/
	this.fillCategoriesSelector = function() {

	}

	/**
	*	Add the companies selector
	*/
	this.fillCompaniesSelector = function() {

	}

	/**
	*	Add the countries selector
	*/
	this.fillCountriesSelector = function() {

	}
}
