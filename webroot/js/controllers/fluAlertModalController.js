(function() {
	angular.module('flu.techniques')
		.controller('FluAlertModalController', FluAlertModalController);

	FluAlertModalController.$inject = ['$uibModalInstance', '$q', 'lockFactory', 'sampleFactory'];

	function FluAlertModalController($uibModalInstance, $q, lockFactory, sampleFactory) {
		var vm = this;
		
		//Bindable Members
		vm.acuteSwabSamplesCollected = areAcuteSwabSamplesCollected();
		vm.saving = false;
		
		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function areAcuteSwabSamplesCollected() {
			return sampleFactory.getAcuteSwabSamplesCollected();
		}
		
		function confirm() {
			vm.saving = true;
			//Set all acute swab samples as collected, and mark lab section as complete
			var collectPromise = sampleFactory.collectAllAcuteSwabSamples();
			var completePromise = lockFactory.setComplete('lab');	//Set lab progress to complete
			$q.all([collectPromise, completePromise]).then(
				function(result) {
					console.log(result);
					$uibModalInstance.close();
					vm.saving = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
	}
})();