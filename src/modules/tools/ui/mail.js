var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('Mail_UIModule', 'Paul Dautry', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
		// enable special popup
		$('#copy_btn').popup({popup:'#copy_popup'});
		// fill category field
		DoleticUIModule.generateSignature();
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
						<div id=\"title_seg\" class=\"html ui top attached segment\"> \
						   	<div class=\"ui top attached label\"><b>Votre signature de mail</b></div> \
						</div> \
						<div id=\"content_seg\" class=\"ui instructive attached segment\"> \
							<p><i id=\"copy_btn\" class=\"copy circular link icon\"></i> <b>Code HTML :</b></p> \
							<div id=\"copy_popup\" class=\"ui special small popup\"> \
								<div class=\"header\">Copier la signature :</div> \
								<div class=\"content\"> \
									<ol class=\"ui list\"> \
										<li>Cliquez dans la zone de texte</li> \
										<li>Pressez Ctrl+A</li> \
										<li>Pressez Ctrl+C</li> \
									<ol> \
								</div> \
						  	</div> \
							<form class=\"ui form\"> \
								<textarea id=\"code_zone\" readonly=\"\"> \
									<!-- HTML SIGNATURE CODE WILL GO THERE --> \
								</textarea> \
							</form> \
						</div> \
						<div id=\"footer_seg\" class=\"ui instructive bottom attached segment\"> \
							<p><b>Prévisualisation de votre signature :</b></p> \
							<div id=\"preview_zone\" class=\"\"> \
								<!-- HTML SIGNATURE WILL GO THERE --> \
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
	    	$('#title_seg').attr('class', 'html ui top attached segment inverted');
	    	$('#content_seg').attr('class', 'ui instructive attached segment inverted');
	    	$('#footer_seg').attr('class', 'ui instructive bottom attached segment inverted');
	    } else {
	    	$('#title_seg').attr('class', 'html ui top attached segment');
	    	$('#content_seg').attr('class', 'ui instructive attached segment');
	    	$('#footer_seg').attr('class', 'ui instructive bottom attached segment');
	    }
  	}

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

	this.generateSignature = function() { 
		// create html signature content
		var html = " \
<table style=\"color: #000000; font-size: medium;\">\n \
  <tbody>\n \
    <tr>\n \
      <td style=\"padding-right: 15px;\">\n \
        <a href=\""+DoleticConfig.JE.website_url+"\" target=\"_blank\">\n \
          <img src=\""+DoleticConfig.JE.logo_url+"\" alt=\"\" width=\"97\" height=\"76\" />\n \
        </a>\n \
      </td>\n \
      <td style=\"font-size: 13px; line-height: 14px; font-family: Helvetica,sans-serif;\">\n \
        <p style=\"margin: 8px  0px;\">\n \
            <span style=\"display: inline-block; margin-top: 12px; color: #006600; font-size: medium;\">\n \
	        <span id=\"user_lastname\"></span>&nbsp;<span id=\"user_firstname\"></span>\n \
          </span>\n \
          <br />\n \
          <span style=\"display: inline-block; margin-top: 2px; color: #006600; font-size: medium;\">\n \
            <strong><span id=\"user_position\"></span></strong>\n \
          </span>\n \
          <br />\n \
          <span style=\"display: inline-block; margin-top: 2px; color: #888888;\">\n \
            <a><span id=\"user_phone\"></span></a>&nbsp;|&nbsp;<a id=\"mail_lnk\" href=\"mailto:\" target=\"_blank\"><span id=\"user_mail\"></span></a>\n \
          </span>\n \
          <br />\n \
          <span style=\"display: inline-block; margin-top: 2px; color: #888888;\">\n \
            <span>"+DoleticConfig.JE.school+"&nbsp;-&nbsp;Département&nbsp;<span id=\"user_departement\"></span>&nbsp;-&nbsp;<span id=\"user_year\"></span>&nbsp;année</span>\n \
          </span>\n \
        </p>\n \
      </td>\n \
    </tr>\n \
  </tbody>\n \
</table>\n";
		// put html signature into preview zone
		$('#preview_zone').html(html);
		// fill signature fields using user data
		DoleticServicesInterface.getCurrentUser(function(data) {
			if(data.code == 0) {
				// set mail
				var mail = data.object.username+DoleticConfig.JE.mail_domain;
				$('#mail_lnk').attr('href', 'mailto:'+mail);
				$('#user_mail').html(mail);
				// retrieve user data
				UserDataServicesInterface.getById(data.object.id, function(data){
					if(data.code == 0) {
						// set firstname, lastname, phone and year
						$('#user_firstname').html(data.object.firstname);
						$('#user_lastname').html(data.object.lastname);
						$('#user_phone').html(data.object.tel);
						$('#user_year').html(data.object.school_year);
						// retrieve departement label
						UserDataServicesInterface.getINSADeptById(data.object.insa_dept_id , function(data){
							if(data.code == 0) {
								$('#user_departement').html(data.object.detail);
								// update signature source code when finished
								DoleticUIModule.updateSignatureSourceCode(); // <!> necessary because asynchronously called 
							} else {
								// use doletic services interface to display error
								DoleticServicesInterface.handleServiceError(data);
							}
						});
						// update signature source code when finished
						DoleticUIModule.updateSignatureSourceCode(); // <!> necessary because asynchronously called 
					} else {
						// use doletic services interface to display error
						DoleticServicesInterface.handleServiceError(data);		
					}
				});
				// retrieve user last position
				UserDataServicesInterface.getUserLastPos(data.object.id, function(data){
					if(data.code == 0) {
						// set user name
						$('#user_position').html(data.object.label);
						// update signature source code when finished
						DoleticUIModule.updateSignatureSourceCode(); // <!> necessary because asynchronously called 
					} else {
						// use doletic services interface to display error
						DoleticServicesInterface.handleServiceError(data);		
					}
				});
				// update signature source code when finished
				DoleticUIModule.updateSignatureSourceCode();
			} else {
				// update signature source code when finished
				DoleticUIModule.updateSignatureSourceCode(); // <!> necessary because asynchronously called 
			}
		});
	}

	this.updateSignatureSourceCode = function() {
		$('#code_zone').html($('#preview_zone').html());
	}

}
