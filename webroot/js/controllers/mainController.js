(function() {
	angular.module('flu')
		.controller('MainController', MainController);

	MainController.$inject = ['$scope', '$location', 'sectionsConstant', 'progressFactory', 'lockFactory', 'mediaFactory', '$q'];
	
	function MainController($scope, $location, sectionsConstant, progressFactory, lockFactory, mediaFactory, $q) {
		var vm = this;
		$scope.loading = true;
		
		//Bindable Members
		vm.sections = sectionsConstant();

		//Actions
		var progressPromise = progressFactory.readProgress();
		var resourcePromise = progressFactory.readResources();
		$q.all([progressPromise, resourcePromise]).then(
			function(result) {
				console.log(result);
				$scope.currentSectionId = getSectionFromPath();
				vm.progress = progressFactory.getProgress();
				vm.resources = progressFactory.getResources();
				vm.locks = lockFactory.setLocks();
				vm.checkLockOnClick = checkLockOnClick;
				$scope.loading = false;
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