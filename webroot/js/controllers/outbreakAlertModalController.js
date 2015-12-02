(function() {
	angular.module('flu')
		.controller('OutbreakAlertModalController', OutbreakAlertModalController);
	
	OutbreakAlertModalController.$inject = ['$uibModalInstance', 'lockFactory'];

	function OutbreakAlertModalController($uibModalInstance, lockFactory) {
		var vm = this;

		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function confirm() {
			$uibModalInstance.close();
			lockFactory.setComplete('alert');	//Set start progress to complete
		}
	}
})();