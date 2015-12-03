(function() {
	angular.module('flu')
		.controller('HomeController', HomeController);

	HomeController.$inject = ['$scope', '$sce', 'mediaFactory', 'progressFactory', 'sampleFactory'];
	
	function HomeController($scope, $sce, mediaFactory, progressFactory, sampleFactory) {
		var vm = this;
		$scope.$parent.$parent.currentSectionId = 'home';	//Make sure the section ID is set correctly in Main Controller (Main Controller is grandparent of this one, rather than parent - not sure why)
		
		//Bindable Members
		vm.introVideo = $sce.trustAsResourceUrl(mediaFactory.getIntroVideo());
		vm.acuteSwabSamplesCollected = areAcuteSwabSamplesCollected();
		
		//Actions
		//mediaFactory.loadJWPlayer('homePlayer', mediaFactory.getIntroVideo());
		
		function areAcuteSwabSamplesCollected() {
			return sampleFactory.getAcuteSwabSamplesCollected();
		}
		
	}
})();