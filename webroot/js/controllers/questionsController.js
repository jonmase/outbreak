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
	angular.module('flu.questions')
		.controller('QuestionsController', QuestionsController);

	QuestionsController.$inject = ['$scope', '$sce', '$uibModal', 'sectionFactory', 'lockFactory', 'modalFactory', 'questionFactory', '$q'];
	
	function QuestionsController($scope, $sce, $uibModal, sectionFactory, lockFactory, modalFactory, questionFactory, $q) {
		var vm = this;
		var sectionId = 'questions';
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		document.body.scrollTop = 0;
		vm.loading = true;

		//Bindable Members - values
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = questionFactory.getQuestions();
		vm.currentQuestionId = questionFactory.getCurrentQuestionId();
		vm.romans = questionFactory.getRomans();
		questionFactory.setAnswered();	//Set whether each question has been answered
		vm.responses = questionFactory.getResponses();
		vm.saving = questionFactory.getSaving();
		//vm.notAnswered = questionFactory.getNotAnswered();

		//Bindable Members - methods
		vm.setSubsection = setSubsection;
		vm.checkAllAnswered = checkAllAnswered;
		vm.check = check;
		vm.clear = clear;
		vm.complete = complete;
		vm.next = next;
		vm.prev = prev;
		
		//Actions on arrival
		vm.setSubsection(vm.currentQuestionId);	//Set the subsection
		//For Development, set to complete as soon as you go to the questions page
		//Note that this still gets called even if user is redirected home by checkLock - doesn't really matter, as won't just unlock page on first visit.
		//lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
		vm.loading = false;
		
		//Functions
		function checkAllAnswered(questionId) {
			questionFactory.checkAllAnswered(questionId);
		}
		
		function check(questionId) {
			questionFactory.setSaving(questionId, true);
			var questionPromise = questionFactory.checkAnswers(questionId);
			//var completePromise = questionFactory.setQuestionsComplete();
			questionPromise.then(
				function(result) {
					console.log(result);
					var completePromise = questionFactory.setQuestionsComplete();
					//If complete promise is a string, then we don't need to save any progress
					if(angular.isString(completePromise) ) {
						questionFactory.setSaving(questionId, false);
						console.log(completePromise);
					}
					else {
						completePromise.then(
							function(result) {
								questionFactory.setSaving(questionId, false);
								console.log(result);
							}, 
							function(reason) {
								fail(questionId, reason);
							}
						);
					}
				}, 
				function(reason) {
					fail(questionId, reason);
				}
			);
		}
		
		function fail(questionId, reason) {
			console.log("Error: " + reason);
			questionFactory.setSaving(questionId, false);
			$uibModal.open(modalFactory.getErrorModalOptions());
		}
		
		function clear(questionId) {
			questionFactory.clearAnswers(questionId);			
		}
		
		//Dev only: set section to complete
		function complete() {
			lockFactory.setComplete('questions');
		}
		
		/*function change(changeBy) {
			var newQuestionId = vm.currentQuestionId + changeBy;
			vm.subsections[newQuestionId].active = true;
			document.body.scrollTop = 0;
			setSubsection(newQuestionId);
		}*/
		
		function next() {
			nextOrPrev('next');
		}
		
		function prev() {
			nextOrPrev('prev');
		}
		
		function nextOrPrev(direction) {
			var newQuestionId = questionFactory.getNextOrPrev(vm.currentQuestionId, direction);
			/*var newQuestionId = vm.currentQuestionId + changeBy;*/
			vm.subsections[newQuestionId].active = true;
			document.body.scrollTop = 0;
			setSubsection(newQuestionId);
		}
		
		function setSubsection(questionId) {
			questionFactory.setCurrentQuestionId(questionId);
			vm.currentQuestionId = questionId;
			//vm.currentQuestion = vm.subsections[questionId];
		};
	}
})();