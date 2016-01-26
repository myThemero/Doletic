
// ------------------------ DOLETIC MASTER INTERFACE CLASS  ----------------------------------

/**
 *  DoleticModuleInterface
 */
var DoleticMasterInterface = new function() {

  // Constantes --------------------------------
  this.module_container_id = "module_container";
  this.master_container_id = "master_container";
  // -------------------------------------------
  /**
   *  Render function, this function builds the content of the web page, 
   *  using the given module
   */
  this.render = function( htmlElement ) {
    // debugging message -----------------------------------------
      console.debug("DoleticMasterInterface.render : Starting rendering process.");
      // -----------------------------------------------------------
      // create doletic page standard HTML content
      var html = this.buildUI();
      // set htmlElment innerHTML content
      htmlElement.innerHTML = html;
      // debugging message -----------------------------------------
      console.debug("DoleticMasterInterface.render : Calling render for DoleticModuleInterface::"+DoleticModuleInterface.meta.name);
      // -----------------------------------------------------------
      // for each module call render function
      DoleticModuleInterface.render(document.getElementById(this.module_container_id));
      // debugging message -----------------------------------------
      console.debug("DoleticMasterInterface.render : Rendering process finished.");
      // -----------------------------------------------------------
  }
  /**
   *  Build Doletic common ui
   */
  this.buildUI = function() {
    var html = "<div id=\"left_sidebar\" class=\"ui visible sidebar vertical menu\"> \
                  <a id=\"menu_doletic\" class=\"item\" onClick=\"DoleticServicesInterface.requireSpecialHome();\"><img class=\"ui mini spaced image\" src=\"/resources/doletic_logo.png\">Doletic v2.0</a> \
                  <a id=\"menu_about_doletic\" class=\"item\" onClick=\"DoleticMasterInterface.showAboutDoletic();\"><i class=\"info circle icon\"></i>About Doletic</a> \
                </div> \
                <div class=\"pusher\"> \
                  <div class=\"ui container\"> \
                    <div id=\""+this.master_container_id+"\" class=\"ui one column centered grid container\"> \
                    </div> \
                  </div> \
                  <div id=\""+this.module_container_id+"\"> \
                  <!-- module custom content goes here --> \
                  </div> \
                  <div id=\"about_doletic_modal\" class=\"ui basic modal\"> \
                    <i class=\"close icon\"></i> \
                    <div class=\"header\">Doletic v2.0</div> \
                    <div class=\"image content\"> \
                      <div class=\"image\"><i class=\"line chart icon\"></i></div> \
                      <div class=\"description\">  \
                        <p> \
                        Doletic est un ERP open-source destiné au Junior-Entreprises.<br><br> \
                        Son développement est à l'initiative du pôle DSI de la Junior-Entreprise de l'INSA de Lyon, \
                        ETIC INSA Technologies. Il est actuellement en cours de développement. \
                        Si vous souhaitez contribuer à ce merveilleux projet n'hésitez pas à nous contacter ! \
                        </p>  \
                        <p> \
                        Développeurs : \
                        <ul class=\"ui list\"> \
                          <li>Paul Dautry (ETIC INSA TEchnologies)</li> \
                          <li>Nicolas Sorin (ETIC INSA TEchnologies)</li> \
                        </ul> \
                        </p>  \
                      </div>  \
                    </div>  \
                    <div class=\"actions\"> \
                      <div class=\"one fluid ui inverted buttons\"> \
                        <div class=\"ui green basic inverted button\" onClick=\"DoleticMasterInterface.hideAboutDoletic();\"><i class=\"checkmark icon\"></i>Cool !</div>  \
                      </div> \
                    </div> \
                  </div> \
                </div> \
                <div id=\"right_sidebar\" class=\"ui visible right sidebar vertical menu\"> \
                  <a id=\"menu_logout\" class=\"item\" onClick=\"DoleticServicesInterface.requireSpecialLogout();\"><i class=\"power icon\"></i>Logout</a> \
                </div>";
    return html;
  }

// ----------------------- DOLETIC INTERFACE COMMON FUNCTIONS ----------------------------------

  /**
   *  Removes logout button (usefull for login and logout interfaces)
   */
  this.removeLogoutButton = function() {
      $('#menu_logout').remove();
  }
  /**
   *  Shows about Doletic modal
   */
  this.showAboutDoletic = function() {
      $('#about_doletic_modal').modal('show');
  }
  /**
   *  Hides about Doletic modal
   */
  this.hideAboutDoletic = function() {
      $('#about_doletic_modal').modal('hide');
  }
  /**
   *  Shows a message
   *  @param type : message type
   *  @param header : message title
   *  @param content : message content
   */
  this.showMessage = function(type, header, content) {
       $('#'+this.master_container_id).append(
       "<div class=\"column\"> \
          <div class=\"ui " + type + " message\"> \
            <i class=\"close icon\" onClick=\"this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);\" ></i> \
            <div class=\"header\">" + header + "</div>" + 
            content + 
         "</div> \
        </div>");
  }
  this.showInfo = function(title, msg) { this.showMessage('info', title, msg); }
  this.showSuccess = function(title, msg) { this.showMessage('success', title, msg); }
  this.showWarn = function(title, msg) { this.showMessage('warning', title, msg); }
  this.showError = function(title, msg) { this.showMessage('negative', title, msg); }

}

// ------------------------ DOLETIC DOCUMENT REDAY FUNCTION ------------------------------------

/**
 *  DOCUMENT READY : entry point
 */
$(document).ready(function(){
    // render page
    DoleticMasterInterface.render(document.getElementById('body'));
})