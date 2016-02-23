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
		.controller('InfoController', InfoController);

	InfoController.$inject = ['$scope', 'sectionFactory'];
	function InfoController($scope, sectionFactory) {
		var vm = this;
		document.body.scrollTop = 0;
		var sectionId = 'info';
		
		//Bindable Members - values
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Actions
	}
})();