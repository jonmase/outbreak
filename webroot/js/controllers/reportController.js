(function() {
	angular.module('flu.report', [])
		.controller('ReportController', ReportController);

	ReportController.$inject = ['$scope', '$sce', '$uibModal', 'dateFilter', 'sectionFactory', 'lockFactory', 'reportFactory', 'resultFactory', 'techniqueFactory'];
		
	function ReportController($scope, $sce, $uibModal, dateFilter, sectionFactory, lockFactory, reportFactory, resultFactory, techniqueFactory) {
		var vm = this;
		var sectionId = 'report';
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			//For Development, set to complete as soon as you go to the report page
			//lockFactory.setComplete(sectionId);
			return false;
		}
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Bindable Members - values
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.boxes = reportFactory.getBoxes();
		vm.date = reportFactory.getDate();
		vm.reportxyz = reportFactory.getReport();
		vm.testabcd = reportFactory.getTest();
		vm.lastSaved = reportFactory.getLastSaved();
		vm.submitted = reportFactory.getSubmitted();
		vm.notes = resultFactory.getNotes();
		//vm.firstNote = reportFactory.getFirstNote();
		vm.allNotesEmpty = reportFactory.getAllNotesEmpty();
		vm.techniques = reportFactory.getNoteTechniques();
		//Get the CKEditor Options
		//This only seems to work (using the ng-ckeditor plugin) using scope, not with vm and 'controller as' syntax
		//The editors will be set to readOnly mode if vm.submitted is true
		$scope.editorOptions = reportFactory.getEditorOptions(vm.submitted);	
		
		//Bindable Members - methods
		vm.save = save;
		vm.submit = submit;
		
		//Actions
		

		//Functions
		function save() {
			var reportPromise = reportFactory.save('save');
			reportPromise.then(
				function(result) {
					console.log(result);
					vm.lastSaved = reportFactory.getLastSaved();
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		
		function submit() {
			var modalInstance = $uibModal.open({
				animation: true,
				size: 'md',
				backdrop: 'static',
				templateUrl: '../../partials/modals/submit-modal.html',
				controller: 'SubmitModalController',
				controllerAs: 'SubmitModalCtrl',
			});
			
			modalInstance.result.then(function () {
				vm.lastSaved = reportFactory.getLastSaved();
				vm.submitted = reportFactory.getSubmitted();
			});
		}
	}
})();