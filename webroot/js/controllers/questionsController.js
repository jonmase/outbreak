(function() {
	angular.module('flu.questions', [])
		.controller('QuestionsController', QuestionsController);

	QuestionsController.$inject = ['$scope', '$sce', 'sectionFactory', 'lockFactory', 'questionFactory', '$q'];
	
	function QuestionsController($scope, $sce, sectionFactory, lockFactory, questionFactory, $q) {
		var vm = this;
		var sectionId = 'questions';
		
		//Check whether the section is locked
		if(!lockFactory.checkLock(sectionId)) {	
			return false;
		}
		vm.loading = true;
		$scope.$parent.currentSectionId = sectionId;	//Make sure the section ID is set correctly in Main Controller
		vm.section = sectionFactory.getSection(sectionId);	//Get the section details

		setup();
		
		/*if(!questionFactory.getLoaded()) {
			var questionsPromise = questionFactory.loadQuestions();
			var responsesPromise = questionFactory.loadResponses();
			$q.all([questionsPromise, responsesPromise]).then(
				function(result) {
					questionFactory.setLoaded();
					console.log(result);
					setup();
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		else {
			setup();
		}*/
		
		function setup() {
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
		}
		
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
					var completePromise = questionFactory.setQuestionsComplete();
					if(completePromise) {	//If complete promise is not false, then all questions have been completed and we need to wait for progress to be saved
						completePromise.then(
							function(result) {
								questionFactory.setSaving(questionId, false);
								console.log(result);
							}, 
							function(reason) {
								console.log("Error: " + reason);
							}
						);
					}
					else {
						questionFactory.setSaving(questionId, false);
					}
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		
		function clear(questionId) {
			questionFactory.clearAnswers(questionId);			
		}
		
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