(function() {
	angular.module('flu.directives', [])
		.directive('iconBar', iconBar)
		.directive('loader', loader)
		.directive('emailMsdlt', emailMsdlt)
		.directive('pageTitle', pageTitle)
		.directive('pageMenu', pageMenu)
		.directive('outbreakAlert', outbreakAlert)
		.directive('fluAlert', fluAlert)
		.directive('researchAlert', researchAlert)
		.directive('quickvueResults', quickvueResults)
		.directive('infoOnHome', infoOnHome)
		.directive('notesInput', notesInput)
		.directive('techniqueCosts', techniqueCosts)
		.directive('showResources', showResources)
		.directive('showResult', showResult)
		.directive('showSamples', showSamples)
		.directive('allOrNone', allOrNone)
		.directive('techniqueVideo', techniqueVideo)
		.directive('techniqueInfo', techniqueInfo)
		//.directive('bootstrapModal', bootstrapModal)
		//.directive('videoPlayer', videoPlayer);
		
	function iconBar() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/icon-bar.html",
		};
	}

	function loader() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/loader.html",
		};
	}

	function pageTitle() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/page-title.html",
			scope: {
				section: '=',
			}
		};
	}

	function pageMenu() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/page-menu.html",
			scope: {
				items: '=',
				click: '&onClick'
			}
		};
	}

	function emailMsdlt() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/email-msdlt.html",
		};
	}

	function outbreakAlert() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/outbreak-alert.html",
		};
	}

	function fluAlert() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/flu-alert.html",
			scope: {
				swabs: '=',
			},
		};
	}

	function researchAlert() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/research-alert.html",
		};
	}

	function quickvueResults() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/quickvue-results.html",
		};
	}

	function infoOnHome() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/info-on-home.html",
			scope: {
				type: '=',
			}
		};
	}
	
	function notesInput() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/notes-input.html",
			scope: {
				model: '=',
				techniqueId: '=',
				blur: '&onBlur',
			}
		};
	}

	function techniqueCosts() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/technique-costs.html",
			scope: {
				technique: '=',
			}
		};
	}

	function showResources() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/show-resources.html",
			scope: {
				//technique: '=',
				//count: '=',
				time: '=',
				money: '=',
				cost: '=',
				title: '=',
			}
		};
	}

	function showResult() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/show-result.html",
			scope: {
				technique: '=',
				samples: '=',
				samplesPerformed: '=',
				type: '=',
				typeRef: '=',
				siteRef: '=',
			},
		};
	}

	function showSamples() {
		return {
			restrict: "AE", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/show-samples.html",
			controller: showSamplesController,
			controllerAs: 'ShowSamplesCtrl',
			scope: {
				status: '=',
			},
		};
	}
	
	showSamplesController.$inject = ['siteFactory', 'schoolFactory', 'sampleFactory'];
	function showSamplesController(siteFactory, schoolFactory, sampleFactory) {
		var vm = this;
		vm.sites = siteFactory.getSites();
		vm.schools = schoolFactory.getSchools();
		vm.samples = sampleFactory.getSamples();
		vm.types = sampleFactory.getSampleTypes();
	}

	function allOrNone() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			/*link: function(scope, element, attrs) {
				scope.siteId = attrs.siteid;
				scope.schoolId = attrs.schoolid;
				scope.childId = attrs.childid;
				scope.typeId = attrs.typeid;
			},*/
			scope: {
				siteId: '=',
				schoolId: '=',
				childId: '=',
				typeId: '=',
				click: '&onClick',
				//schoolId = '=',
				//childId = '=',
				//typeId = '=',
			},
			templateUrl: "../../partials/elements/all-or-none.html",
		};
	}
	
	function techniqueInfo() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			scope: {
				technique: '=',
			},
			templateUrl: "../../partials/elements/technique-info.html",
		};
	}

	function techniqueVideo() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			scope: {
				video: '=',
				part: '=',
				select: '&onSelect'
			},
			templateUrl: "../../partials/elements/technique-video.html",
		};
	}

	/*function bootstrapModal() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/modal.html",
			/*controller: function() {
				this.progress = progress;
				this.modal = {};
				if(!this.progress.intro) {
					this.modal = modals.intro;
					$('#influenzaModal').modal({
						keyboard: this.modal.closable,
						backdrop: this.modal.closable?true:'static'
					});
				}
			},
			controllerAs: "modalCtrl"*//*
		};
	}*/

	/*function videoPlayer() {
		return {
			restrict: "E", //type of directive - E = Element (i.e. new HTML element), or A = Attribute (i.e. for behaviours)
			templateUrl: "../../partials/elements/jwplayer.html",
		};
	}*/
})();