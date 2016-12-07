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
	angular.module('flu.marking')
		.controller('MarkingController', MarkingController);

	MarkingController.$inject = ['$q', '$window', '$uibModal', 'markingFactory', 'modalFactory', 'techniqueFactory', 'sampleFactory', 'schoolFactory', 'siteFactory', 'assayFactory'];
	
	function MarkingController($q, $window, $uibModal, markingFactory, modalFactory, techniqueFactory, sampleFactory, schoolFactory, siteFactory, assayFactory) {
		window.onbeforeunload = null;   //Remove before unload listener (set in ReportController)
        
		var vm = this;
		
		vm.me = MY_ID;
		vm.status = 'loading';
		vm.currentUserId = null;
		vm.currentUser = null;
		
		//Bindable Members - variables
		vm.rolesForFilter = [
			{
				value: 'Student',
				label: 'Students',
			},
			{
				value: 'Demonstrator',
				label: 'Demonstrators',
			},
			{
				value: '',
				label: 'Both',
			}
		];
		vm.roleToShow = vm.rolesForFilter[2];
		
		vm.submitStatusesForFilter = [
			{
				value: 1,
				label: 'Submitted',
			},
			{
				value: 0,
				label: 'Not Submitted',
			},
			{
				value: -1,
				label: 'Both',
			},
		];
		vm.submitStatusToShow = vm.submitStatusesForFilter[2];
		
		vm.markOptions = ['Satis plus', 'Satis', 'Fail'];
		vm.markOptionsForFilter = vm.markOptions;
		vm.markOptionsForFilter.unshift('All', 'Unmarked', 'Marked');
		vm.markToShow = vm.markOptionsForFilter[0];
		
		vm.orderOptions = [
			{
				value: 'lti_displayid',
				label: 'Username',
			},
			{
				value: 'lti_lis_person_name_family',
				label: 'Last name',
			},
			{
				value: 'lti_lis_person_name_given',
				label: 'Given name',
			},
		];
		vm.orderBy = 'lti_displayid';
	
		//Bindable Members - methods
		vm.hideUser = hideUser;
		vm.markUser = markUser;
		vm.showUser = showUser;
		vm.save = save;
		vm.edit = edit;
		vm.cancel = cancel;
		
		//Actions
		var usersPromise = markingFactory.loadUsers();

		var techniquesPromise = techniqueFactory.loadTechniques();
		var sitesPromise = siteFactory.loadSites();
		var schoolsPromise = schoolFactory.loadSchools();
		var typesPromise = sampleFactory.loadTypes();
		var standardsPromise = assayFactory.loadStandards();
		
		$q.all([usersPromise, techniquesPromise, sitesPromise, schoolsPromise, typesPromise, standardsPromise]).then(
			function(result) {
				console.log(result);
				vm.users = markingFactory.getUsers();
				vm.userCount = markingFactory.getUserCount();
				vm.techniques = techniqueFactory.getTechniques('lab');
				vm.sites = siteFactory.getSites();;
				vm.schools = schoolFactory.getSchools();
				vm.types = sampleFactory.getSampleTypes();
				vm.standards = assayFactory.getStandards();
				vm.status = 'index';
			}, 
			function(reason) {
				console.log("Error: " + reason);
				$uibModal.open(modalFactory.getErrorModalOptions());
			}
		);
		
		//Functions
		function hideUser(userId) {
			//alert(userId);
		}
		function showUser(userId) {
			//alert(userId);
		}
		
		function markUser(userId) {
			if(!vm.users[userId].marked) {
				var lockPromise = markingFactory.setLock(userId, true);
			}
			else {
				lockPromise = true;
			}
			var usersPromise = markingFactory.loadUserAttempts(userId);
			
			$q.all([lockPromise, usersPromise]).then(
				function(result) {
					console.log(result);
					vm.currentUserId = userId;
					vm.currentUser = vm.users[vm.currentUserId];
					
					if(!vm.users[userId].marked) {
						vm.currentUser.editing = true;
					}
					vm.status = 'mark';
					document.body.scrollTop = 0;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);
		}
		
		function cancel() {
			var lockPromise = markingFactory.setLock(vm.currentUserId, false);
			
			lockPromise.then(
				function(result) {
					console.log(result);
					reloadUsers();
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);
		}
		
		function edit() {
			var lockPromise = markingFactory.setLock(vm.currentUserId, true);
			lockPromise.then(
				function(result) {
					console.log(result);
					vm.status = 'mark';
					vm.currentUser.editing = true;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					if(reason === 'locked') {
						var lockedModalInstance = $uibModal.open(modalFactory.getMarkingLockedModalOptions());
						lockedModalInstance.result.then(
							function () {
								reloadUsers();
							}
						)
					}
					else {
						$uibModal.open(modalFactory.getErrorModalOptions());
					}
				}
			);
		}
		
		function reloadUsers() {
			vm.status = 'loading';
			vm.currentUser.editing = false;
			var usersPromise = markingFactory.loadUsers();
			usersPromise.then(
				function(result) {
					console.log(result);
					vm.users = markingFactory.getUsers();
					vm.userCount = markingFactory.getUserCount();
					vm.status = 'index';
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);
		}
		
		function save() {
			var markPromise = markingFactory.save(vm.currentUserId);
			markPromise.then(
				function(result) {
					console.log(result);
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);

		}
	}
})();