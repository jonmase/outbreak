(function() {
	angular.module('flu.modals')
		.controller('MarkingLockedModalController', MarkingLockedModalController);
	
	MarkingLockedModalController.$inject = ['$uibModalInstance', '$window'];

	function MarkingLockedModalController($uibModalInstance, $window) {
		var vm = this;

		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function confirm() {
			//console.log("Reloading page to update statuses");
			//$window.location.reload();
			 $uibModalInstance.close();
		}
	}
})();