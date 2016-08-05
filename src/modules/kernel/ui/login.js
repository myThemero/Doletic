var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('Login_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"holder\"> \
				  <div class=\"ui three column centered middle aligned grid container\"> \
					<div class=\"column\"> \
					  <div class=\"ui attached message\"> \
						  <div class=\"header\"> \
						    Bienvenue ! \
						  </div> \
						  <p>Veuillez vous identifier.</p> \
						</div> \
					  <form id=\"login_form\" class=\"ui form attached fluid segment\"> \
				  		<div id=\"uname_field\" class=\"required field\"> \
					      <label>Nom d'utilisateur</label> \
					      <input id=\"uname_input\" placeholder=\"prenom.nom\" name=\"username\" type=\"text\"> \
						</div> \
						<div id=\"pass_field\" class=\"required field\"> \
					      <label>Mot de passe</label> \
					      <input id=\"pass_input\" placeholder=\"mot de passe\" name=\"password\" type=\"password\" onKeyPress=\"if(event.keyCode == 13) { DoleticUIModule.checkLoginFormInputs(); }\"> \
						</div> \
  						<div id=\"login_btn\" class=\"ui green button\" onClick=\"DoleticUIModule.checkLoginFormInputs();\">Connexion</div> \
  						<div id=\"reset_btn\" class=\"ui right floated button\" onClick=\"DoleticUIModule.resetLoginForm();\">Effacer</div> \
					  </form> \
					  <div class=\"ui bottom attached info message\"> \
  						<i class=\"icon help\"></i> \
  						<a class=\"ui\" onClick=\"DoleticServicesInterface.getUILost();\">Mot de passe perdu</a> \
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
        if (on) {
            $('#login_form').attr('class', 'ui form attached fluid segment inverted');
            $('#login_btn').attr('class', 'ui green button inverted');
            $('#reset_btn').attr('class', 'ui right floated button inverted');
        } else {
            $('#login_form').attr('class', 'ui form attached fluid segment');
            $('#login_btn').attr('class', 'ui green button');
            $('#reset_btn').attr('class', 'ui right floated button');
        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    this.hasInputError = false;
    /**
     *
     */
    this.checkLoginFormInputs = function () {
        if (/*$('#uname_input').val().length > 0 &&
            $('#pass_input').val().length > 0 &&
            $('#uname_input').val().match(/^[\w-]+\.[\w-]+$/g)*/ true) {
            if (this.hasInputError) {
                DoleticUIModule.resetLoginForm();
            }
            DoleticServicesInterface.authenticate(
                $('#uname_input').val(),
                $('#pass_input').val(),
                function (data) {
                    if (data.authenticated) {
                        // Call for home interface
                        DoleticServicesInterface.getUIHome();
                    } else {
                        DoleticUIModule.showLoginError();
                    }
                });
        } else {
            DoleticUIModule.showInputError();
        }
    };
    /**
     *
     */
    this.showLoginError = function () {
        // reset login form
        DoleticUIModule.resetLoginForm();
        // put form in error state
        $('#login_form').attr('class', 'ui form error');
        // display error message
        $('#pass_field').after("<div id=\"login_error\" class=\"ui error message\"> \
								   <div class=\"header\">Connexion refusée !</div> \
								   <p>Veuillez saisir vos identifiants de nouveau.</p> \
		   						 </div>");
    };
    /**
     *
     */
    this.showInputError = function () {
        if (!this.hasInputError) {
            // raise loginError flag
            this.hasInputError = true;
            // show input error elements
            $('#login_form').attr('class', 'ui form segment error');
            $('#uname_field').attr('class', 'field error');
            $('#uname_field').append("<div id=\"uname_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer votre nom d'utilisateur</div>");
            $('#pass_field').attr('class', 'field error');
            $('#pass_field').append("<div id=\"pass_error\" class=\"ui basic red pointing prompt label transition visible\">Veuillez entrer votre mot de passe</div>");
        }
    };
    /**
     *
     */
    this.resetLoginForm = function () {
        // lower inputError flag
        this.hasInputError = false;
        // clear fields
        $('#uname_input').val("");
        $('#pass_input').val("");
        // reset class attributes
        $('#login_form').attr('class', 'ui form attached fluid segment');
        $('#uname_field').attr('class', 'required field');
        $('#pass_field').attr('class', 'required field');
        // remove error elements
        $('#uname_error').remove();
        $('#pass_error').remove();
        $('#login_error').remove();
    }

};