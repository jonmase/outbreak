(function() {
	angular.module('flu')
		.factory('modalFactory', modalFactory);
	
	function modalFactory() {
		var beggingModalOptions = setBeggingModalOptions();
		var fluAlertModalOptions = setFluAlertModalOptions();
		var introModalOptions = setIntroModalOptions();
		var labModalOptions = setLabModalOptions();
		var outbreakAlertModalOptions = setOutbreakAlertModalOptions();
		
		var factory = {
			getBeggingModalOptions: getBeggingModalOptions,
			getFluAlertModalOptions: getFluAlertModalOptions,
			getIntroModalOptions: getIntroModalOptions,
			getLabModalOptions: getLabModalOptions,
			getOutbreakAlertModalOptions: getOutbreakAlertModalOptions,
		}
		return factory;
		
		function getBeggingModalOptions() {
			return beggingModalOptions;
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