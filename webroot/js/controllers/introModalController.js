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
			lockFactory.setComplete('start');	//Set start progress to complete
			
			$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
		};
	}
})();