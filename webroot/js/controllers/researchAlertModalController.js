(function() {
	angular.module('flu.lab')
		.controller('ResearchAlertModalController', ResearchAlertModalController);

	ResearchAlertModalController.$inject = ['$scope', '$uibModalInstance', '$location', '$controller'];
	
	function ResearchAlertModalController($scope, $uibModalInstance, $location, $controller) {
		var vm = this;
		
		//Bindable Members - values
		
		//Bindable Members - methods
		vm.goToReport = goToReport;
		vm.goToResearch = goToResearch;
		vm.goToResults = goToResults;
		vm.cancel = cancel;

		function goToReport() {
			$uibModalInstance.close();
			$location.path("/report");
		}
		
		function goToResearch() {
			$uibModalInstance.close();
			$location.path("/research");
		}
		
		function goToResults() {
			$uibModalInstance.close();
			$location.path("/results");
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();