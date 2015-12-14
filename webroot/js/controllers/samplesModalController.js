(function() {
	angular.module('flu.samples')
		.controller('SamplesModalController', SamplesModalController);

	SamplesModalController.$inject = ['$uibModalInstance', '$uibModal', 'modalFactory', 'progressFactory', 'lockFactory', 'sampleFactory', 'siteFactory'];

	function SamplesModalController($uibModalInstance, $uibModal, modalFactory, progressFactory, lockFactory, sampleFactory, siteFactory) {
		var vm = this;
		var sectionId = 'sampling';
		vm.confirm = confirm;
		vm.cancel = cancel;
		vm.samples = sampleFactory.getSamples();
		vm.siteIds = siteFactory.getSiteIds();
		vm.saving = false;
		
		//Moved the temporary samples to the saved samples
		function confirm() {
			vm.saving = true;
			
			var samplesPromise = sampleFactory.setSamples();
			samplesPromise.then(
				function(result) {
					console.log(result);
					//Only need to set progress locally - progress is saved to DB as part of samples saving
					if(!progressFactory.checkProgress(sectionId)) {
						lockFactory.setComplete(sectionId, false);	//Set the progress for this section to complete, but don't save to DB
					}
					$uibModalInstance.close();
					vm.saving = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getErrorModalOptions());
					vm.saving = false;
				}
			);
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();