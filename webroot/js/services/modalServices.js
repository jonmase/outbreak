(function() {
	angular.module('flu.modals')
		.factory('modalFactory', modalFactory);
	
	function modalFactory() {
		var beggingModalOptions = setBeggingModalOptions();
		var errorModalOptions = setErrorModalOptions();
		var fluAlertModalOptions = setFluAlertModalOptions();
		var introModalOptions = setIntroModalOptions();
		var labModalOptions = setLabModalOptions();
		var markingLockedModalOptions = setMarkingLockedModalOptions();
		var outbreakAlertModalOptions = setOutbreakAlertModalOptions();
		
		var factory = {
			getBeggingModalOptions: getBeggingModalOptions,
			getErrorModalOptions: getErrorModalOptions,
			getFluAlertModalOptions: getFluAlertModalOptions,
			getIntroModalOptions: getIntroModalOptions,
			getLabModalOptions: getLabModalOptions,
			getMarkingLockedModalOptions: getMarkingLockedModalOptions,
			getOutbreakAlertModalOptions: getOutbreakAlertModalOptions,
		}
		return factory;
		
		function getBeggingModalOptions() {
			return beggingModalOptions;
		}
		
		function getErrorModalOptions() {
			return errorModalOptions;
		}
		
		function getFluAlertModalOptions() {
			return fluAlertModalOptions;
		}
		
		function getIntroModalOptions() {
			return introModalOptions;
		}
		
		function getLabModalOptions() {
			return labModalOptions;
		}
		
		function getMarkingLockedModalOptions() {
			return markingLockedModalOptions;
		}
		
		function getOutbreakAlertModalOptions() {
			return outbreakAlertModalOptions;
		}
		
		
		function setBeggingModalOptions() {
			var beggingModalOptions = {
				animation: true,
				size: 'lg',
				backdrop: 'static',
				templateUrl: '../../partials/modals/begging-modal.html',
				controller: 'BeggingModalController',
				controllerAs: 'BeggingModalCtrl',
			};
			
			return beggingModalOptions;
		}

		function setErrorModalOptions() {
			var errorModalOptions = {
				animation: true,
				size: 'md',
				backdrop: 'static',
				//templateUrl: '../../partials/modals/error-modal.html',
				template: '<div class="modal-header"><h4 class="modal-title">Someting\'s gone wrong</h4></div><div class="modal-body"><p>Sorry about this, there seems to have been a problem. You may have lost connection, or your session may have timed out. It could also be caused by having multiple instances of this attempt open at once. Please check your internet connection, ensure you don\'t have this attempt open in multiple tabs, and then try refreshing the page. </p><p>Unfortunately, this means that your most recent actions/inputs may be lost. This may include any unchecked questions, uncollected samples or assays that you haven\'t yet carried out. Reports are automatically saved every minute, but you may lose any text you have entered since the last save. Apologies for any inconvenience or annoyance this causes.</p><p>If you continue to experience problems, <email-msdlt></email-msdlt></p></div><div class="modal-footer"><div><button type="button" class="btn btn-primary" ng-click="ErrorModalCtrl.confirm()">Reload</button></div></div>',
				controller: 'ErrorModalController',
				controllerAs: 'ErrorModalCtrl',
			};
			
			return errorModalOptions;
		}

		function setMarkingLockedModalOptions() {
			var errorModalOptions = {
				animation: true,
				size: 'md',
				backdrop: 'static',
				//templateUrl: '../../partials/modals/error-modal.html',
				template: '<div class="modal-header"><h4 class="modal-title">Locked</h4></div><div class="modal-body"><p>Sorry, this student has been locked for marking by someone else since you loaded the page. Please reload the page to see the current status of all the students.</p></div><div class="modal-footer"><div><button type="button" class="btn btn-primary" ng-click="MarkingLockedModalCtrl.confirm()">Refresh</button></div></div>',
				controller: 'MarkingLockedModalController',
				controllerAs: 'MarkingLockedModalCtrl',
			};
			
			return errorModalOptions;
		}

		function setFluAlertModalOptions() {
			var fluAlertModalOptions = {
				animation: true,
				backdrop: 'static',
				size: 'lg',
				templateUrl: '../../partials/modals/flu-alert-modal.html',
				controller: 'FluAlertModalController',
				controllerAs: 'FluAlertModalCtrl',
			};
			
			return fluAlertModalOptions;
		}

		function setIntroModalOptions() {
			var introModalOptions = {
				animation: true,
				backdrop: 'static',
				size: 'lg',
				templateUrl: '../../partials/modals/intro-modal.html',
				controller: 'IntroModalController',
				controllerAs: 'IntroModalCtrl',
			};
			
			return introModalOptions;
		}
		
		function setLabModalOptions() {
			var labModalOptions = {
				animation: true,
				size: 'lg',
				templateUrl: '../../partials/modals/lab-modal.html',
				controller: 'LabModalController',
				controllerAs: 'LabModalCtrl',
				resolve: { 
					currentTechniqueId: function () { 
						return vm.currentTechniqueId; 
					} 
				}
			};
			
			return labModalOptions;
		}
		
		function setOutbreakAlertModalOptions() {
			var outbreakAlertModalOptions = {
				animation: true,
				backdrop: 'static',
				size: 'lg',
				templateUrl: '../../partials/modals/outbreak-alert-modal.html',
				controller: 'OutbreakAlertModalController',
				controllerAs: 'OutbreakAlertModalCtrl',
			};
			
			return outbreakAlertModalOptions;
		}
	}
	
	/*
	//OLD Modal Factory
	function modalFactory() {
		var modal = {
			backdrop: true,
			closable: true,
			content: 'Default content',
			template: false,
			title: 'Default Title',
			width: '90%',
			buttons: {
				primary: {
					text: 'Next',
					show: true,
				},
				dismiss: {
					text: 'Close'
				},
			}
		};
		
		var factory = {
			getModal: getModal,
			setClosable: setClosable,
			setContent: setContent,
			setTemplate: setTemplate,
			setTitle: setTitle,
			setWidth: setWidth,
			showModal: showModal,
		}
		return factory;
		
		function getModal() {
			return modal;
		}

		function setClosable(closable) {
			modal.closable = closable;
		}

		function setContent(content) {
			modal.content = content;
		}
		
		function setTemplate(template) {
			modal.template = template;
		}

		function setTitle(title) {
			modal.title = title;
		}
		
		function setWidth(width) {
			modal.width = width;
		}

		function showModal(options) {
			$('#influenzaModal').modal(options);
		}
	}
	*/
})();