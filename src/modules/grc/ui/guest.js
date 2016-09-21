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
        // Load HTML templates
        DoleticUIModule.getStatsTab();
        window.postLoad();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"ui two column grid container\"> \
 				  	<div class=\"row\"> \
 				  		<div class=\"sixteen wide column\"> \
 				  			<div class=\"ui top attached tabular menu\"> \
								<a class=\"item active\" data-tab=\"stats\">Statistiques</a> \
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
     *    Load the HTML code of the Stats Tab
     */
    this.getStatsTab = function () {
        $('#statsTab').load("../modules/grc/ui/templates/statsTab.html");
    };
};
