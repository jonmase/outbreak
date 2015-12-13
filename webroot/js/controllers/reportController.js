(function() {
	angular.module('flu.report', [])
		.controller('ReportController', ReportController);

	ReportController.$inject = ['$scope', '$sce', '$uibModal', '$timeout', 'dateFilter', 'sectionFactory', 'lockFactory', 'reportFactory', 'resultFactory', 'techniqueFactory'];
		
	function ReportController($scope, $sce, $uibModal, $timeout, dateFilter, sectionFactory, lockFactory, reportFactory, resultFactory, techniqueFactory) {
		var vm = this;
		var sectionId = 'report';
		var autoSaveTimeout;
		
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
		vm.report = reportFactory.getReport();
		vm.lastSaved = reportFactory.getLastSaved();
		vm.lastSaveType = reportFactory.getLastSaveType();
		vm.submitted = reportFactory.getSubmitted();
		vm.notes = resultFactory.getNotes();
		//vm.firstNote = reportFactory.getFirstNote();
		vm.allNotesEmpty = reportFactory.getAllNotesEmpty();
		vm.techniques = reportFactory.getNoteTechniques();
		//Get the CKEditor Options
		//This only seems to work (using the ng-ckeditor plugin) using scope, not with vm and 'controller as' syntax
		//The editors will be set to readOnly mode if vm.submitted is true
		$scope.editorOptions = reportFactory.getEditorOptions(vm.submitted);	
		vm.saving = false;
		
		//Bindable Members - methods
		vm.save = save;
		vm.submit = submit;
		
		
		
		//Actions
		//If not already submitted, set up saving
		if(!vm.submitted) {
			//Initialise autosave
			setAutosaveTimeout();
			// Save, and cancel autosave timeout, when leaving page
			$scope.$on("$destroy", function() { 
				cancelAutosaveTimeout();
				if(!vm.submitted) {
					reportFactory.save('leave');
				}
			});
		}
		
		//Functions
		function autosave() {
			var reportPromise = reportFactory.save('auto');
			reportPromise.then(
				function(result) {
					console.log(result);
					vm.lastSaved = reportFactory.getLastSaved();
					vm.lastSaveType = reportFactory.getLastSaveType();
					setAutosaveTimeout();
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		
		function cancelAutosaveTimeout() {
			$timeout.cancel(autoSaveTimeout);
		}
		
		function setAutosaveTimeout() {
			autoSaveTimeout = $timeout(
				function() { autosave() },
				60000	//every minute
			);
		}
		
		function save() {
			if(vm.submitted) {
				console.log('Report already saved');
				return false;
			}
			reportFactory.setEditorsReadOnly(true);
			vm.saving = true;
			if(autoSaveTimeout) {
				cancelAutosaveTimeout();
			}
			var reportPromise = reportFactory.save('save');
			reportPromise.then(
				function(result) {
					console.log(result);
					vm.lastSaved = reportFactory.getLastSaved();
					vm.lastSaveType = reportFactory.getLastSaveType();
					vm.saving = false;
					reportFactory.setEditorsReadOnly(false);
					setAutosaveTimeout();
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		
		function submit() {
			if(vm.submitted) {
				console.log('Report already saved');
				return false;
			}
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
				vm.lastSaveType = reportFactory.getLastSaveType();
				vm.submitted = reportFactory.getSubmitted();
			});
		}
	}
})();