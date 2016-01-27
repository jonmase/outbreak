(function() {
	angular.module('flu.modals')
		.controller('ErrorModalController', ErrorModalController);
	
	ErrorModalController.$inject = ['$uibModalInstance'];

	function ErrorModalController($uibModalInstance) {
		var vm = this;

		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function confirm() {
			console.log("Reloading page after error");
			window.location.reload();
		}
	}
})();