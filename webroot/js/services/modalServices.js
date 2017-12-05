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
	angular.module('flu.modals')
		.factory('modalFactory', modalFactory);
	
	function modalFactory() {
		var factory = {
			getBeggingModalOptions: getBeggingModalOptions,
			getErrorModalOptions: getErrorModalOptions,
			getFluAlertModalOptions: getFluAlertModalOptions,
			getHelpModalOptions: getHelpModalOptions,
			getIntroModalOptions: getIntroModalOptions,
			getLabModalOptions: getLabModalOptions,
			getMarkingLockedModalOptions: getMarkingLockedModalOptions,
			getOutbreakAlertModalOptions: getOutbreakAlertModalOptions,
			getReportErrorModalOptions: getReportErrorModalOptions,
            getResearchAlertModalOptions: getResearchAlertModalOptions,
            getSamplesModalOptions: getSamplesModalOptions,
            getSubmitModalOptions: getSubmitModalOptions,
            getSubmittedModalOptions: getSubmittedModalOptions,
            getTooLateModalOptions: getTooLateModalOptions,
		}
		return factory;
		
		function getBeggingModalOptions() {
			var beggingModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'BeggingModalController',
				controllerAs: 'BeggingModalCtrl',
				size: 'md',
				templateUrl: 'begging-modal.html',
			};
			
			return beggingModalOptions;
		}
		
		function getErrorModalOptions() {
			var errorModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'ErrorModalController',
				controllerAs: 'ErrorModalCtrl',
				size: 'md',
                templateUrl: 'error-modal.html',
			};
			
			return errorModalOptions;
		}
		
		function getFluAlertModalOptions() {
			var fluAlertModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'FluAlertModalController',
				controllerAs: 'FluAlertModalCtrl',
				size: 'lg',
				templateUrl: 'flu-alert-modal.html',
			};
			
			return fluAlertModalOptions;
		}
		
		function getHelpModalOptions() {
			var helpModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'HelpModalController',
				controllerAs: 'HelpModalCtrl',
				size: 'md',
				templateUrl: 'help-modal.html',
			};
			
			return helpModalOptions;
		}
		
		function getIntroModalOptions() {
			var introModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'IntroModalController',
				controllerAs: 'IntroModalCtrl',
				size: 'lg',
				templateUrl: 'intro-modal.html',
			};
			
			return introModalOptions;
		}
		
		function getLabModalOptions(currentTechniqueId) {
			var labModalOptions = {
				animation: true,
				controller: 'LabModalController',
				controllerAs: 'LabModalCtrl',
				resolve: { 
					currentTechniqueId: function () { 
						return currentTechniqueId; 
					} 
				},
				size: 'lg',
				templateUrl: 'lab-modal.html',
			};
			
			return labModalOptions;
		}
		
		function getMarkingLockedModalOptions() {
			var errorModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'MarkingLockedModalController',
				controllerAs: 'MarkingLockedModalCtrl',
				size: 'md',
				templateUrl: 'marking-locked-modal.html',
			};
			
			return errorModalOptions;
		}
		
		function getOutbreakAlertModalOptions() {
			var outbreakAlertModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'OutbreakAlertModalController',
				controllerAs: 'OutbreakAlertModalCtrl',
				size: 'lg',
				templateUrl: 'outbreak-alert-modal.html',
			};
			
			return outbreakAlertModalOptions;
		}
		
		function getReportErrorModalOptions() {
			var beggingModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'ReportErrorModalController',
				controllerAs: 'ReportErrorModalCtrl',
				size: 'lg',
                templateUrl: 'report-error-modal.html',
			};
			
			return beggingModalOptions;
		}
        
		function getResearchAlertModalOptions() {
			var researchAlertModalOptions = {
                animation: true,
                controller: 'ResearchAlertModalController',
                controllerAs: 'ResearchAlertModalCtrl',
                size: 'lg',
                templateUrl: 'research-alert-modal.html',
            }
			return researchAlertModalOptions;
        }
        
		function getSamplesModalOptions() {
			var samplesModalOptions = {
                animation: true,
                controller: 'SamplesModalController',
                controllerAs: 'SamplesModalCtrl',
                size: 'lg',
                templateUrl: 'samples-modal.html',
            }
			return samplesModalOptions;
        }
        
		function getSubmitModalOptions() {
			var submitModalOptions = {
				animation: true,
				backdrop: 'static',
				controller: 'SubmitModalController',
				controllerAs: 'SubmitModalCtrl',
				size: 'md',
				templateUrl: 'submit-modal.html',
			}
			return submitModalOptions;
        }
        
		function getSubmittedModalOptions() {
			var submittedModalOptions = {
                animation: true,
                backdrop: 'static',
                controller: 'SubmittedModalController',
                controllerAs: 'SubmittedModalCtrl',
                size: 'md',
                templateUrl: 'submitted-modal.html',
            }
			return submittedModalOptions;
        }
        
		function getTooLateModalOptions() {
			var tooLateModalOptions = {
                animation: true,
                backdrop: 'static',
                controller: 'tooLateModalController',
                controllerAs: 'tooLateModalCtrl',
                size: 'md',
                templateUrl: 'too-late-modal.html',
            }
			return tooLateModalOptions;
        }
	}
})();