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
		document.body.scrollTop = 0;
		vm.loading = true;

		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = techniqueFactory.getTechniques(sectionId);
		vm.currentTechniqueId = techniqueFactory.getCurrentTechniqueId(sectionId);
		vm.usefulDisabled = [];
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
		vm.setSubsection(vm.currentTechniqueId);
		if(sectionId === 'research' && !progressFactory.checkProgress(sectionId)) {	//For research section, only show research techniques
			var completePromise = lockFactory.setComplete(sectionId);	//Set progress to complete
			completePromise.then(
				function(result) {
					console.log(result);
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		vm.loading = false;

		//Functions
		//Dev only: set section to complete
		function complete() {
			lockFactory.setComplete('revision');
		}
		
		function setSubsection(techniqueId) {
			techniqueFactory.setCurrentTechniqueId(sectionId, techniqueId);
			vm.currentTechniqueId = techniqueId;
			
			//Youtube videos - need to allow the URL
			var video = vm.subsections[techniqueId].video;
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
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);
		};
		
		function setVideoTab(url) {
			vm.currentVideoPart = $sce.trustAsResourceUrl(url);
		};
	}
})();