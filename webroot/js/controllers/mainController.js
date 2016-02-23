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
	angular.module('flu')
		.controller('MainController', MainController);

	MainController.$inject = ['$scope', '$location', '$uibModal', '$q', '$resource', 'sectionsConstant', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', 'techniqueFactory', 'questionFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'assayFactory', 'resultFactory', 'reportFactory'];
	
	function MainController($scope, $location, $uibModal, $q, $resource, sectionsConstant, progressFactory, lockFactory, mediaFactory, modalFactory, techniqueFactory, questionFactory, siteFactory, schoolFactory, sampleFactory, assayFactory, resultFactory, reportFactory) {
		var vm = this;
		vm.loading = true;
		
		$location.path( "/home" );
		document.body.scrollTop = 0;
		
		//Bindable Members
		vm.oxford = OXFORD_VERSION;
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
		var reportPromise = reportFactory.loadReport();
		
		$q.all([progressPromise, resourcePromise, techniquesPromise, researchTechniquesPromise, usefulPromise, questionsPromise, responsesPromise, sitesPromise, schoolsPromise, typesPromise, samplesPromise, happinessPromise, assaysPromise, standardsPromise, standardAssaysPromise, notesPromise, reportPromise]).then(
		//$q.all([progressPromise, resourcePromise]).then(
		//progressPromise.then(
		//loadPromise.then(
			function(result) {
				console.log(result);
				vm.progress = progressFactory.getProgress();
				vm.resources = progressFactory.getResources();
				sampleFactory.setup();
				assayFactory.setup();
				resultFactory.setup();
				reportFactory.setup();
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
				$uibModal.open(modalFactory.getErrorModalOptions());
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