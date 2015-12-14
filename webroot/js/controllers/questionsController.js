(function() {
	angular.module('flu.questions', [])
		.controller('QuestionsController', QuestionsController);

	QuestionsController.$inject = ['$scope', '$sce', '$uibModal', 'sectionFactory', 'lockFactory', 'modalFactory', 'questionFactory', '$q'];
	
	function QuestionsController($scope, $sce, $uibModal, sectionFactory, lockFactory, modalFactory, questionFactory, $q) {
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