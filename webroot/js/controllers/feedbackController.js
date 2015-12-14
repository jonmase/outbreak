(function() {
	angular.module('flu')
		.controller('FeedbackController', FeedbackController);

	FeedbackController.$inject = ['$scope', 'sectionFactory', 'lockFactory'];
	function FeedbackController($scope, sectionFactory, lockFactory) {
		var vm = this;
		var sectionId = 'feedback';
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Bindable Members
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		
		//Actions
	}
})();