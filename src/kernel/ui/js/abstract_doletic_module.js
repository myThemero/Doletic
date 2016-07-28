/*
 *	This is DefaultDoleticUIModule singleton, 
 *	it will be called by DoleticMasterInterface 
 *  if no var called DoleticUIModule is defined.
 *
 *	This interface declares a super class as this.super
 *	which must be done in DoleticUIModule to.
 */
var DefaultDoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('DefaultUIModule', 'Paul Dautry', '1.0dev');
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
        return "<div class=\"ui grid container\"> \
				  <div class=\"row\"> \
				  	<!-- an empty row to remove later --> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"three wide column\"></div> \
					<div class=\"ten wide column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  	    Le module graphique par défaut à été chargé, cela signifie qu'il n'y pas d'interface implémentée.<br> \
				  	    Merci de contacter un développeur en lui indiquant la page et les actions que vous avez effectuées. \
				      </form> \
					</div> \
					<div class=\"three wide column\"></div> \
				  </div> \
				</div>";
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data) {
        this.super.uploadSuccessHandler(id, data);
    }
};
/*
 *	This AbstractDoleticUIModule defines a small interface which
 *  must be used when creating a new DoleticUIModule singleton for a class.
 */
function AbstractDoleticUIModule(name, authors, version) {
    /**
     *    Module meta data block
     */
    this.meta = {
        name: name,
        authors: authors,
        version: version
    };
    /**
     *    Module default render function renders the html page for the given module
     */
    this.render = function (htmlNode, module) {
        if (module == null) {
            // build module UI
            htmlNode.innerHTML = this.build();
        } else {
            htmlNode.innerHTML = module.build();
        }
    };
    /**
     *    Module default build function creates HTML content to be put in html node by render function
     */
    this.build = function () {
        return "<div class=\"ui grid container\"> \
				  <div class=\"row\"> \
				  	<!-- an empty row to remove later --> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"three wide column\"></div> \
					<div class=\"ten wide column\"> \
					  <form id=\"login_form\" class=\"ui form segment\"> \
				  	    Module " + this.meta.name + " is currently in development... \
				      </form> \
					</div> \
					<div class=\"three wide column\"></div> \
				  </div> \
				</div>";
    };
    /**
     *    Module default uploadSuccessHandler function shows an error message
     */
    this.uploadSuccessHandler = function (id, data) {
        // show an error message
        DoleticMasterInterface.showError("Erreur d'upload !", "Le handler d'upload a été appelé (id:" + id + "), merci de prévenir un développeur en lui indiquant la page sur laquelle vous êtes actuellement et les actions que vous avez effectuées ! Merci !");
    }

}