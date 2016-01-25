
// ----------------------- DOLETIC INTERFACE SERVICES CLASS ----------------------------------

/**
 *  DoleticServicesInterface
 */
var DoleticServicesInterface = new function() {

  /**
   *  Global url to main script
   */
  this.doleticMainScript = 'http://localhost/src/kernel/Main.php';

  /**
   *  Loads a standard Doletic page using ui parameter 
   */
  this.requireUI = function(ui) {
    window.location.replace(this.doleticMainScript + '?q=ui&page=' + ui);
  }
  /**
   *  Loads a specific Doletic Login page
   */
  this.requireSpecialLogin = function() {
    this.requireUI('login');
  }
  /**
   *  Loads a specific Doletic Login Failed page
   */
  this.requireSpecialLoginFailed = function() {
    this.requireUI('login_failed');
  }
  /**
   *  Loads a specific Doletic Logout page
   */
  this.requireSpecialLogout = function() {
    this.requireUI('logout');
  }
  /**
   *  Loads a specific Doletic 404 page
   */
  this.requireSpecial404 = function() {
    this.requireUI('404');
  }
  /**
   *  Loads a specific Doletic Home page
   */
  this.requireSpecialHome = function() {
    this.requireUI('home');
  }
  /**
   *  Loads a specific Doletic Auth page
   */
  this.requireAuth = function(username, password, success) {
    this.ajaxPOST(
      this.doleticMainScript, 
      {
        q: 'auth',
        user: username,
        hash: phpjsLight.sha1(password)
      },
      "json",
      function(jqXHR, textStatus, errorThrown) {
        // Show an error message on doletic interface
        DoleticMasterInterface.showError("L'appel AJAX a échoué !", "<p>L'appel AJAX d'authentification à échoué ! Détails : " + errorThrown + " ("+textStatus+")</p>");
        // Debug the response content
        console.debug(jqXHR.responseText);
      },
      success
      );
  }
  /**
   *  Make an AJAX call to Doletic services to retrieve some data
   */
  this.requireService = function(obj, action, params) {
    /// \todo implement here
  }
  /**
   *  Make an ajax call. Do not call it from the outside of this class
   */
  this.ajaxPOST = function(url, data, dataType, error, success) {
      // DEBUG -------------------------------
      console.debug("ajaxPOST called with url = "+url);
      // -------------------------------------
      $.ajax({
         url: url,
         data: data,
         dataType: dataType,
         error: error,
         success: success,
         type: "POST"
      });
  }

}