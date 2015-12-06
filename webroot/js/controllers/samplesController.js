(function() {
	angular.module('flu.samples', [])
		.controller('SamplesController', SamplesController);

	SamplesController.$inject = ['$scope', '$sce', '$uibModal', 'sectionFactory', 'progressFactory', 'lockFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', '$q'];
		
	function SamplesController($scope, $sce, $uibModal, sectionFactory, progressFactory, lockFactory, siteFactory, schoolFactory, sampleFactory, $q) {
		var vm = this;
		var sectionId = 'samples';

		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		vm.loading = true;
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details

		if(!sampleFactory.getLoaded()) {
			var sitesPromise = siteFactory.loadSites();
			var schoolsPromise = schoolFactory.loadSchools();
			var typesPromise = sampleFactory.loadTypes();
			var samplesPromise = sampleFactory.loadSamples();
			var happinessPromise = sampleFactory.loadHappiness();
			$q.all([sitesPromise, schoolsPromise, typesPromise, samplesPromise, happinessPromise]).then(
				function(result) {
					sampleFactory.setup();
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
		}
		
		function setup() {
			//Bindable Members
			vm.subsections = siteFactory.getSites();
			vm.siteIds = siteFactory.getSiteIds();
			vm.currentSiteIndex = siteFactory.getCurrentSiteIndex();
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
			setSubsection(vm.currentSiteIndex);
			//For Development, set to complete as soon as you go to the samples page
			//Note that this still gets called even if user is redirected home by checkLock - doesn't really matter, as won't just unlock page on first visit.
			//lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
			vm.loading = false;
		}
		
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