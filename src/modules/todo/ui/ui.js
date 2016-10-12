var DoleticUIModule;
DoleticUIModule = new function () {
    /**
     *    Parent abstract module
     */
    this.super = new AbstractDoleticUIModule('Todo_UIModule', 'Paul Dautry', '1.0dev');
    /**
     *    Override render function
     */
    this.render = function (htmlNode) {
        this.super.render(htmlNode, this);
        // loadModals
        this.loadNewTaskModal();
        this.loadNewEventModal();
        this.loadPlanningExample();
        // fill category field
        //DoleticUIModule.fillCategorySelector();
        // fill ticket list
        //DoleticUIModule.fillTicketsList();
        window.postLoad();
    };
    /**
     *    Override build function
     */
    this.build = function () {
        return "\
        <div class=\"ui two column grid container\">\
            <div class=\"ui ten wide column\">\
				  <div class=\"row\"> \
					<div class=\"ui horizontal divider\">Mon Planning</div> \
				    <div id=\"planning_list\" class=\"ui very relaxed celled selection list\"> \
						<!-- USER TICKETS WILL GO HERE --> \
                    </div>\
				  </div> \
				  <div class=\"row\"> \
				  </div> \
			</div>\
			<div class = \"ui six wide column\">\
			    <div class=\"ui segment\">\
                    <h4 class=\"ui dividing header\">Ajouter des éléments au planning</h4>\
                    <div class=\"ui fluid green button\" onClick=\"DoleticUIModule.showNewTaskForm();\"><i class=\"icon tasks\"></i> Créer une nouvelle tâche</div>\
                    <div class=\"ui fluid olive button\" onClick=\"DoleticUIModule.showNewEventForm();\"><i class=\"icon add to calendar\"></i> Créer un nouvel évènement</div>\
                    <h4 class=\"ui dividing header\">Filtrer les éléments</h4>\
                </div>\
			</div>\
				\
				\
				<div class=\"ui modal\" id=\"task_form_modal\">\
				</div>\
				<div class=\"ui modal\" id=\"event_form_modal\">    \
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
            $('.ui.horizontal.divider').attr('class', 'ui horizontal divider inverted');
            $('#ticket_list').attr('class', 'ui very relaxed celled selection list inverted');
            $('#support_form').attr('class', 'ui form segment inverted');
            $('#abort_btn').attr('class', 'ui button inverted');
            $('#send_btn').attr('class', 'ui green button inverted');
        } else {
            $('.ui.horizontal.divider.inverted').attr('class', 'ui horizontal divider');
            $('#ticket_list').attr('class', 'ui very relaxed celled selection list');
            $('#support_form').attr('class', 'ui form segment');
            $('#abort_btn').attr('class', 'ui button');
            $('#send_btn').attr('class', 'ui green button');
        }
    };

// ---- OTHER FUNCTION REQUIRED BY THE MODULE ITSELF

    this.hasInputError = false;

    /**
     *    Load the HTML code of the New Task Modal
     */
    this.loadNewTaskModal = function () {
        $('#task_form_modal').load("../modules/todo/ui/templates/newTaskModal.html");
    };

    /**
     *    Show the New Task Modal
     */
    this.showNewTaskForm = function () {
        DoleticUIModule.clearNewTaskForm();
        $('#task_form_modal').modal('show');
    };

    /**
     *    Clear the New Task Modal
     */
    this.clearNewTaskForm = function () {
        $('#task_form .dropdown').dropdown('restore defaults');
        $('#task_form')[0].reset();
    };

    /**
     *    Close the New Task Modal
     */
    this.closeNewTaskForm = function () {
        $('#task_form_modal').modal('hide');
    };

    /**
     *    Load the HTML code of the New Task Modal
     */
    this.loadNewEventModal = function () {
        $('#event_form_modal').load("../modules/todo/ui/templates/newEventModal.html");
    };

    /**
     *    Show the New Task Modal
     */
    this.showNewEventForm = function () {
        DoleticUIModule.clearNewEventForm();
        $('#event_form_modal').modal('show');
    };

    /**
     *    Clear the New Task Modal
     */
    this.clearNewEventForm = function () {
        $('#event_form .dropdown').dropdown('restore defaults');
        $('#event_form')[0].reset();
        $('#event_form #owner').val('Théo THIBAULT');
    };

    /**
     *    Load the HTML code of the New Task Modal
     */
    this.loadPlanningExample = function () {
        $('#planning_list').load("../modules/todo/ui/templates/planningExemple.html");
    };

};