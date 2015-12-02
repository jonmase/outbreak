(function() {
	angular.module('flu.questions', [])
		.controller('QuestionsController', QuestionsController);

	QuestionsController.$inject = ['$scope', '$sce', 'sectionFactory', 'lockFactory', 'questionFactory'];
	
	function QuestionsController($scope, $sce, sectionFactory, lockFactory, questionFactory) {
		var vm = this;
		var sectionId = 'questions';
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller

		//Bindable Members - values
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details
		vm.subsections = questionFactory.getQuestions();
		vm.currentQuestionId = questionFactory.getCurrentQuestionId();
		vm.romans = questionFactory.getRomans();
		vm.responses = questionFactory.getResponses();
		//vm.notAnswered = questionFactory.getNotAnswered();

		//Bindable Members - methods
		vm.setSubsection = setSubsection;
		vm.checkAllAnswered = checkAllAnswered;
		vm.check = check;
		vm.clear = clear;
		vm.complete = complete;
		vm.change = change;
		
		//Actions on arrival
		vm.setSubsection(vm.currentQuestionId);	//Set the subsection
		//For Development, set to complete as soon as you go to the questions page
		//Note that this still gets called even if user is redirected home by checkLock - doesn't really matter, as won't just unlock page on first visit.
		//lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
		
		//Functions
		function checkAllAnswered(questionId) {
			questionFactory.checkAllAnswered(questionId);
		}
		
		function check(questionId) {
			questionFactory.checkAnswers(questionId);
		}
		
		function clear(questionId) {
			questionFactory.clearAnswers(questionId);			
		}
		
		function complete() {
			lockFactory.setComplete('questions');
		}
		
		function change(changeBy) {
			var newQuestionId = vm.currentQuestionId + changeBy;
			vm.subsections[newQuestionId].active = true;
			document.body.scrollTop = 0;
			setSubsection(newQuestionId);
		}
		
		function setSubsection(questionId, updateFactory) {
			questionFactory.setCurrentQuestionId(questionId);
			vm.currentQuestionId = questionId;
			vm.currentQuestion = vm.subsections[questionId];
		};
	}
})();