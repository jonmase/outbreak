(function() {
	angular.module('flu.progress', [])
	angular.module('flu.techniques', [])
	angular.module('flu.samples', [])
	angular.module('flu.lab', [])
	angular.module('flu.modals', [])
	angular.module('flu.filters', [])
	angular
		.module('flu.marking', ['ngResource', 'ui.bootstrap', 'ngCkeditor', 'ui.checkbox', 'flu.progress', 'flu.techniques', 'flu.samples', 'flu.lab', 'flu.modals', 'flu.filters']);
		
})();