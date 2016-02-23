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
		.controller('IntroModalController', IntroModalController);

	IntroModalController.$inject = ['$sce', '$uibModal', '$uibModalInstance', 'lockFactory', 'mediaFactory', 'modalFactory'];
	
	function IntroModalController($sce, $uibModal, $uibModalInstance, lockFactory, mediaFactory, modalFactory) {
		var vm = this;
		vm.saving = false;
		
		//vm.loadVideo = loadVideo;
		vm.introVideo = $sce.trustAsResourceUrl(mediaFactory.getIntroVideo());
		vm.start = start;
		
		/*function loadVideo() {
			mediaFactory.loadJWPlayer('introModalPlayer', mediaFactory.getIntroVideo()); 
		}*/
		
		function start() {
			vm.saving = true;
			var completePromise = lockFactory.setComplete('start');	//Set start progress to complete
			completePromise.then(
				function(result) {
					console.log(result);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getOutbreakAlertModalOptions());
					vm.saving = false;
				}, 
				function(reason) {
					console.log("Error: " + reason);
					$uibModalInstance.close();
					$uibModal.open(modalFactory.getErrorModalOptions());
					vm.saving = false;
				}
			);
		};
	}
})();