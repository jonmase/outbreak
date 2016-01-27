(function() {
	angular.module('flu.progress')
		.factory('progressFactory', progressFactory);

	progressFactory.$inject = ['moneyCutoff', 'timeCutoff', '$resource', '$q'];

	function progressFactory(moneyCutoff, timeCutoff, $resource, $q) {
		//Variables
		var startingMoney = 200;
		var startingTime = 48;
		var progress = {};
		//progress = readProgress();
		var resources = {};
		//var resources = readResources();

		//Exposed Methods
		var factory = {
			checkProgress: checkProgress,
			getProgress: getProgress,
			getResources: getResources,
			loadProgress: loadProgress,
			loadResources: loadResources,
			resetResources: resetResources,
			setProgress: setProgress,
			//saveProgress: saveProgress,	//Now done in lockFactory as need to set locks afterwards and can't do from here due to circular factory reference
			subtractResources: subtractResources,
		}
		return factory;
		
		//Methods
		function checkProgress(sectionId) { 
			return progress[sectionId]; 
		}
		
		function getProgress() { 
			return progress; 
		}
		
		function getResources() { 
			return resources; 
		}	

		function loadProgress() { 
			//API: Get user's progress from DB
			var deferred = $q.defer();
			var ProgressCall = $resource('../loadProgress/:attemptId/:token.json', {attemptId: null, token: null});
			ProgressCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN}, 
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						progress = result.progress;
						deferred.resolve('Progress loaded');
					}
					else {
						deferred.reject('Progress load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Progress load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		
			/*
			Checkpoints
			1. First page load > show video
			2. Start button > get memo
			3. Seen memo > Revision Available
			4. Usefulness of techniques all answered > Questions
			5. Questions all answered > Samples
			6. Collected at least one sample > Lab
			7. Arrive at lab > Flu memo > Results available
			8. Identified H and N > Report available
			9. Submit Report > Research available
			10. Research visited > Finish Button
			*/
			/*var progress = {
				start: 1,	//Clicked start button after intro video > Show Alert
				alert: 1,	//Seen initial alert > Unlock Revision
				revision: 1,	//Looked at revision materials and ticked whether or not they are useful > Unlock Questions
				questions: 1,	//Completed the Questions > Unlock Samples
				samples: 0,	//Collected at least one sample > Unlock Lab
				lab: 0,	//Been to the lab > Show flu memo and Unlock Results
				hidentified: 0,	//Identified H in the lab > Unlock Report and research (if N also identified)
				nidentified: 0,	//Identified N in the lab > Unlock Report and research (if H also identified)
				report: 0,	//Submitted the report
				research: 0,	//Visited research page > Unlock Finish
			};*/
			//return progress;
		}
		
		function loadResources() { 
			//API: Get user's resources from DB
			var deferred = $q.defer();
			var ResourcesCall = $resource('../loadResources/:attemptId/:token.json', {attemptId: null, token: null});
			ResourcesCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						resources = result.resources;
						deferred.resolve('Resources loaded');
					}
					else {
						deferred.reject('Resources load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Resources load error (' + result.status + ')');
				}
			);
			return deferred.promise;

			/*var resources = {
				money: startingMoney,
				time: startingTime,
			};*/
			//return resources; 
		}
		
		//Reset the user's resources to the initial values (e.g. if they beg for more)
		function resetResources(resetMoney, resetTime) {
			//API: Reset resources in DB
			//resources = angular.copy(startingResources);
			var money = null;
			var time = null;
			if(resetMoney) {
				var money = startingMoney;
			}
			if(resetTime) {
				var time = startingTime;
			}
			var deferred = $q.defer();
			var ResourcesCall = $resource(URL_MODIFIER + 'attempts/saveResources', {});
			ResourcesCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, money: money, time: time},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						if(resetMoney) {
							resources.money = money;
						}
						if(resetTime) {
							resources.time = time;
						}
						deferred.resolve('Resources reset');
					}
					else {
						deferred.reject('Resources reset failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Resources reset error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		//Update the user's progress in a section. Completed = 1 or 0.
		function setProgress(sections, completed) {
			if(angular.isString(sections)) {
				progress[sections] = completed;
			}
			if(angular.isArray(sections)) {
				for(var i = 0; i < sections.length; i++) {
					progress[sections[i]] = completed;
				}
			}		
		}
		
		/*function saveProgress(sectionId, completed) {
			//API: Update user's progress in DB
			var deferred = $q.defer();
			var ProgressCall = $resource('../saveProgress', {});
			ProgressCall.save({}, {attemptId: ATTEMPT_ID, sectionId: sectionId, completed: completed}, function(result) {
				//console.log(result.message);
				setProgress(sectionId, completed)
				deferred.resolve('Progress saved');
				deferred.reject('Progress not saved');
			});
			return deferred.promise;
			//return progress;
		}*/
		
		function subtractResources(money, time) {
			//API: Subtract resources in DB
			resources.money -= money;
			resources.time -= time;
		}
	}
})();