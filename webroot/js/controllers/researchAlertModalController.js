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
		.controller('ResearchAlertModalController', ResearchAlertModalController);

	ResearchAlertModalController.$inject = ['$scope', '$uibModalInstance', '$location', '$controller'];
	
	function ResearchAlertModalController($scope, $uibModalInstance, $location, $controller) {
		var vm = this;
		
		//Bindable Members - values
		
		//Bindable Members - methods
		vm.goToReport = goToReport;
		vm.goToResearch = goToResearch;
		vm.goToResults = goToResults;
		vm.cancel = cancel;

		function goToReport() {
			$uibModalInstance.close();
			$location.path("/report");
		}
		
		function goToResearch() {
			$uibModalInstance.close();
			$location.path("/research");
		}
		
		function goToResults() {
			$uibModalInstance.close();
			$location.path("/results");
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();