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
		.controller('FeedbackController', FeedbackController);

	FeedbackController.$inject = ['$scope', 'sectionFactory', 'lockFactory'];
	function FeedbackController($scope, sectionFactory, lockFactory) {
		var vm = this;
		var sectionId = 'feedback';
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		document.body.scrollTop = 0;
		
		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		
		//Actions
	}
})();