(function() {
	angular.module('flu')
		.controller('InfoController', InfoController);

	InfoController.$inject = ['$scope', 'sectionFactory'];
	function InfoController($scope, sectionFactory) {
		var vm = this;
		var sectionId = 'info';
		
		//Bindable Members
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Actions
	}
})();