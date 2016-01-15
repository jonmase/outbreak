(function() {
	angular.module('flu.report')
		.controller('SubmitModalController', SubmitModalController);

	SubmitModalController.$inject = ['$uibModalInstance', '$uibModal', 'modalFactory', 'reportFactory', 'lockFactory'];
	
	function SubmitModalController($uibModalInstance, $uibModal, modalFactory, reportFactory, lockFactory) {
		var vm = this;
		
		//Bindable Members - values
		vm.saving = false;
		//vm.failed = false;

		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		function confirm() {
			reportFactory.setEditorsReadOnly(true);
			//vm.failed = false;
			vm.saving = true;
			
			var reportPromise = reportFactory.save('submit');
			reportPromise.then(
				function(result) {
					console.log(result);
					var completePromise = lockFactory.setComplete('report');
					completePromise.then(
						function(result) {
							console.log(result);
							$uibModalInstance.close();
							vm.saving = false;
						}, 
						function(reason) {
							fail(reason);
						}
					);
				}, 
				function(reason) {
					fail(reason);
				}
			);
		}
		
		function fail() {
			//progressFactory.subtractResources(-moneyCost, -timeCost);	//Add the costs back on to the remaining resources
			//alert("Saving failed, please try again. If you continue to experience problems, please refresh the page and try again. Contact msdlt@medsci.ox.ac.uk if this does not fix it");
			$uibModalInstance.close();
			console.log("Error: " + reason);
			uibModal.open(modalFactory.getErrorModalOptions());
			vm.saving = false;
			//vm.failed = true;
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();