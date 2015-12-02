(function() {
	angular.module('flu')
		.factory('progressFactory', progressFactory)
		
	progressFactory.$inject = ['moneyCutoff', 'timeCutoff'];

	function progressFactory(moneyCutoff, timeCutoff) {
		//Variables
		var startingMoney = 200;
		var startingTime = 48;
		var progress = readProgress();
		var resources = readResources();

		//Exposed Methods
		var factory = {
			checkProgress: checkProgress,
			getProgress: getProgress,
			getResources: getResources,
			resetResources: resetResources,
			setProgress: setProgress,
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

		function readProgress() { 
			//API: Get user's progress from DB
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
			var progress = {
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
			};
			return progress; 
		}
		
		function readResources() { 
			//API: Get user's resources from DB
			var resources = {
				money: startingMoney,
				time: startingTime,
			};
			return resources; 
		}
		
		//Reset the user's resources to the initial values (e.g. if they beg for more)
		function resetResources() {
			//API: Reset resources in DB
			//resources = angular.copy(startingResources);
			if(resources.money < moneyCutoff) {
				resources.money = startingMoney;
			}
			if(resources.time < timeCutoff) {
				resources.time = startingTime;
			}
		}
		
		//Update the user's progress in a section. Completed = 1 or 0.
		function setProgress(sectionId, completed) {
			progress[sectionId] = completed;
			//API: Update user's progress in DB. Just set the changed value?
			return progress;
		}
		
		function subtractResources(money, time) {
			//API: Subtract resources in DB
			resources.money -= money;
			resources.time -= time;
		}
	}
})();