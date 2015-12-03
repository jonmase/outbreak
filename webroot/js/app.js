(function() {
	angular
		.module('flu', ['ngResource', 'ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngCkeditor', 'ui.checkbox', 'mgcrea.ngStrap.affix', 'flu.techniques', 'flu.questions', 'flu.samples', 'flu.lab', 'flu.results', 'flu.report', 'flu.directives', 'flu.filters'])	//with Ckeditor
		//.module('flu', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'textAngular', 'flu.techniques', 'flu.questions', 'flu.samples', 'flu.lab', 'flu.results', 'flu.report', 'flu.directives', 'flu.filters'])	//With textangular
		.config(config);
		
	config.$inject = ['$routeProvider', 'sectionsConstant'];
	function config($routeProvider, sectionsConstant) {
		var sections = sectionsConstant();
		
		$routeProvider.when('/home', {	//Home route
			templateUrl: '../../partials/home.html',
			controller: 'HomeController',
			controllerAs: 'homeCtrl',
		});
		for(var id in sections) {	//Section routes
			$routeProvider.when('/' + id, {
				templateUrl: '../../partials/' + sections[id].template + '.html',
				controller: sections[id].controller,
				controllerAs: sections[id].template + 'Ctrl'
			});
		}
		$routeProvider.otherwise( {	//Default route - go home
				redirectTo: "/home",
		});
	};
})();