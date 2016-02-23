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
	angular.module('flu.lab')
		.controller('BeggingModalController', BeggingModalController);

	BeggingModalController.$inject = ['$uibModalInstance', '$uibModal', 'modalFactory', 'progressFactory', 'moneyCutoff', 'timeCutoff'];
	
	function BeggingModalController($uibModalInstance, $uibModal, modalFactory, progressFactory, moneyCutoff, timeCutoff) {
		var vm = this;
		var resetMoney = false;
		var resetTime = false;
		
		//Bindable Members - values
		vm.saving = false;
		vm.resources = progressFactory.getResources();
		vm.moneyCutoff = moneyCutoff;
		vm.timeCutoff = timeCutoff;
		if(vm.resources.money < moneyCutoff && vm.resources.time < timeCutoff) {
			vm.resourceText = 'time and money';
			vm.resourceTitle = 'Time and Money';
			resetMoney = true;
			resetTime = true;
		}
		else if(vm.resources.time < timeCutoff) {
			vm.resourceText = 'time';
			vm.resourceTitle = 'Time';
			resetTime = true;
		}
		else if(vm.resources.money < moneyCutoff) {
			vm.resourceText = 'money';
			vm.resourceTitle = 'Money';
			resetMoney = true;
		}

		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		function confirm() {
			vm.saving = true;
			var resetPromise = progressFactory.resetResources(resetMoney, resetTime);
			resetPromise.then(
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
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();