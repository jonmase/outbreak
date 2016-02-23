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
	angular.module('flu.samples')
		.factory('schoolFactory', schoolFactory)
		
	schoolFactory.$inject = ['$resource', '$q'];

	function schoolFactory($resource, $q) {
		//Variables
		//var schools = readSchools();
		var schools;
		
		//Exposed Methods
		var factory = {
			getSchools: getSchools,
			loadSchools: loadSchools,
		};
		return factory;
		
		//Methods

		function getSchools() { 
			return schools; 
		}
		
		function loadSchools() {
			var deferred = $q.defer();
			if(typeof(ATTEMPT_ID) === "undefined" || typeof(ATTEMPT_TOKEN) === "undefined") {
				var SchoolsCall = $resource(URL_MODIFIER + 'schools/load.json', {});
				var parameters = {};
			}
			else {
				var SchoolsCall = $resource(URL_MODIFIER + 'schools/load/:attemptId/:token.json', {attemptId: null, token: null});
				var parameters = {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN};
			}
			
			SchoolsCall.get(parameters,
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						schools = result.schools;
						deferred.resolve('Schools loaded');
					}
					else {
						deferred.reject('Schools load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Schools load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		/*function readSchools() {
			//API: get the schools from the DB? [constant]
			//API: Get acuteDisabled from the DB for each school
			var schools = [
				{
					id: 'school1',
					name: 'Cabot Road Primary School, Westbridge',
					details: 'Infected December 2015. Children are convalescent.',
					//infected: 'Dec 2015',
					//current: 'Children have recovered',
					acute: false,	//Can acute samples be taken
					acuteDisabled: false,	//Are acute checkboxes disabled for this school. Will be set to true once user has seen the "can't get acute samples" message
					convalescent: true,	//Can convalescent samples be taken
					//convalescentDisabled: false,	//Are convalescent checkboxes disabled for this school. Currently no reason why they should ever be
					//showReturnTripCheck: false,	//Toggle for whether return trip check text and box should be shown - always start as false (and will always be false here as can't take acute samples)
					//returnTripOk: false,	//Toggle for whether return trip has been OK'd. Not currenlty used
					children: [
						{id: 'ja', name: 'Jennifer A'},
						{id: 'js', name: 'Jason S'},
						{id: 'kt', name: 'Kasim T'},
						{id: 'dl', name: 'Dawn L'},
					],
				},
				{
					id: 'school2',
					name: 'St. Bride\'s Primary School, Tovington',
					details: 'Infected January 2016. Children are still symptomatic.',
					//infected: 'Jan 2016',
					//current: 'Children still symptomatic',
					acute: true,
					acuteDisabled: false,
					convalescent: true,
					//convalescentDisabled: false,	//Are convalescent checkboxes disabled for this school. Currently no reason why they should ever be
					//showReturnTripCheck: false,
					//returnTripOk: false,
					children: [
						{id: 'jb', name: 'Johnny B'},
						{id: 'ai', name: 'Ann I'},
						{id: 'jo', name: 'Jamal O'},
					],
				}
			];
			
			return schools;
		}*/
	}
})();