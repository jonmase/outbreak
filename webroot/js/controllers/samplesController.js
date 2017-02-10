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
	angular.module('flu.samples')
		.controller('SamplesController', SamplesController);

	SamplesController.$inject = ['$scope', '$sce', '$uibModal', 'sectionFactory', 'progressFactory', 'lockFactory', 'modalFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', '$q'];
		
	function SamplesController($scope, $sce, $uibModal, sectionFactory, progressFactory, lockFactory, modalFactory, siteFactory, schoolFactory, sampleFactory, $q) {
		window.onbeforeunload = null;   //Remove before unload listener (set in ReportController)
        
		var vm = this;
		var sectionId = 'sampling';

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		document.body.scrollTop = 0;
		vm.loading = true;
		
		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = siteFactory.getSites();
		vm.siteIds = siteFactory.getSiteIds();
		vm.currentSiteIndex = siteFactory.getCurrentSiteIndex();
		vm.schools = schoolFactory.getSchools();
		vm.samples = sampleFactory.getSamples();
		vm.types = sampleFactory.getSampleTypes();
		vm.happiness = getHappiness();
		
		//Bindable Members - methods
		vm.checkSamples = checkSamples;
		vm.confirmSamples = confirmSamples;
		//vm.modalCancel = modalCancel;
		vm.selectAllOrNone = selectAllOrNone;
		vm.setSubsection = setSubsection;
		
		//Actions
		setSubsection(vm.currentSiteIndex);
		//For Development, set to complete as soon as you go to the samples page
		//Note that this still gets called even if user is redirected home by checkLock - doesn't really matter, as won't just unlock page on first visit.
		//lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
		vm.loading = false;
		
		//Functions
		//When user clicks a sample, check whether the sample is available, show any appropriate advice/warnings, and update happiness
		function checkSamples(siteId, schoolId, childId, typeId) {
			vm.samples = sampleFactory.checkSamples(siteId, schoolId, childId, typeId);
			vm.happiness = getHappiness();
		};

		//Show modal with details of samples being taken, any necessary warnings, and confirm/cancel buttons
		//Uses Angular UI Botstrap modal component: https://angular-ui.github.io/bootstrap/
		function confirmSamples() {
			if(vm.samples.temp.counts.total > 0) {
                $uibModal.open(modalFactory.getSamplesModalOptions());
			}
			else {	//No samples have been selected, so we should not be able to collect samples (button should be disabled anyway)
				alert("Please select some samples to collect");
				return false;
			}
			
			//OLD Modal Factory setters
			//modalFactory.setTitle('Sample Title');
			//modalFactory.setContent('<p>Get the samples that the user is about to take</p><selected-samples></selected-samples>');
			//modalFactory.setTemplate('../../partials/elements/selected-samples.html');
			//modalFactory.setWidth('50%');
			//modalFactory.setClosable(true);
			
			//OLD Modal Factory launcher
			/*modalFactory.showModal({
				backdrop: 'static',
			});*/
		}
		
		function getHappiness() {
			return sampleFactory.getHappiness();
		}
		
		/*function modalCancel() {
			alert('closing modal');
			vm.modalInstance.dismiss('cancel');
		}*/
		
		function selectAllOrNone(allOrNone, schoolId, typeId) {
			sampleFactory.selectAllOrNone(allOrNone, vm.currentSiteIndex, schoolId, typeId);
			vm.happiness = getHappiness();
		}

		//Set the subsection
		function setSubsection(siteIndex) {
			siteFactory.setCurrentSiteIndex(siteIndex);
			vm.currentSiteIndex = siteIndex;
			vm.currentSite = vm.subsections[siteIndex];
		};
	}
})();