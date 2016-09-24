var DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('HR_UIModule', 'Nicolas Sorin', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {

        this.super.render(htmlNode, this);

        // Load HTML templates
        DoleticUIModule.getStatsTab();

        $('.menu .item').tab();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "<div class=\"ui two column grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
				  	<div class=\"sixteen wide column\" id=\"page_content\"> \
				  		<div class=\"ui top attached tabular menu\" id=\"tabmenu\" > \
  							<a class=\"item active\" data-tab=\"stats\">Statistiques</a> \
						</div> \
						<div class=\"ui bottom attached tab segment active\" data-tab=\"stats\"> \
							<div id=\"statsTab\">\
								<div class=\"ui loader active\"></div>\
							</div>\
					    </div> \
					</div> \
				  </div> \
				</div>";
    };
    /**
     *    Override uploadSuccessHandler
     */
    this.uploadSuccessHandler = function (id, data) {
        DocumentServicesInterface.insert(window.currentDetails, window.currentTemplate, data, function () {
            DoleticUIModule.fillDocumentList(window.currentDetails);
        });
    };

    this.nightMode = function (on) {
        /*if(on) {
         $('.ui.horizontal.divider').attr('class', 'ui horizontal divider inverted');
         $('#ticket_list').attr('class', 'ui very relaxed celled selection list inverted');
         $('#support_form').attr('class', 'ui form segment inverted');
         $('#abort_btn').attr('class', 'ui button inverted');
         $('#adduser_btn').attr('class', 'ui green button inverted');
         } else {
         $('.ui.horizontal.divider.inverted').attr('class', 'ui horizontal divider');
         $('#ticket_list').attr('class', 'ui very relaxed celled selection list');
         $('#support_form').attr('class', 'ui form segment');
         $('#abort_btn').attr('class', 'ui button');
         $('#adduser_btn').attr('class', 'ui green button');
         }*/
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    /**
     *    Load the HTML code of the Stats Tab
     */
    this.getStatsTab = function () {
        $('#statsTab').load("../modules/ua/ui/templates/statsTab.html");
    };

    this.hasInputError = false;

    this.drawGraphs = function () {
        IndicatorServicesInterface.processAllGraphByModule('ua', function (data) {
            DoleticMasterInterface.drawGraphs(data.object, 'graphs');
        });
    };

    this.fillValueIndicators = function () {
        IndicatorServicesInterface.processAllValueByModule('ua', function (data) {
            DoleticMasterInterface.fillValueIndicators(data.object, 'indicators_body');
        });
    };

    this.fillTableIndicators = function () {
        IndicatorServicesInterface.processAllTableByModule('ua', function (data) {
            DoleticMasterInterface.fillTableIndicators(data.object, 'tables');
        });
    };
};