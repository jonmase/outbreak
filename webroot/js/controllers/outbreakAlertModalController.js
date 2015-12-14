(function() {
	angular.module('flu')
		.controller('OutbreakAlertModalController', OutbreakAlertModalController);
	
	OutbreakAlertModalController.$inject = ['$uibModal', '$uibModalInstance', 'lockFactory', 'modalFactory'];

	function OutbreakAlertModalController($uibModal, $uibModalInstance, lockFactory, modalFactory) {
		var vm = this;
		vm.saving = false;

		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function confirm() {
			vm.saving = true;
			var completePromise = lockFactory.setComplete('alert');	//Set start progress to complete
			completePromise.then(
				function(result) {
					console.log(result);
					$uibModalInstance.close();
					vm.saving = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getErrorModalOptions());
					vm.saving = false;
				}
			);
		}
	}
})();