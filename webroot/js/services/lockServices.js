(function() {
	angular.module('flu')
		.factory('lockFactory', lockFactory);
	
	lockFactory.$inject = ['$location', 'sectionsConstant', 'progressFactory'];
	
	//Locks factory - deals with setting and checking the locks on sections
	//NO API requirements
	function lockFactory($location, sectionsConstant, progressFactory) {
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
		
		function setComplete(sectionId, saveToDB) {
			if(typeof(saveToDB) === 'undefined') {	saveToDB = true; }
			return setProgressAndLocks(sectionId, 1, saveToDB);	//Set the progress for this section to complete
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

		function setProgressAndLocks(sectionId, completed, saveToDB) {
			progressFactory.setProgress(sectionId, completed);	//Set progress locally
			setLocks();
			if(saveToDB) {
				var progressPromise = progressFactory.saveProgress(sectionId, completed);	//save progress to DB
				return progressPromise;
			}
			else {
				return true;
			}
			//progress[sectionId] = completed;
			//return setLocks();
		}
	}
})();