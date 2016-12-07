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
		.controller('HomeController', HomeController);

	HomeController.$inject = ['$scope', '$sce', 'mediaFactory', 'progressFactory', 'sampleFactory'];
	
	function HomeController($scope, $sce, mediaFactory, progressFactory, sampleFactory) {
		window.onbeforeunload = null;   //Remove before unload listener (set in ReportController)
        
		var vm = this;
		document.body.scrollTop = 0;
		
		//Bindable Members - values
		$scope.$parent.$parent.currentSectionId = 'home';	//Make sure the section ID is set correctly in Main Controller (Main Controller is grandparent of this one, rather than parent - not sure why)
		vm.introVideo = $sce.trustAsResourceUrl(mediaFactory.getIntroVideo());
		vm.acuteSwabSamplesCollected = areAcuteSwabSamplesCollected();
		
		//Actions
		//mediaFactory.loadJWPlayer('homePlayer', mediaFactory.getIntroVideo());
		
		//Functions
		function areAcuteSwabSamplesCollected() {
			return sampleFactory.getAcuteSwabSamplesCollected();
		}
		
	}
})();