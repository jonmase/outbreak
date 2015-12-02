(function() {
	angular.module('flu.samples', [])
		.controller('SamplesController', SamplesController);

	SamplesController.$inject = ['$scope', '$sce', '$uibModal', 'sectionFactory', 'progressFactory', 'lockFactory', 'siteFactory', 'schoolFactory', 'sampleFactory'];
		
	function SamplesController($scope, $sce, $uibModal, sectionFactory, progressFactory, lockFactory, siteFactory, schoolFactory, sampleFactory) {
		var vm = this;
		var sectionId = 'samples';

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Bindable Members
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = siteFactory.getSites();
		vm.siteIds = siteFactory.getSiteIds();
		vm.currentSiteId = siteFactory.getCurrentSiteId();
		vm.schools = schoolFactory.getSchools();
		vm.samples = sampleFactory.getSamples();
		vm.types = sampleFactory.getSampleTypes();
		vm.happiness = getHappiness();
		
		vm.checkSamples = checkSamples;
		vm.confirmSamples = confirmSamples;
		//vm.modalCancel = modalCancel;
		vm.selectAllOrNone = selectAllOrNone;
		vm.setSubsection = setSubsection;
		
		//Actions
		setSubsection(vm.currentSiteId);
		//For Development, set to complete as soon as you go to the samples page
		//Note that this still gets called even if user is redirected home by checkLock - doesn't really matter, as won't just unlock page on first visit.
		//lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
		
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
				$uibModal.open({
					animation: true,
					size: 'lg',
					templateUrl: '../../partials/modals/samples-modal.html',
					controller: 'SamplesModalController',
					controllerAs: 'SamplesModalCtrl',
				});
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
			sampleFactory.selectAllOrNone(allOrNone, vm.currentSiteId, schoolId, typeId);
			vm.happiness = getHappiness();
		}

		//Set the subsection
		function setSubsection(siteId) {
			siteFactory.setCurrentSiteId(siteId);
			vm.currentSiteId = siteId;
			vm.currentSite = vm.subsections[siteId];
		};
	}
})();