(function() {
	angular.module('flu.marking')
		.factory('markingFactory', markingFactory);
		
	markingFactory.$inject = ['$resource', '$q'];
	
	function markingFactory($resource, $q) {
		//Variables
		var users, userCount; 
		
		//Exposed Methods
		var factory = {
			//cancelAutosaveTimeout: cancelAutosaveTimeout,
			setup: setup,
			getUserCount: getUserCount,
			getUsers: getUsers,
			loadUsers: loadUsers,
			save: save,
			setLock: setLock,
		}
		return factory;
		
		//Methods
		function setup() {
		}
		
		function getUserCount() {
			return userCount;
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
						userCount = result.userCount;
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
		
		function setLock(userId, lock) {
			//API: Lock user, so no-one else can edit them, or unlock it (if lock is false)
			var userId = users[userId].id;
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'marks/save/lock', {});
			ReportCall.save({}, {userId: userId, data: lock},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						deferred.resolve('Mark lock set');
					}
					else {
						if(result.status === 'locked') {
							deferred.reject('locked');
						}
						deferred.reject('Mark lock set failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Mark lock set error (' + result.status + ')');
				}
			);
			return deferred.promise;
			//return now;
		}

		function save(userId) {
			//API: Save mark
			var userId = users[userId].id;
			var mark = users[userId].marks;
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'marks/save', {});
			ReportCall.save({}, {userId: userId, data: mark},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						users[userId].marked = 1;
						users[userId].editing = 0;
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