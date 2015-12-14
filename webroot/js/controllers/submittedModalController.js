(function() {
	angular.module('flu.lab')
		.controller('SubmittedModalController', SubmittedModalController);

	SubmittedModalController.$inject = ['$scope', '$uibModalInstance', '$location', '$controller'];
	
	function SubmittedModalController($scope, $uibModalInstance, $location, $controller) {
		var vm = this;
		
		//Bindable Members - values
		
		//Bindable Members - methods
		vm.goToResearch = goToResearch;
		vm.goToFeedback = goToFeedback;
		vm.cancel = cancel;

		function goToResearch() {
			$uibModalInstance.close();
			$location.path("/research");
		}
		
		function goToFeedback() {
			$uibModalInstance.close();
			$location.path("/feedback");
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();