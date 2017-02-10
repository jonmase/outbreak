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
	angular.module('flu.lab')
		.controller('LabController', LabController);

	LabController.$inject = ['$scope', '$sce', '$location', '$uibModal', 'sectionFactory', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', 'techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'resultFactory', 'assayFactory', 'moneyCutoff', 'timeCutoff'];
	
	function LabController($scope, $sce, $location, $uibModal, sectionFactory, progressFactory, lockFactory, mediaFactory, modalFactory, techniqueFactory, siteFactory, schoolFactory, sampleFactory, resultFactory, assayFactory, moneyCutoff, timeCutoff) {
		window.onbeforeunload = null;   //Remove before unload listener (set in ReportController)
        
		var vm = this;
		var sectionId = 'lab';

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		document.body.scrollTop = 0;

		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = techniqueFactory.getTechniques(sectionId);
		vm.currentTechniqueId = assayFactory.getCurrentTechniqueId();
		vm.sites = siteFactory.getSites();
		vm.schools = schoolFactory.getSchools();
		vm.samples = sampleFactory.getSamples();
		vm.types = sampleFactory.getSampleTypes();
		vm.standards = assayFactory.getStandards();
		//vm.results = resultFactory.getResults();
		vm.assays = assayFactory.getAssays();
		vm.requiredTests = assayFactory.getRequiredTests();
		vm.resources = progressFactory.getResources();
		vm.activeTabs = assayFactory.getActiveTabs();
		
		//Bindable Members - methods
		vm.isStandardChecked = isStandardChecked;
		vm.setAssayCount = setAssayCount;
		vm.setSubsection = setSubsection;
		vm.setTab = setTab;
		vm.performAssay = performAssay;
		vm.selectAllOrNoneBySite = selectAllOrNoneBySite;
		vm.selectAllOrNoneByType = selectAllOrNoneByType;
		
		//Actions
		vm.setSubsection(vm.currentTechniqueId);

		//If this is the user's first visit (i.e. lab hasn't been set to complete yet), show the flu alert and set lab to complete
		if(!progressFactory.checkProgress('lab')) {
            $uibModal.open(modalFactory.getFluAlertModalOptions());
			//lockFactory.setComplete('lab');
		}

		//Functions
		function isStandardChecked(standardId) {
			var isChecked = vm.assays.saved.standards[vm.currentTechniqueId][standardId] === 1 || vm.assays.temp.standards[vm.currentTechniqueId][standardId] === 1;
			return isChecked;
			//return true;
		}
		
		//GO ahead and perform the currently selected (temp) assayss
		function performAssay() {
			if(vm.assays.temp.counts[vm.currentTechniqueId].total > 0) {
				//If user has run out of time or money, make them beg for more
				if(vm.resources.money < moneyCutoff || vm.resources.time < timeCutoff) {
					$uibModal.open(modalFactory.getBeggingModalOptions());
				}
				//Show modal with summary of samples to be included in the assay, and cost, and get user to confirm
				else {
					$uibModal.open(modalFactory.getLabModalOptions(vm.currentTechniqueId));
				}
			}
			else {	//No samples have been selected for this technique, so we should not be able to collect samples (button should be disabled anyway)
				alert("Please select some samples and/or standards on which to perform an assay using this technique");
				return false;
			}
		}
		
		function resetActiveTabs(techniqueId) {
			assayFactory.resetActiveTabs(techniqueId);
		}
		
		function selectAllOrNoneBySite(allOrNone, siteId) {
			assayFactory.selectAllOrNoneBySite(allOrNone, vm.currentTechniqueId, siteId);
		}

		function selectAllOrNoneByType(allOrNone, siteId, schoolId, typeId) {
			assayFactory.selectAllOrNoneByType(allOrNone, vm.currentTechniqueId, siteId, schoolId, typeId);
		}

		function setAssayCount() {
			assayFactory.setAssayCount(vm.currentTechniqueId);
		}
		
		function setSubsection(techniqueId) {
			assayFactory.setCurrentTechniqueId(techniqueId);
			vm.currentTechniqueId = techniqueId;
			vm.currentTechnique = vm.subsections[techniqueId];
			//vm.activeTabs[techniqueId] = resetActiveTabs(techniqueId);
			resetActiveTabs(techniqueId);
			vm.currentTechniqueVideo = $sce.trustAsResourceUrl(vm.subsections[techniqueId].video);

			/*vm.video = vm.currentTechnique.video;
			mediaFactory.loadJWPlayer('labPlayer', vm.video);*/
		};
		
		function setTab(techniqueId, tab) {
			assayFactory.setTab(techniqueId, tab);
		}
	}
})();