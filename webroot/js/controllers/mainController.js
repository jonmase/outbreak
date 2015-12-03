(function() {
	angular.module('flu')
		.controller('MainController', MainController);

	MainController.$inject = ['$scope', '$location', '$uibModal', 'sectionsConstant', 'progressFactory', 'lockFactory', 'mediaFactory', 'modalFactory', '$q'];
	
	function MainController($scope, $location, $uibModal, sectionsConstant, progressFactory, lockFactory, mediaFactory, modalFactory, $q) {
		var vm = this;
		vm.loading = true;
		
		//Bindable Members
		vm.sections = sectionsConstant();

		//Actions
		var progressPromise = progressFactory.loadProgress();
		var resourcePromise = progressFactory.loadResources();
		$q.all([progressPromise, resourcePromise]).then(
			function(result) {
				console.log(result);
				$scope.currentSectionId = getSectionFromPath();
				vm.progress = progressFactory.getProgress();
				vm.resources = progressFactory.getResources();
				vm.locks = lockFactory.setLocks();
				vm.checkLockOnClick = checkLockOnClick;
				vm.loading = false;
				
				//If user has not 'started' (i.e. clicked start after the intro video), show the intro video
				if(!progressFactory.checkProgress('start')) {
					$uibModal.open(modalFactory.getIntroModalOptions());
				}
				else if(!progressFactory.checkProgress('alert')) {
					$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
				}
			}, 
			function(reason) {
				console.log("Error: " + reason);
			}
		);
		
		function checkLockOnClick(sectionId) {
			if(lockFactory.checkLockOnClick(sectionId)) {
				$scope.currentSectionId = getSectionFromPath();
			}
		}
		
		function getSectionFromPath() {
			var sectionId = $location.path().substring(1);
			return sectionId;
		}
		
		function alertTest() {
			alert("called alertTest");
		}
	}
})();