(function() {
	angular.module('flu.results')
		.controller('ResultsController', ResultsController);

	ResultsController.$inject = ['$scope', '$sce', '$uibModal', 'modalFactory', 'sectionFactory', 'progressFactory', 'lockFactory', 'techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'resultFactory', 'assayFactory'];
		
	function ResultsController($scope, $sce, $uibModal, modalFactory, sectionFactory, progressFactory, lockFactory, techniqueFactory, siteFactory, schoolFactory, sampleFactory, resultFactory, assayFactory) {
		var vm = this;
		var sectionId = 'results';

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		document.body.scrollTop = 0;

		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = resultFactory.getSections();
		vm.currentTechniqueId = resultFactory.getCurrentTechniqueId();
		vm.progress = progressFactory.getProgress();
		vm.sites = siteFactory.getSites();
		vm.schools = schoolFactory.getSchools();
		vm.samples = sampleFactory.getSamples();
		//vm.sampleCounts = sampleFactory.getSampleCounts();
		vm.types = sampleFactory.getSampleTypes();
		vm.standards = assayFactory.getStandards();
		//vm.results = resultFactory.getResults();
		vm.notes = resultFactory.getNotes();
		vm.assays = assayFactory.getAssays();
		vm.requiredTests = assayFactory.getRequiredTests();
		
		//Bindable Members - methods
		vm.setNote = setNote;
		vm.setSubsection = setSubsection;
		//Actions
		//vm.setSubsection(initialTechnique);
		//resultFactory.setDisabledTechniques();
		//resultFactory.setCurrentTechniqueId(vm.currentTechniqueId);
		
		//Functions
		function setNote(techniqueId) {
			var notesPromise = resultFactory.setNote(techniqueId);	//Set the assays and save them to the DB, along with the reduced resources
			notesPromise.then(
				function(result) {
					console.log(result);
				}, 
				function(reason) {
					console.log("Error:" + reason);
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);
		}
		
		function setSubsection(techniqueId) {
			if(!vm.subsections[techniqueId].disabled) {
				resultFactory.setCurrentTechniqueId(techniqueId);
				vm.currentTechniqueId = resultFactory.getCurrentTechniqueId();	//Shouldn't be necessary, but doesn't work without this
				//vm.currentTechnique = vm.subsections[techniqueId];
			}
		};
	}
})();