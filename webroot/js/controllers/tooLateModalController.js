(function() {
	angular.module('flu.samples')
		.controller('tooLateModalController', tooLateModalController);

	tooLateModalController.$inject = ['$uibModalInstance'];
	
	function tooLateModalController($uibModalInstance) {
		var vm = this;
		
		//Bindable Members - values

		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		function confirm() {
			$uibModalInstance.close();
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();