(function() {
	angular.module('flu.techniques')
		.controller('FluAlertModalController', FluAlertModalController);

	FluAlertModalController.$inject = ['$uibModalInstance', 'lockFactory', 'sampleFactory'];

	function FluAlertModalController($uibModalInstance, lockFactory, sampleFactory) {
		var vm = this;
		
		//Bindable Members
		vm.acuteSwabSamplesCollected = areAcuteSwabSamplesCollected();
		
		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function areAcuteSwabSamplesCollected() {
			return sampleFactory.getAcuteSwabSamplesCollected();
		}
		
		function confirm() {
			$uibModalInstance.close();
			//Set all acute swab samples as collected
			sampleFactory.collectAllAcuteSwabSamples();
			lockFactory.setComplete('lab');	//Set lab progress to complete
		}
	}
})();