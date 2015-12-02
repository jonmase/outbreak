(function() {
	angular.module('flu.lab')
		.controller('LabModalController', LabModalController);

	LabModalController.$inject = ['$uibModal', '$uibModalInstance', 'progressFactory', 'lockFactory', 'techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'assayFactory', 'resultFactory', 'currentTechniqueId'];
	
	function LabModalController($uibModal, $uibModalInstance, progressFactory, lockFactory, techniqueFactory, siteFactory, schoolFactory, sampleFactory, assayFactory, resultFactory, currentTechniqueId) {
		var vm = this;
		var sectionId = 'lab';
		
		//Bindable Members - values
		vm.currentTechniqueId = currentTechniqueId;
		vm.technique = techniqueFactory.getLabTechnique(currentTechniqueId);
		vm.assays = assayFactory.getAssays();
		vm.sites = siteFactory.getSites();
		vm.schools = schoolFactory.getSchools();
		vm.types = sampleFactory.getSampleTypes();
		vm.standards = assayFactory.getStandards();
		vm.resources = progressFactory.getResources();
		
		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		//Move the temporary assays to the saved assays
		function performAssay() {
			//Update time and money
			var moneyCost = vm.technique.money * vm.assays.temp.counts[currentTechniqueId].total;
			var timeCost = vm.technique.time;
			progressFactory.subtractResources(moneyCost, timeCost);
			
			assayFactory.setAssays(currentTechniqueId);
			resultFactory.setDisabledTechniques();
			
			assayFactory.setLabComplete(vm.currentTechniqueId);
			
			//If both H and N have been identified, show modal saying they have sufficient info to write report
			if(progressFactory.checkProgress('hidentified') && progressFactory.checkProgress('nidentified')) {
				$uibModal.open({
					animation: true,
					size: 'lg',
					templateUrl: '../../partials/modals/research-alert-modal.html',
					controller: 'ResearchAlertModalController',
					controllerAs: 'ResearchAlertModalCtrl',
				});
			}
		}
		
		function confirm() {
			performAssay();
			assayFactory.setTab(currentTechniqueId, 'results');
			$uibModalInstance.close();
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();