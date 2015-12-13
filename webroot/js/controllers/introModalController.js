(function() {
	angular.module('flu')
		.controller('IntroModalController', IntroModalController);

	IntroModalController.$inject = ['$sce', '$uibModal', '$uibModalInstance', 'lockFactory', 'mediaFactory', 'modalFactory'];
	
	function IntroModalController($sce, $uibModal, $uibModalInstance, lockFactory, mediaFactory, modalFactory) {
		var vm = this;
		
		//vm.loadVideo = loadVideo;
		vm.introVideo = $sce.trustAsResourceUrl(mediaFactory.getIntroVideo());
		vm.start = start;
		
		/*function loadVideo() {
			mediaFactory.loadJWPlayer('introModalPlayer', mediaFactory.getIntroVideo()); 
		}*/
		
		function start() {
			$uibModalInstance.close();
			var completePromise = lockFactory.setComplete('start');	//Set start progress to complete
			completePromise.then(
				function(result) {
					console.log(result);
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
			
			$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
		};
	}
})();