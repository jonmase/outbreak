(function() {
	angular.module('flu')
		.constant('sectionsConstant', sectionsConstant)
		.factory('sectionFactory', sectionFactory);
		
	sectionFactory.$inject = ['sectionsConstant'];
		
	function sectionFactory(sectionsConstant) {
		var factory = {
			getSection: getSection,
		}
		return factory;
		
		function getSection(sectionId) {
			var sections = sectionsConstant();
			return sections[sectionId];
		}
	}
		
	function sectionsConstant() {
		//NOT API
		var sections = {
			revision: {
				name: 'Revision',
				controller: 'TechniquesController',
				template: 'techniques',
				icon: 'book',
				prerequisites: ['alert'],
			},
			questions: {
				name: 'Questions',
				controller: 'QuestionsController',
				template: 'questions',
				icon: 'question-circle',
				prerequisites: ['revision'],
				//highlighted: true
			},
			sampling: {
				name: 'Collect Samples',
				controller: 'SamplesController',
				template: 'samples',
				icon: 'user',
				prerequisites: ['questions'],
			},
			lab: {
				name: 'Lab',
				controller: 'LabController',
				template: 'lab',
				icon: 'flask',
				prerequisites: ['sampling'],
			},
			results: {
				name: 'Results',
				controller: 'ResultsController',
				template: 'results',
				//icon: 'dot-circle-o',
				icon: 'bar-chart',
				prerequisites: ['lab'],
			},
			report: {
				name: 'Report',
				controller: 'ReportController',
				template: 'report',
				icon: 'file-text-o',
				prerequisites: ['hidentified', 'nidentified'],
			},
			research: {
				name: 'Grant Funded Research',
				controller: 'TechniquesController',
				template: 'techniques',
				icon: 'flaskmoney',
				//icon: 'search',
				prerequisites: ['hidentified', 'nidentified'],
			},
			finish: {
				name: 'Feedback',
				controller: 'FeedbackController',
				template: 'feedback',
				icon: 'comment',	//'sign-out',
				prerequisites: ['report', 'research'],
			},
			info: {
				name: 'Info & Acknowledgements',
				controller: 'InfoController',
				template: 'info',
				icon: 'info-circle',
				prerequisites: [],
			},
		};
		
		return sections;
	}
})();