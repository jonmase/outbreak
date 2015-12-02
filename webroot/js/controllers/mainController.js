(function() {
	angular.module('flu')
		.controller('MainController', MainController);

	MainController.$inject = ['$scope', '$location', 'sectionsConstant', 'progressFactory', 'lockFactory', 'mediaFactory'];
	
	function MainController($scope, $location, sectionsConstant, progressFactory, lockFactory, mediaFactory) {
		var vm = this;
		$scope.currentSectionId = getSectionFromPath();
		
		//Bindable Members
		vm.resources = progressFactory.getResources();
		vm.sections = sectionsConstant();
		vm.progress = progressFactory.getProgress();
		vm.locks = lockFactory.setLocks();
		vm.showProgress = false;
		
		vm.checkLockOnClick = checkLockOnClick;
		
		function checkLockOnClick(sectionId) {
			if(lockFactory.checkLockOnClick(sectionId)) {
				//alert("section changed");
				$scope.currentSectionId = getSectionFromPath();
			}
		}
		//vm.modal = modalFactory.getModal();
		
		function getSectionFromPath() {
			var sectionId = $location.path().substring(1);
			return sectionId;
		}
		
		function alertTest() {
			alert("called alertTest");
		}
	}
})();