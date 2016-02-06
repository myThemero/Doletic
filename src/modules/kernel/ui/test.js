var DoleticUIModule = new function() {
	/**
	 *	Parent abstract module
	 */
	this.super = new AbstractDoleticUIModule('Test_UIModule', 'Paul Dautry', '1.0dev');
	/**
	 *	Override render function
	 */
	this.render = function(htmlNode) {
		this.super.render(htmlNode, this);
	}
	/**
	 *	Override build function
	 */
	this.build = function() {
		return "<div class=\"ui two column grid container\"> \
				  <div class=\"row\"> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"two wide column\"> \
					</div> \
					<div class=\"twelve wide column\"> \
					  <div class=\"ui info message\"> \
					    <div class=\"header\">Page de tests des services AJAX</div> \
					    <p> \
					    	Cette page est destinée aux test des appels de services. \
					    </p> \
					  </div> \
					</div> \
					<div class=\"two wide column\"> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
				  <div class=\"two wide column\"> \
					</div> \
					<div class=\"twelve wide column\"> \
					  <div class=\"ui middle aligned divided list\"> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.getComments();\">Run</div></div> \
						    <div class=\"content\">Récupérer tous les commentaires</div> \
						  </div> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.getUsers();\">Run</div></div> \
						    <div class=\"content\">Récupérer tous les utilisateurs</div> \
						  </div> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.getCurrentUser();\">Run</div></div> \
						    <div class=\"content\">Récupérer l'utilisateur courrant</div> \
						  </div> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.getUserData();\">Run</div></div> \
						    <div class=\"content\">Récupérer toutes les données utilisateur</div> \
						  </div> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.getModules();\">Run</div></div> \
						    <div class=\"content\">Récupérer tous les modules</div> \
						  </div> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.getTickets();\">Run</div></div> \
						    <div class=\"content\">Récupérer tous les tickets</div> \
						  </div> \
						</div> \
					</div> \
					<div class=\"two wide column\"> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"two wide column\"> \
					</div> \
					<div class=\"twelve wide column\"> \
					<div class=\"example\"> \
					    <div class=\"html ui top attached segment\"> \
					    	<div class=\"ui top attached label\"><b>AJAX Response</b></div> \
						</div> \
						<div style=\"display: none;\" class=\"annotation transition visible\"> \
							<div id=\"response_zone\" class=\"ui instructive bottom attached segment\"> \
								Aucun service n'a encore été appelé ! \
							</div> \
						</div> \
 					</div> \
					</div> \
					<div class=\"two wide column\"> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
				  <div class=\"two wide column\"> \
					</div> \
					<div class=\"twelve wide column\"> \
					  <div class=\"ui middle aligned divided list\"> \
						  <div class=\"item\"> \
						    <div class=\"right floated content\"><div class=\"ui button\" onClick=\"DoleticUIModule.displayPlot();\">Run</div></div> \
						    <div class=\"content\">Affiche un graphique</div> \
						  </div> \
						</div> \
					</div> \
					<div class=\"two wide column\"> \
					</div> \
				  </div> \
				  <div class=\"row\"> \
					<div class=\"two wide column\"> \
					</div> \
					<div class=\"twelve wide column\"> \
					  <div id=\"plot\" ><!-- Plotly chart will be drawn inside this DIV --></div> \
  					  <script> \
    				    <!-- JAVASCRIPT CODE GOES HERE --> \
  					  </script> \
					</div> \
					<div class=\"two wide column\"> \
					</div> \
				  </div> \
				</div>";
	}
	/**
	 *	Override uploadSuccessHandler
	 */
	this.uploadSuccessHandler = function(id, data) {
		this.super.uploadSuccessHandler(id, data);
	}

// ----------------------------------------------------------------------------------------------------------

	this.serviceSuccessHandler = function(data) {
		var content = "<b>Error code</b> : " + data.code + 
				  "<br/><b>Error status</b> : " + data.error + 
				  "<br/><b>Data</b> :<br/><pre><code class=\"code json\">" + 
				  DoleticUIFactory.JSONSyntaxHighlight(data.object) + 
				  "</code></pre>";
		$('#response_zone').html(content);
	}

	this.getComments = function() {
		CommentServicesInterface.getAll(DoleticUIModule.serviceSuccessHandler);
	}
	this.getUsers = function() {
		UserServicesInterface.getAll(DoleticUIModule.serviceSuccessHandler);
	}
	this.getCurrentUser = function() {
		DoleticServicesInterface.getCurrentUser(DoleticUIModule.serviceSuccessHandler);
	}
	this.getUserData = function() {
		UserDataServicesInterface.getAll(DoleticUIModule.serviceSuccessHandler);
	}
	this.getModules = function() {
		alert('ModuleServicesInterface not implemented yet !');
		//ModuleServicesInterface.getAll(DoleticUIModule.serviceSuccessHandler);
	}	
	this.getTickets = function() {
		TicketServicesInterface.getAll(DoleticUIModule.serviceSuccessHandler);
	}
	this.displayPlot = function() {
		var trace1 = {
  			x: [1, 2, 3, 4], 
  			y: [10, 15, 13, 17], 
  			type: 'scatter'
		};
		var trace2 = {
		  x: [1, 2, 3, 4], 
		  y: [16, 5, 11, 9], 
		  type: 'scatter'
		};
		var data = [trace1, trace2];
			Plotly.newPlot('plot', data);
		}
}