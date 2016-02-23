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
		.controller('LabModalController', LabModalController);

	LabModalController.$inject = ['$uibModal', '$uibModalInstance', '$q', 'progressFactory', 'lockFactory', 'modalFactory', 'techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'assayFactory', 'resultFactory', 'currentTechniqueId'];
	
	function LabModalController($uibModal, $uibModalInstance, $q, progressFactory, lockFactory, modalFactory, techniqueFactory, siteFactory, schoolFactory, sampleFactory, assayFactory, resultFactory, currentTechniqueId) {
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
		vm.error = false;
		
		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		//Move the temporary assays to the saved assays
		function performAssay() {
		}
		
		function confirm() {
			vm.saving = true;
			vm.error = false;
			
			//Update time and money
			var moneyCost = vm.technique.money * vm.assays.temp.counts[currentTechniqueId].total;
			var timeCost = vm.technique.time;
			
			var assayPromise = assayFactory.setAssays(currentTechniqueId, moneyCost, timeCost);	//Set the assays and save them to the DB, along with the reduced resources
			assayPromise.then(
				function(result) {
					console.log(result);
					var completePromise = assayFactory.setLabComplete(vm.currentTechniqueId);
					//If complete promise is a string, then we don't need to save any progress
					if(angular.isString(completePromise) ) {
						success(moneyCost, timeCost);
						console.log(completePromise);
					}
					else {
						completePromise.then(
							function(result) {
								console.log(result);
								success(moneyCost, timeCost);
								
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
								fail(reason);
							}
						);
					}
				}, 
				function(reason) {
					fail(reason);
				}
			);
		}
		
		function success(moneyCost, timeCost) {
			$uibModalInstance.close();
			assayFactory.setTab(currentTechniqueId, 'results');
			progressFactory.subtractResources(moneyCost, timeCost);	//Subtract the resources (but not API call)
			resultFactory.setDisabledTechniques();
			vm.saving = false;
			vm.error = false;
		}
		
		function fail(reason) {
			//progressFactory.subtractResources(-moneyCost, -timeCost);	//Add the costs back on to the remaining resources
			//alert("Assay failed, please try again. If you continue to experience problems, please refresh the page and try again. Contact msdlt@medsci.ox.ac.uk if this does not fix it");
			console.log("Error: " + reason);
			$uibModalInstance.close();
			$uibModal.open(modalFactory.getErrorModalOptions());
			vm.error = true;
			vm.saving = false;
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();