
// ----------------------- DOLETIC INTERFACE SERVICES CLASS ----------------------------------

/**
 *  DoleticServicesInterface
 */
var DoleticServicesInterface = new function() {

  /**
   *  Global url to main script
   */
  this.doleticMainScript = 'https://localhost/src/kernel/Main.php';

  /**
   *  Loads a standard Doletic page using ui parameter 
   */
  this.requireUI = function(ui) {
    location.replace(this.doleticMainScript + '?q=ui&page=' + ui);
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
  this.requireAuth = function() {
    this.ajaxJSONCall(
      this.doleticMainScript, 
      {
        /// \todo implement here
      })
  }
  /**
   *  Make an AJAX call to Doletic services to retrieve some data
   */
  this.requireService = function() {
    location.replace(this.doletic);
  }
  /**
   *  Make an ajax call. Do not call it from the outside of this class
   */
  this.ajaxJSONCall = function(urlString, dataPart, errorFunction, successFunction, ajaxType) {
      $.ajax({
         url: urlString,
         async: false,
         data: dataPart,
         error: function() {
            $('#info').html('<p>An error has occurred</p>');
         },
         dataType: 'json',
         success: successFunction,
         type: ajaxType
      });
  }

}