(function() {
	angular.module('flu')
		.factory('lockFactory', lockFactory);
	
	lockFactory.$inject = ['$location', '$resource', '$q', 'sectionsConstant', 'progressFactory'];
	
	//Locks factory - deals with setting and checking the locks on sections
	//NO API requirements
	function lockFactory($location, $resource, $q, sectionsConstant, progressFactory) {
		//Variables
		var locks = {};
		var sections = sectionsConstant();
		
		//Exposed Methods
		var factory = {
			checkLock: checkLock,
			checkLockOnClick: checkLockOnClick,
			getLock: getLock,
			setComplete: setComplete,
			setLocks: setLocks,
			setProgressAndLocks: setProgressAndLocks,
		}
		return factory;
		
		//Methods
		function checkLock(sectionId) {
			if(getLock(sectionId)) {	//If sections is locked...
				$location.path("/home");	//Redirect home
				return false;
			}
			return true;
		}
		
		function checkLockOnClick(sectionId) {
			if(getLock(sectionId)) {	//If section is locked
				//alert("This section is locked");	//Alert the user that the section is locked
				return false;	//Do nothing
			}
			else {
				$location.path("/" + sectionId);	//Send them to the section
				return true;
			}
		}

		function getLock(sectionId) {
			return locks[sectionId];
		}
		
		function setComplete(sections, saveToDB) {
			if(typeof(saveToDB) === 'undefined') {	saveToDB = true; }
			//return setProgress(sectionId, 1, saveToDB);	//Set the progress for this section to complete
			if(saveToDB) {
				var progressPromise = saveProgress(sections, 1);	//save progress to DB and set locally
				return progressPromise;
			}
			else {
				setProgressAndLocks(sections, 1);	//Set progress locally only
				return true;
			}
		}

		function setLocks() {
			var progress = progressFactory.getProgress();
			var oldLocks = angular.copy(locks);
			for(var sectionId in sections) {
				locks[sectionId] = 1;
				var incompletePrerequisites = 0;
				for(var prerequisite = 0;  prerequisite < sections[sectionId].prerequisites.length; prerequisite++) {
					if(!progress[sections[sectionId].prerequisites[prerequisite]]) {
						incompletePrerequisites = 1;
						break;
					}
				}
				if(!incompletePrerequisites) {
					locks[sectionId] = 0;
				}
				if(oldLocks[sectionId] && !locks[sectionId]) {
					//Highlight the newly unlocked section(s)
					//alert("unlocked section: " + sections[sectionId].name);
				}
			}
			return locks;
		}

		//Update the user's progress in a section. Completed = 1 or 0.
		function setProgressAndLocks(sections, completed) {
			progressFactory.setProgress(sections, completed);
			setLocks();
		}
		
		function saveProgress(sections, completed) {
			//API: Update user's progress in DB
			var deferred = $q.defer();
			var ProgressCall = $resource('../saveProgress', {});
			ProgressCall.save({}, {attemptId: ATTEMPT_ID, sections: sections, completed: completed},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						setProgressAndLocks(sections, completed);
						deferred.resolve('Progress saved');
					}
					else {
						deferred.reject('Progress save failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Progress save error (' + result.status + ')');
				}
			);
			return deferred.promise;
			//return progress;
		}
	}
})();