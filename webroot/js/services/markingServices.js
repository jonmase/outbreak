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
			save: save,
		}
		return factory;
		
		//Methods
		function setup() {
		}
		
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
		
		function save(userIndex) {
			//API: Save mark
			var userId = users[userIndex].id;
			var mark = users[userIndex].marks;
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'marks/save', {});
			ReportCall.save({}, {userId: userId, mark: mark},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						users[userIndex].marked = 1;
						deferred.resolve('Mark saved');
					}
					else {
						deferred.reject('Mark save failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Mark save error (' + result.status + ')');
				}
			);
			return deferred.promise;
			//return now;
		}
	}
})();