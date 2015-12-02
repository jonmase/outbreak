(function() {
	angular.module('flu.techniques', [])
		.controller('TechniquesController', TechniquesController);

	TechniquesController.$inject = ['$scope', '$sce', '$location', '$uibModal', 'sectionFactory', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', 'techniqueFactory'];
	
	function TechniquesController($scope, $sce, $location, $uibModal, sectionFactory, progressFactory, lockFactory, mediaFactory, modalFactory, techniqueFactory) {
		var vm = this;
		var sectionId = $location.path().substring(1);	//Work out the section ID from the path

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Bindable Members - values
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = techniqueFactory.getTechniques(sectionId);
		vm.currentTechniqueId = techniqueFactory.getCurrentTechniqueId(sectionId);
		if(sectionId === 'revision') {
			//Pass techniques useful to view for revision page
			vm.techniquesUseful = techniqueFactory.getUsefulTechniques();
		}
		
		//Bindable Members - methods
		vm.setSubsection = setSubsection;
		vm.setUsefulTechnique = setUsefulTechnique;
		vm.setRevisionComplete = setRevisionComplete;
		vm.setVideoTab = setVideoTab;
		vm.complete = complete;	//Dev only, so don't have to click all of the buttons
		
		//Actions
		vm.setSubsection(vm.currentTechniqueId);
		if(sectionId === 'research') {	//For research section, only show research techniques
			lockFactory.setComplete(sectionId);
		}

		//Functions
		//Dev only: set section to complete
		function complete() {
			lockFactory.setComplete('revision');
		}
		
		function setRevisionComplete() {
			techniqueFactory.setRevisionComplete()
		};

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
			techniqueFactory.setUsefulTechnique(techniqueId, vm.techniquesUseful[techniqueId]);
			vm.setRevisionComplete();	//Set the completion status of the revision section
		};
		
		function setVideoTab(url) {
			vm.currentVideoPart = $sce.trustAsResourceUrl(url);
		};
	}
})();