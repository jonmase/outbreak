(function() {
	angular.module('flu')
		.controller('MainController', MainController);

	//MainController.$inject = ['$scope', '$location', '$uibModal', '$q', 'sectionsConstant', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', 'techniqueFactory', 'questionFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'assayFactory', 'resultFactory', 'reportFactory'];
	MainController.$inject = ['$scope', '$location', '$uibModal', '$q', 'sectionsConstant', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', 'techniqueFactory', 'questionFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'assayFactory', 'resultFactory'];
	
	//function MainController($scope, $location, $uibModal, $q, sectionsConstant, progressFactory, lockFactory, mediaFactory, modalFactory, techniqueFactory, questionFactory, siteFactory, schoolFactory, sampleFactory, assayFactory, resultFactory, reportFactory) {
	function MainController($scope, $location, $uibModal, $q, sectionsConstant, progressFactory, lockFactory, mediaFactory, modalFactory, techniqueFactory, questionFactory, siteFactory, schoolFactory, sampleFactory, assayFactory, resultFactory) {
		var vm = this;
		vm.loading = true;
		
		//Bindable Members
		$location.path( "/home" );
		vm.sections = sectionsConstant();
		$scope.currentSectionId = getSectionFromPath();

		//Actions
		var progressPromise = progressFactory.loadProgress();
		var resourcePromise = progressFactory.loadResources();
		
		var techniquesPromise = techniqueFactory.loadTechniques();
		var researchTechniquesPromise = techniqueFactory.loadResearchTechniques();
		var usefulPromise = techniqueFactory.loadUsefulTechniques();
		
		var questionsPromise = questionFactory.loadQuestions();
		var responsesPromise = questionFactory.loadResponses();

		var sitesPromise = siteFactory.loadSites();
		var schoolsPromise = schoolFactory.loadSchools();
		var typesPromise = sampleFactory.loadTypes();
		var samplesPromise = sampleFactory.loadSamples();
		var happinessPromise = sampleFactory.loadHappiness();
		
		var assaysPromise = assayFactory.loadAssays();
		var standardsPromise = assayFactory.loadStandards();
		var standardAssaysPromise = assayFactory.loadStandardAssays();
		
		var notesPromise = resultFactory.loadNotes();
		
		$q.all([progressPromise, resourcePromise, techniquesPromise, researchTechniquesPromise, usefulPromise, questionsPromise, responsesPromise, sitesPromise, schoolsPromise, typesPromise, samplesPromise, happinessPromise, assaysPromise, standardsPromise, standardAssaysPromise, notesPromise]).then(
			function(result) {
				console.log(result);
				vm.progress = progressFactory.getProgress();
				vm.resources = progressFactory.getResources();
				sampleFactory.setup();
				assayFactory.setup();
				resultFactory.setup();
				vm.locks = lockFactory.setLocks();
				vm.checkLockOnClick = checkLockOnClick;
				vm.loading = false;
				
				//If user has not 'started' (i.e. clicked start after the intro video), show the intro video
				if(!progressFactory.checkProgress('start')) {
					$uibModal.open(modalFactory.getIntroModalOptions());
				}
				else if(!progressFactory.checkProgress('alert')) {
					$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
				}
			}, 
			function(reason) {
				console.log("Error: " + reason);
			}
		);
		
		function checkLockOnClick(sectionId) {
			if(lockFactory.checkLockOnClick(sectionId)) {
				$scope.currentSectionId = getSectionFromPath();
			}
		}
		
		function getSectionFromPath() {
			var sectionId = $location.path().substring(1);
			return sectionId;
		}
		
		function alertTest() {
			alert("called alertTest");
		}
	}
})();