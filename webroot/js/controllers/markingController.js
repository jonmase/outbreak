(function() {
	angular.module('flu.marking')
		.controller('MarkingController', MarkingController);

	MarkingController.$inject = ['$q', '$uibModal', 'markingFactory', 'modalFactory', 'techniqueFactory', 'sampleFactory', 'schoolFactory', 'siteFactory', 'assayFactory'];
	
	function MarkingController($q, $uibModal, markingFactory, modalFactory, techniqueFactory, sampleFactory, schoolFactory, siteFactory, assayFactory) {
		var vm = this;
		
		vm.status = 'loading';
		vm.currentUserIndex = null;
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
		
		vm.statusesForFilter = [
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
		vm.statusToShow = vm.statusesForFilter[2];
		
		//Bindable Members - methods
		vm.hideUser = hideUser;
		vm.markUser = markUser;
		vm.showUser = showUser;
		
		//Actions
		var usersPromise = markingFactory.loadUsers();

		var sitesPromise = siteFactory.loadSites();
		var schoolsPromise = schoolFactory.loadSchools();
		var typesPromise = sampleFactory.loadTypes();
		var standardsPromise = assayFactory.loadStandards();
		
		$q.all([usersPromise, sitesPromise, schoolsPromise, typesPromise, standardsPromise]).then(
			function(result) {
				console.log(result);
				vm.users = markingFactory.getUsers();
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
		function hideUser(userIndex) {
			alert(userIndex);
		}
		function markUser(userIndex) {
			vm.status = 'mark';
			vm.currentUserIndex = userIndex;
			vm.currentUser = vm.users[vm.currentUserIndex];
		}
		function showUser(userIndex) {
			alert(userIndex);
		}
	}
})();