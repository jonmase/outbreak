(function() {
	angular.module('flu.techniques', [])
		.controller('TechniquesController', TechniquesController);

	TechniquesController.$inject = ['$scope', '$sce', '$location', '$uibModal', 'sectionFactory', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', 'techniqueFactory', '$q'];
	
	function TechniquesController($scope, $sce, $location, $uibModal, sectionFactory, progressFactory, lockFactory, mediaFactory, modalFactory, techniqueFactory, $q) {
		var vm = this;
		var sectionId = $location.path().substring(1);	//Work out the section ID from the path

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		vm.loading = true;
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details

		setup();
		
		/*if(!techniqueFactory.getLoaded()) {
			techniqueFactory.setLoaded();
			var techniquesPromise = techniqueFactory.loadTechniques();
			var researchTechniquesPromise = techniqueFactory.loadResearchTechniques();
			var usefulPromise = techniqueFactory.loadUsefulTechniques();
			$q.all([techniquesPromise, researchTechniquesPromise, usefulPromise]).then(
				function(result) {
					console.log(result);
					setup();
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		else {
			setup();
		}*/

		//Functions
		function setup() {
			vm.subsections = techniqueFactory.getTechniques(sectionId);
			vm.currentTechniqueIndex = techniqueFactory.getCurrentTechniqueIndex(sectionId);
			if(sectionId === 'revision') {
				//Pass techniques useful to view for revision page
				vm.techniquesUseful = techniqueFactory.getUsefulTechniques();
			}
			
			//Bindable Members - methods
			vm.setSubsection = setSubsection;
			vm.setUsefulTechnique = setUsefulTechnique;
			vm.setVideoTab = setVideoTab;
			vm.complete = complete;	//Dev only, so don't have to click all of the buttons
			
			//Actions
			vm.setSubsection(vm.currentTechniqueIndex);
			if(sectionId === 'research') {	//For research section, only show research techniques
				lockFactory.setComplete(sectionId);
			}
			vm.usefulDisabled = [];
			vm.loading = false;
		}
		
		//Dev only: set section to complete
		function complete() {
			lockFactory.setComplete('revision');
		}
		
		function setSubsection(techniqueIndex) {
			techniqueFactory.setCurrentTechniqueIndex(sectionId, techniqueIndex);
			vm.currentTechniqueIndex = techniqueIndex;
			
			//Youtube videos - need to allow the URL
			var video = vm.subsections[techniqueIndex].video;
			if(angular.isObject(video)) {	//If multiple videos, the URL is allowed when the tab is changed using setVideoTab function below
				vm.currentTechniqueVideo = video;	//Set currentTechniqueVideo to the video object
			}
			else if(angular.isString(video)) {
				vm.currentTechniqueVideo = $sce.trustAsResourceUrl(video);	//Set currentTechniqueVideo to the trusted url
			}
			else {
				vm.currentTechniqueVideo = null;
			}
			
			//Local videos
			/*vm.video = vm.currentTechnique.video;
			if(vm.video) {
				mediaFactory.loadJWPlayer('techniquesPlayer', vm.video)
			}*/
		};
		
		function setUsefulTechnique(techniqueId) {
			vm.usefulDisabled[techniqueId] = true;
			
			var usefulPromise = techniqueFactory.setUsefulTechnique(techniqueId, vm.techniquesUseful[techniqueId]);
			var completePromise = techniqueFactory.setRevisionComplete();
			$q.all([usefulPromise, completePromise]).then(
				function(result) {
					console.log(result);
					vm.usefulDisabled[techniqueId] = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		};
		
		function setVideoTab(url) {
			vm.currentVideoPart = $sce.trustAsResourceUrl(url);
		};
	}
})();