(function() {
	angular.module('flu.samples', [])
	angular.module('flu.lab', [])
	angular.module('flu.modals', [])
	angular.module('flu.filters', [])
	angular
		.module('flu.marking', ['ngResource', 'ui.bootstrap', 'ngCkeditor', 'ui.checkbox', 'flu.samples', 'flu.lab', 'flu.modals', 'flu.filters']);
		
})();