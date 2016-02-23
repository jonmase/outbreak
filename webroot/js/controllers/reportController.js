/**
    Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/

(function() {
	angular.module('flu.report')
		.controller('ReportController', ReportController);

	ReportController.$inject = ['$scope', '$sce', '$uibModal', '$timeout', 'dateFilter', 'sectionFactory', 'lockFactory', 'modalFactory', 'reportFactory', 'resultFactory', 'techniqueFactory'];
		
	function ReportController($scope, $sce, $uibModal, $timeout, dateFilter, sectionFactory, lockFactory, modalFactory, reportFactory, resultFactory, techniqueFactory) {
		var vm = this;
		var sectionId = 'report';
		var autoSaveTimeout;
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			//For Development, set to complete as soon as you go to the report page
			//lockFactory.setComplete(sectionId);
			return false;
		}
		document.body.scrollTop = 0;

		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
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
		vm.reopen = reopen;
		
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
					$uibModal.open(modalFactory.getErrorModalOptions());
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
					$uibModal.open(modalFactory.getErrorModalOptions());
					vm.saving = false;
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
				if(autoSaveTimeout) {
					cancelAutosaveTimeout();
				}
				if(vm.submitted) {
					$uibModal.open({
						animation: true,
						size: 'md',
						backdrop: 'static',
						templateUrl: '../../partials/modals/submitted-modal.html',
						controller: 'SubmittedModalController',
						controllerAs: 'SubmittedModalCtrl',
					});
				}
			});
		}
		
		function reopen() {
			if(!vm.submitted) {
				console.log('Report not submitted');
				return false;
			}
			vm.saving = true;
			var reportPromise = reportFactory.reopen();
			reportPromise.then(
				function(result) {
					console.log(result);
					var completePromise = lockFactory.setIncomplete('report', true, true);
					completePromise.then(
						function(result) {
							console.log(result);
							reportFactory.setEditorsReadOnly(false);
							vm.submitted = reportFactory.getSubmitted();
							vm.saving = false;
							setAutosaveTimeout();
						}, 
						function(reason) {
							fail(reason);
						}
					);
				}, 
				function(reason) {
					fail(reason);
				}
			);
		}
		
		function fail(reason) {
			console.log("Error: " + reason);
			$uibModal.open(modalFactory.getErrorModalOptions());
			vm.saving = false;
		}
	}
})();