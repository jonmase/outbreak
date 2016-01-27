(function() {
	angular.module('flu.marking')
		.controller('MarkingController', MarkingController);

	MarkingController.$inject = ['$q', '$uibModal', 'markingFactory', 'modalFactory'];
	
	function MarkingController($q, $uibModal, markingFactory, modalFactory) {
		var vm = this;
		vm.loading = true;
		
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
		
		//Actions
		var usersPromise = markingFactory.loadUsers();
		
		$q.all([usersPromise]).then(
			function(result) {
				console.log(result);
				vm.users = markingFactory.getUsers();
				vm.loading = false;
			}, 
			function(reason) {
				console.log("Error: " + reason);
				$uibModal.open(modalFactory.getErrorModalOptions());
			}
		);
	}
})();