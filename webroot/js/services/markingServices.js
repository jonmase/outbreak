/**
    Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/

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
			loadUserAttempts: loadUserAttempts,
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
			var UsersCall = $resource('marks/loadUsers.json', {});
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
		
		function loadUserAttempts(userId) {
			var deferred = $q.defer();
			var UsersCall = $resource('marks/loadUserAttempts/:userId.json', {userId: null});
			UsersCall.get({userId: userId},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						users[userId]['attempts'] = result.attempts;
						deferred.resolve('Attempts loaded for user ID: ' + userId);
					}
					else {
						deferred.reject('Marks load failed for user ID: ' + userId + ' (' + result.status + ')');
					}
				},
				function(result) {
					deferred.reject('Marks load error for user ID: ' + userId + ' (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		function setLock(userId, lock) {
			//API: Lock user, so no-one else can edit them, or unlock it (if lock is false)
			var userId = users[userId].id;
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'marks/save/lock.json', {});
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
			var ReportCall = $resource(URL_MODIFIER + 'marks/save.json', {});
			ReportCall.save({}, {userId: userId, data: mark},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						users[userId].marks.created = result.marked_on;
						users[userId].marks.marker = result.marker;
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