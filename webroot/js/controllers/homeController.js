(function() {
	angular.module('flu')
		.controller('HomeController', HomeController);

	HomeController.$inject = ['$scope', '$sce', '$uibModal', 'mediaFactory', 'progressFactory', 'sampleFactory', 'modalFactory'];
	
	function HomeController($scope, $sce, $uibModal, mediaFactory, progressFactory, sampleFactory, modalFactory) {
		var vm = this;
		$scope.$parent.$parent.currentSectionId = 'home';	//Make sure the section ID is set correctly in Main Controller (Main Controller is grandparent of this one, rather than parent - not sure why)
		
		//Bindable Members
		vm.introVideo = $sce.trustAsResourceUrl(mediaFactory.getIntroVideo());
		vm.acuteSwabSamplesCollected = areAcuteSwabSamplesCollected();
		
		//Actions
		//mediaFactory.loadJWPlayer('homePlayer', mediaFactory.getIntroVideo());
		
		//If user has not 'started' (i.e. clicked start after the intro video), show the intro video
		if(!progressFactory.checkProgress('start')) {
			$uibModal.open(modalFactory.getIntroModalOptions());
		}
		else if(!progressFactory.checkProgress('alert')) {
			$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
		}
	
		function areAcuteSwabSamplesCollected() {
			return sampleFactory.getAcuteSwabSamplesCollected();
		}
		
	}
})();