(function() {
	angular.module('flu.lab')
		.controller('LabModalController', LabModalController);

	LabModalController.$inject = ['$uibModal', '$uibModalInstance', '$q', 'progressFactory', 'lockFactory', 'techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'assayFactory', 'resultFactory', 'currentTechniqueId'];
	
	function LabModalController($uibModal, $uibModalInstance, $q, progressFactory, lockFactory, techniqueFactory, siteFactory, schoolFactory, sampleFactory, assayFactory, resultFactory, currentTechniqueId) {
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
		vm.saving = false;
		
		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		//Move the temporary assays to the saved assays
		function performAssay() {
		}
		
		function confirm() {
			vm.saving = true;
			
			//Update time and money
			var moneyCost = vm.technique.money * vm.assays.temp.counts[currentTechniqueId].total;
			var timeCost = vm.technique.time;
			progressFactory.subtractResources(moneyCost, timeCost);	//Subtract the resources (but not API call)
			
			var assayPromise = assayFactory.setAssays(currentTechniqueId);	//Set the assays and save them to the DB, along with the reduced resources
			$q.all([assayPromise]).then(
				function(result) {
					console.log(result);
					var completePromise = assayFactory.setLabComplete(vm.currentTechniqueId);
					if(completePromise) {	//If complete promise is not false, then all questions have been completed and we need to wait for progress to be saved
						completePromise.then(
							function(result) {
								console.log(result);
								success();
								
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
							}, 
							function(reason) {
								console.log("Error: " + reason);
							}
						);
					}
					else {
						success();
					}
				}, 
				function(reason) {
					fail(reason);
				}
			);
		}
		
		function success() {
			resultFactory.setDisabledTechniques();
			assayFactory.setTab(currentTechniqueId, 'results');
			$uibModalInstance.close();
			vm.saving = false;
		}
		
		function fail() {
			progressFactory.subtractResources(-moneyCost, -timeCost);	//Add the costs back on to the remaining resources
			alert("Assay failed, please try again. If you continue to experience problems, please refresh the page and try again. Contact msdlt@medsci.ox.ac.uk if this does not fix it");
			console.log("Error: " + reason);
			vm.saving = false;
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();