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
        this.fillUserData();
        this.refreshAvatar();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"ui two column grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"four wide column\"> \
					<div class=\"ui card\"> \
					  <div class=\"image\"> \
					    <a class=\"ui medium image\" onClick=\"$('#upload_btn_avatar').click();\"> \
  						  <img id=\"user_avatar\" src=\"/resources/image.png\"> \
						</a> \
					  </div> \
					  <div class=\"content\"> \
					    <a id=\"user_fulname\" class=\"header\">John Doe</a> \
					    <div class=\"meta\"> \
					      <span class=\"date\">Dernière connexion le <span id=\"lastco_timestamp\">13-02-2015 à 12:00</span></span> \
					    </div> \
					    <div id=\"user_position\" class=\"description\">Responsable DSI</div> \
					  </div> \
					  <div class=\"extra content\"> \
					    <a> \
					      <i class=\"user icon\"></i> \
					      Modifier mon profil \
					    </a> \
					  </div> \
					</div> \
					</div> \
					<div class=\"height wide column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  	    Home page is currently in development... \
				      </form> \
					</div> \
					<div class=\"four wide column\"> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
				  <div class=\"three wide column\"> \
					</div> \
					<div class=\"ten wide column\"> \
					</div> \
					<div class=\"three wide column\"> \
					</div> \
				  </div> \
				</div>" + DoleticUIFactory.makeUploadForm('avatar', true);
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
    }
};