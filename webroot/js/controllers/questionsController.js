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
			vm.currentQuestionIndex = questionFactory.getCurrentQuestionIndex();
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
			vm.change = change;
			
			//Actions on arrival
			vm.setSubsection(vm.currentQuestionIndex);	//Set the subsection
			//For Development, set to complete as soon as you go to the questions page
			//Note that this still gets called even if user is redirected home by checkLock - doesn't really matter, as won't just unlock page on first visit.
			//lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
			vm.loading = false;
		}
		
		//Functions
		function checkAllAnswered(questionIndex) {
			questionFactory.checkAllAnswered(questionIndex);
		}
		
		function check(questionIndex) {
			questionFactory.setSaving(questionIndex, true);
			var questionPromise = questionFactory.checkAnswers(questionIndex);
			//var completePromise = questionFactory.setQuestionsComplete();
			questionPromise.then(
				function(result) {
					var completePromise = questionFactory.setQuestionsComplete();
					if(completePromise) {	//If complete promise is not false, then all questions have been completed and we need to wait for progress to be saved
						completePromise.then(
							function(result) {
								questionFactory.setSaving(questionIndex, false);
								console.log(result);
							}, 
							function(reason) {
								console.log("Error: " + reason);
							}
						);
					}
					else {
						questionFactory.setSaving(questionIndex, false);
					}
				}, 
				function(reason) {
					console.log("Error: " + reason);
				}
			);
		}
		
		function clear(questionIndex) {
			questionFactory.clearAnswers(questionIndex);			
		}
		
		function complete() {
			lockFactory.setComplete('questions');
		}
		
		function change(changeBy) {
			var newQuestionId = vm.currentQuestionIndex + changeBy;
			vm.subsections[newQuestionId].active = true;
			document.body.scrollTop = 0;
			setSubsection(newQuestionId);
		}
		
		function setSubsection(questionIndex) {
			questionFactory.setCurrentQuestionIndex(questionIndex);
			vm.currentQuestionIndex = questionIndex;
			vm.currentQuestion = vm.subsections[questionIndex];
		};
	}
})();