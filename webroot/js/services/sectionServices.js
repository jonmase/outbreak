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
	angular.module('flu.progress')
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
			feedback: {
				name: 'Feedback',
				controller: 'FeedbackController',
				template: 'feedback',
				icon: 'comment',	//'sign-out',
				prerequisites: ['report'],
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