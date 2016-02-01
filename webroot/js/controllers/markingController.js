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
		vm.marks = [];
		
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
		
		/*vm.markOptions = [
			{
				value: 'plus',
				label: 'Satis plus',
			},
			{
				value: 'satis',
				label: 'Satis',
			},
			{
				value: 'fail',
				label: 'Fail',
			},
		];*/
		vm.markOptions = ['Satis plus', 'Satis', 'Fail',];
		
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
		function hideUser(userIndex) {
			alert(userIndex);
		}
		function markUser(userIndex) {
			vm.status = 'mark';
			vm.currentUserIndex = userIndex;
			vm.currentUser = vm.users[vm.currentUserIndex];

			checkout();
		}
		function showUser(userIndex) {
			alert(userIndex);
		}
		
		function cancel() {
			vm.status = 'index';
			vm.currentUserIndex = null;
			vm.currentUser = null;
			
			//TODO: Release checked out user
		}
		
		function checkout() {
			//TODO: Check out method
		}
		
		function edit() {
			//TODO: Show select and text area
			
			checkout();
		}
		
		function save() {
			var markPromise = markingFactory.save(vm.currentUserIndex);
			markPromise.then(
				function(result) {
					console.log(result);
					//Display mark, comments and marker details, plus Edit Mark button
					
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModal.open(modalFactory.getErrorModalOptions());
				}
			);

		}
	}
})();