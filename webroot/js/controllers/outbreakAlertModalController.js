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
	angular.module('flu')
		.controller('OutbreakAlertModalController', OutbreakAlertModalController);
	
	OutbreakAlertModalController.$inject = ['$uibModal', '$uibModalInstance', 'lockFactory', 'modalFactory'];

	function OutbreakAlertModalController($uibModal, $uibModalInstance, lockFactory, modalFactory) {
		var vm = this;
		vm.saving = false;

		//Do the same whether the user confirms or cancels - either way they're just dismissing the modal having seen the alert
		vm.cancel = confirm;
		vm.confirm = confirm;
		
		function confirm() {
			vm.saving = true;
			var completePromise = lockFactory.setComplete('alert');	//Set start progress to complete
			completePromise.then(
				function(result) {
					console.log(result);
					$uibModalInstance.close();
					vm.saving = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getErrorModalOptions());
					vm.saving = false;
				}
			);
		}
	}
})();