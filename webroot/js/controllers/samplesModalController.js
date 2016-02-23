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
	angular.module('flu.samples')
		.controller('SamplesModalController', SamplesModalController);

	SamplesModalController.$inject = ['$uibModalInstance', '$uibModal', 'modalFactory', 'progressFactory', 'lockFactory', 'sampleFactory', 'siteFactory'];

	function SamplesModalController($uibModalInstance, $uibModal, modalFactory, progressFactory, lockFactory, sampleFactory, siteFactory) {
		var vm = this;
		var sectionId = 'sampling';
		vm.confirm = confirm;
		vm.cancel = cancel;
		vm.samples = sampleFactory.getSamples();
		vm.siteIds = siteFactory.getSiteIds();
		vm.saving = false;
		
		//Moved the temporary samples to the saved samples
		function confirm() {
			vm.saving = true;
			
			var samplesPromise = sampleFactory.setSamples();
			samplesPromise.then(
				function(result) {
					console.log(result);
					//Only need to set progress locally - progress is saved to DB as part of samples saving
					if(!progressFactory.checkProgress(sectionId)) {
						lockFactory.setComplete(sectionId, false);	//Set the progress for this section to complete, but don't save to DB
					}
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