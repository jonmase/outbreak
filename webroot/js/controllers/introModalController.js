(function() {
	angular.module('flu')
		.controller('IntroModalController', IntroModalController);

	IntroModalController.$inject = ['$sce', '$uibModal', '$uibModalInstance', 'lockFactory', 'mediaFactory', 'modalFactory'];
	
	function IntroModalController($sce, $uibModal, $uibModalInstance, lockFactory, mediaFactory, modalFactory) {
		var vm = this;
		vm.saving = false;
		
		//vm.loadVideo = loadVideo;
		vm.introVideo = $sce.trustAsResourceUrl(mediaFactory.getIntroVideo());
		vm.start = start;
		
		/*function loadVideo() {
			mediaFactory.loadJWPlayer('introModalPlayer', mediaFactory.getIntroVideo()); 
		}*/
		
		function start() {
			vm.saving = true;
			var completePromise = lockFactory.setComplete('start');	//Set start progress to complete
			completePromise.then(
				function(result) {
					console.log(result);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
					vm.saving = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getErrorModalOptions());
					vm.saving = false;
				}
			);
		};
	}
})();