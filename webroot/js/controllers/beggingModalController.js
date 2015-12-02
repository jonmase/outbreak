(function() {
	angular.module('flu.lab')
		.controller('BeggingModalController', BeggingModalController);

	BeggingModalController.$inject = ['$uibModalInstance', 'progressFactory', 'moneyCutoff', 'timeCutoff'];
	
	function BeggingModalController($uibModalInstance, progressFactory, moneyCutoff, timeCutoff) {
		var vm = this;
		
		//Bindable Members - values
		vm.resources = progressFactory.getResources();
		vm.moneyCutoff = moneyCutoff;
		vm.timeCutoff = timeCutoff;
		if(vm.resources.money < moneyCutoff && vm.resources.time < timeCutoff) {
			vm.resourceText = 'time and money';
			vm.resourceTitle = 'Time and Money';
		}
		else if(vm.resources.time < timeCutoff) {
			vm.resourceText = 'time';
			vm.resourceTitle = 'Time';
		}
		else if(vm.resources.money < moneyCutoff) {
			vm.resourceText = 'money';
			vm.resourceTitle = 'Money';
		}

		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		function confirm() {
			progressFactory.resetResources();
			$uibModalInstance.close();
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();