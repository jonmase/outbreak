(function() {
	angular.module('flu.samples')
		.controller('SamplesModalController', SamplesModalController);

	SamplesModalController.$inject = ['$uibModalInstance', 'progressFactory', 'lockFactory', 'sampleFactory'];

	function SamplesModalController($uibModalInstance, progressFactory, lockFactory, sampleFactory) {
		var vm = this;
		var sectionId = 'samples';
		vm.confirm = confirm;
		vm.cancel = cancel;
		vm.samples = sampleFactory.getSamples();
		
		//Moved the temporary samples to the saved samples
		function collectSamples() {
			sampleFactory.setSamples();
			if(!progressFactory.checkProgress(sectionId)) {
				lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
			}
		}
		
		function confirm() {
			$uibModalInstance.close();
			collectSamples();
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();