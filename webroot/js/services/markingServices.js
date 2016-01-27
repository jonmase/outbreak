(function() {
	angular.module('flu.marking')
		.factory('markingFactory', markingFactory);
		
	markingFactory.$inject = ['$resource', '$q'];
	
	function markingFactory($resource, $q) {
		//Variables
		var users; 
		
		//Exposed Methods
		var factory = {
			//cancelAutosaveTimeout: cancelAutosaveTimeout,
			setup: setup,
			getUsers: getUsers,
			loadUsers: loadUsers,
		}
		return factory;
		
		//Methods
		function setup() {
		}
		
		//function get
		
		function getUsers() {
			return users;
		}
		
		
		
		
		function loadUsers() {
			var deferred = $q.defer();
			var UsersCall = $resource('marks/load.json', {});
			UsersCall.get({},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						users = result.users;
						deferred.resolve('Marks loaded');
					}
					else {
						deferred.reject('Marks load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Marks load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
	}
})();