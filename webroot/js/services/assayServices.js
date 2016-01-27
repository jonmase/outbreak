(function() {
	angular.module('flu.lab')
		.value('moneyCutoff', 1)
		.value('timeCutoff', 1)
		.factory('assayFactory', assayFactory);
	
	assayFactory.$inject = ['techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'progressFactory', 'lockFactory', '$resource', '$q'];
	
	function assayFactory(techniqueFactory, siteFactory, schoolFactory, sampleFactory, progressFactory, lockFactory, $resource, $q) {
		//Variables
		var techniques, currentTechniqueId, samples, sites, schools, types, standards, requiredTests, resources, activeTabs, emptyStandardsForTechnique, emptyAssaysForTechnique, emptyCountsForTechnique, assays, savedAssays, savedStandardAssays;
		
		//Exposed Methods
		var factory = {
			getActiveTabs: getActiveTabs,
			getAssays: getAssays,
			getCurrentTechniqueId: getCurrentTechniqueId,
			getStandards: getStandards,
			getRequiredTests: getRequiredTests,
			loadAssays: loadAssays,
			loadStandardAssays: loadStandardAssays,
			loadStandards: loadStandards,
			resetActiveTabs: resetActiveTabs,
			selectAllOrNoneBySite: selectAllOrNoneBySite,
			selectAllOrNoneByType: selectAllOrNoneByType,
			setAssayCount: setAssayCount,
			setAssays: setAssays,
			setCurrentTechniqueId: setCurrentTechniqueId,
			setLabComplete: setLabComplete,
			setTab: setTab,
			setup: setup,
		}
		return factory;
		
		//Methods
		function setup() {
			techniques = techniqueFactory.getTechniques('lab');
			currentTechniqueId = 1;
			samples = sampleFactory.getSamples();
			sites = siteFactory.getSites();
			schools = schoolFactory.getSchools();
			types = sampleFactory.getSampleTypes();
			requiredTests = readRequiredTests();
			resources = progressFactory.getResources();
			activeTabs = [];

			initializeAssays();
		}
		function getActiveTabs() {
			return activeTabs;
		}
		function getAssays() {
			return assays;
		}
		function getCurrentTechniqueId() {
			return currentTechniqueId;
		}
		function getRequiredTests() {
			return requiredTests;
		}
		function getStandards() {
			return standards;
		}
		
		function initializeAssays() {
			emptyAssaysForTechnique = {};
			emptyCountsForTechnique = {};
			emptyStandardsForTechnique = {};
			emptyCountsForTechnique.total = 0;
			emptyCountsForTechnique.standards = 0;
			emptyCountsForTechnique.sites = {};
			
			var savedAssayCounts = {};
			
			//Set up temp assays
			var tempAssaysAndCounts = {
				standards: {},
				samples: {},
				counts: {}
			};
			
			var firstTechnique = 1;
			var firstChild = 1;
			for(var techniqueId in techniques) {
				//var techniqueId = techniques[techniqueIndex].id;
				if(typeof(savedAssays[techniqueId]) === 'undefined') {
					savedAssays[techniqueId] = {};
				}
				if(typeof(savedStandardAssays[techniqueId]) === 'undefined') {
					savedStandardAssays[techniqueId] = {};
				}
				savedAssayCounts[techniqueId] = angular.copy(emptyCountsForTechnique);

				//for(var siteId = 0; siteId < sites.length; siteId++) {
				for(var siteId in sites) {
					if(firstTechnique) {
						emptyAssaysForTechnique[siteId] = {};
						emptyCountsForTechnique.sites[siteId] = {};
						emptyCountsForTechnique.sites[siteId].schools = {};
						emptyCountsForTechnique.sites[siteId].total = 0;
					}
					savedAssayCounts[techniqueId].sites[siteId] = angular.copy(emptyCountsForTechnique.sites[siteId]);
					if(typeof(savedAssays[techniqueId][siteId]) === 'undefined') {
						savedAssays[techniqueId][siteId] = {};
					}
					
					//for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					for(var schoolId in schools) {
						if(firstTechnique) {
							emptyAssaysForTechnique[siteId][schoolId] = {};
							emptyCountsForTechnique.sites[siteId].schools[schoolId] = {};
							emptyCountsForTechnique.sites[siteId].schools[schoolId].children = {};
							emptyCountsForTechnique.sites[siteId].schools[schoolId].types = {};
							emptyCountsForTechnique.sites[siteId].schools[schoolId].total = 0;
						}
						savedAssayCounts[techniqueId].sites[siteId].schools[schoolId] = angular.copy(emptyCountsForTechnique.sites[siteId].schools[schoolId]);
						if(typeof(savedAssays[techniqueId][siteId][schoolId]) === 'undefined') {
							savedAssays[techniqueId][siteId][schoolId] = {};
						}
						
						//for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						firstChild = 1;
						for(var childId in schools[schoolId].children) {
							if(firstTechnique) {
								emptyAssaysForTechnique[siteId][schoolId][childId] = {};
								emptyCountsForTechnique.sites[siteId].schools[schoolId].children[childId] = 0;
							}
							savedAssayCounts[techniqueId].sites[siteId].schools[schoolId].children[childId] = 0;
							if(typeof(savedAssays[techniqueId][siteId][schoolId][childId]) === 'undefined') {
								savedAssays[techniqueId][siteId][schoolId][childId] = {};
							}
							//for(var typeId = 0; typeId < types.length; typeId++) {
							for(var typeId in types) {
								if(firstTechnique) {
									emptyAssaysForTechnique[siteId][schoolId][childId][typeId] = 0;
									//emptyCountsForTechnique.sites[siteId].schools[schoolId].types[typeId] = 0;
								}
								if(firstChild) {
									emptyCountsForTechnique.sites[siteId].schools[schoolId].types[typeId] = 0;
									savedAssayCounts[techniqueId].sites[siteId].schools[schoolId].types[typeId] = 0;
								}
								if(typeof(savedAssays[techniqueId][siteId][schoolId][childId][typeId]) === 'undefined') {
									savedAssays[techniqueId][siteId][schoolId][childId][typeId] = 0;
								}
								else if(savedAssays[techniqueId][siteId][schoolId][childId][typeId]) {
									savedAssayCounts[techniqueId].total++;
									savedAssayCounts[techniqueId].sites[siteId].total++;
									savedAssayCounts[techniqueId].sites[siteId].schools[schoolId].total++;
									savedAssayCounts[techniqueId].sites[siteId].schools[schoolId].children[childId]++;
									savedAssayCounts[techniqueId].sites[siteId].schools[schoolId].types[typeId]++;
								}
							}
							firstChild = 0;
						}
					}
				}
				
				for(var standardId in standards) {
					if(typeof(savedStandardAssays[techniqueId][standardId]) === 'undefined') {
						savedStandardAssays[techniqueId][standardId] = 0;
					}
					else if(savedStandardAssays[techniqueId][standardId]) {
						savedAssayCounts[techniqueId].total++;
						savedAssayCounts[techniqueId].standards++;
					}

					if(firstTechnique) {
						emptyStandardsForTechnique[standardId] = 0;
					}
				}
				
				tempAssaysAndCounts.standards[techniqueId] = angular.copy(emptyStandardsForTechnique);
				tempAssaysAndCounts.samples[techniqueId] = angular.copy(emptyAssaysForTechnique);
				tempAssaysAndCounts.counts[techniqueId] = angular.copy(emptyCountsForTechnique);

				firstTechnique = 0;
			}

			
			var savedAssaysAndCounts = {
				standards: savedStandardAssays,
				samples: savedAssays,
				counts: savedAssayCounts,
			};
			var allAssaysAndCounts = angular.copy(savedAssaysAndCounts);
			
			assays = {
				all: allAssaysAndCounts,
				saved: savedAssaysAndCounts,
				temp: tempAssaysAndCounts
			};
			//return assays;
		}
		
		function loadAssays() {
			var deferred = $q.defer();
			var AssaysCall = $resource(URL_MODIFIER + 'Assays/load/:attemptId/:token.json', {attemptId: null, token: null});
			AssaysCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						savedAssays = result.assays;
						deferred.resolve('Assays loaded');
					}
					else {
						deferred.reject('Assays load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Assays load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}

		function loadStandardAssays() {
			var deferred = $q.defer();
			var StandardAssaysCall = $resource(URL_MODIFIER + 'StandardAssays/load/:attemptId/:token.json', {attemptId: null, token: null});
			StandardAssaysCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						savedStandardAssays = result.standardAssays;
						deferred.resolve('Standard Assays loaded');
					}
					else {
						deferred.reject('Standard Assays load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Standard Assays load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}

		function loadStandards() {
			var deferred = $q.defer();
			var StandardsCall = $resource(URL_MODIFIER + 'Standards/load.json', {});
			StandardsCall.get({},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						standards = result.standards;
						deferred.resolve('Standards loaded');
					}
					else {
						deferred.reject('Standards load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Standards load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
				
		function readRequiredTests() {
			//API: Store this in the DB somehow?? Leave for now
			var requiredTests = {
				h: [],
				n: [],
			};
			//Technique IDs
			var haiId = 3;
			var pcrhId = 4;
			var pcrnId = 5;
			var elisaId = 7;
			
			//Site IDs
			var npId = 1;
			var bloodId = 2;
			
			//School ID
			var schoolId = 2;

			requiredTests.h[haiId] = {};
			requiredTests.h[pcrhId] = {};
			requiredTests.n[elisaId] = {};
			requiredTests.n[pcrnId] = {};
			
			var schools = schoolFactory.getSchools();
			
			//for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
			for(var childId in schools[schoolId].children) {
				//To Identify H:
				//HAI - Serum, Acute and Convalescent for School 2 children
				requiredTests.h[haiId]["" + bloodId + schoolId + childId + "1"] = 0;
				requiredTests.h[haiId]["" + bloodId + schoolId + childId + "2"] = 0;
				//or PCRH - NP Swab, Acute for School 2 children
				requiredTests.h[pcrhId]["" + npId + schoolId + childId + "1"] = 0;
				
				//To Identify N:
				//ELISA - Serum, Acute and Convalescent for School 2 children
				requiredTests.n[elisaId]["" + bloodId + schoolId + childId + "1"] = 0;
				requiredTests.n[elisaId]["" + bloodId + schoolId + childId + "2"] = 0;
				//or PCRN - NP Swab, Acute for School 2 children
				requiredTests.n[pcrnId]["" + npId + schoolId + childId + "1"] = 0;
			}

			return requiredTests;
		}

		function resetActiveTabs(techniqueId) {
			var activeTab = {
				assay: true,
				results: false,
				info: false,
			};
			if(typeof(techniqueId) !== "undefined") {
				activeTabs[techniqueId] = angular.copy(activeTab);
			}
			else {
				activeTabs = [];
				//for(var t = 0; t < techniques.length; t++) {
				for(var t in techniques) {
					activeTabs[t] = angular.copy(activeTab);
				}
				//return activeTabs;
			}
		}

		function selectAllOrNoneBySite(allOrNone, techniqueId, siteId) {
			if(siteId === 'standards') {
				//for(var standardId = 0; standardId < standards.length; standardId++) {
				for(var standardId in standards) {
					if(assays.saved.standards[techniqueId][standardId] === 0) {
						assays.all.standards[techniqueId][standardId] = allOrNone;
						assays.temp.standards[techniqueId][standardId] = allOrNone;
					}
				}
			}
			else {
				//for(var schoolId = 0; schoolId < schools.length; schoolId++) {
				//	for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
				//		for(var typeId = 0; typeId < types.length; typeId++) {
				for(var schoolId in schools) {
					for(var childId in schools[schoolId].children) {
						for(var typeId in types) {
							if(assays.saved.samples[techniqueId][siteId][schoolId][childId][typeId] === 0 && samples.saved.samples[siteId][schoolId][childId][typeId] === 1) {
								assays.all.samples[techniqueId][siteId][schoolId][childId][typeId] = allOrNone;
								assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] = allOrNone;
							}
						}
					}
				}
			}
			setAssayCount(techniqueId);
		}
		
		function selectAllOrNoneByType(allOrNone, techniqueId, siteId, schoolId, typeId) {
			//for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
			for(var childId in schools[schoolId].children) {
				if(assays.saved.samples[techniqueId][siteId][schoolId][childId][typeId] === 0 && samples.saved.samples[siteId][schoolId][childId][typeId] === 1) {
					assays.all.samples[techniqueId][siteId][schoolId][childId][typeId] = allOrNone;
					assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] = allOrNone;
				}
			}
			setAssayCount(techniqueId);
		}		
		
		function setAssayCount(techniqueId) {
			assays.temp.counts[techniqueId] = angular.copy(emptyCountsForTechnique);	//Reset the temp counts for this technique
			assays.all.counts[techniqueId] = angular.copy(emptyCountsForTechnique);	//Reset the all counts for this technique
			
			//Count all of the standards in the current assay
			//for(var standardId = 0; standardId < standards.length; standardId++) {
			for(var standardId in standards) {
				if(assays.saved.standards[techniqueId][standardId] === 0) {
					assays.temp.standards[techniqueId][standardId] = assays.all.standards[techniqueId][standardId];
				}
				
				if(assays.all.standards[techniqueId][standardId] === 1) {
					assays.all.counts[techniqueId].total++;
					assays.all.counts[techniqueId].standards++;
				}
				if(assays.temp.standards[techniqueId][standardId] === 1) {
					assays.temp.counts[techniqueId].total++;
					assays.temp.counts[techniqueId].standards++;
				}
			}
			
			//Count all of the samples in the current assay
			//for(var siteId = 0; siteId < sites.length; siteId++) {
			//	for(var schoolId = 0; schoolId < schools.length; schoolId++) {
			//		for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
			//			for(var typeId = 0; typeId < types.length; typeId++) {
			for(var siteId in sites) {
				for(var schoolId in schools) {
					for(var childId in schools[schoolId].children) {
						for(var typeId in types) {
							if(assays.saved.samples[techniqueId][siteId][schoolId][childId][typeId] === 0) {
								assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] = assays.all.samples[techniqueId][siteId][schoolId][childId][typeId];
							}
							if(assays.all.samples[techniqueId][siteId][schoolId][childId][typeId] === 1) {
								assays.all.counts[techniqueId].total++;
								assays.all.counts[techniqueId].sites[siteId].total++;
								assays.all.counts[techniqueId].sites[siteId].schools[schoolId].total++;
								assays.all.counts[techniqueId].sites[siteId].schools[schoolId].children[childId]++;
								assays.all.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId]++;
							}
							if(assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] === 1) {
								assays.temp.counts[techniqueId].total++;
								assays.temp.counts[techniqueId].sites[siteId].total++;
								assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].total++;
								assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].children[childId]++;
								assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId]++;
							}
						}
					}
				}
			}
		}
		
		function setAssays(techniqueId, moneyCost, timeCost) {
			//API: Save assays performed
			var deferred = $q.defer();
			var AssaysCall = $resource(URL_MODIFIER + 'assays/save', {});
			AssaysCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, techniqueId: techniqueId, assays: assays.temp.samples[techniqueId], standardAssays: assays.temp.standards[techniqueId], money: (resources.money - moneyCost), time: (resources.time - timeCost)},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						//Add the tempCount to the permanent count and reset the tempCount for the technique
						assays.saved.counts[techniqueId].total += assays.temp.counts[techniqueId].total;
						assays.saved.counts[techniqueId].standards += assays.temp.counts[techniqueId].standards;
						//for(var siteId = 0; siteId < sites.length; siteId++) {
						for(var siteId in sites) {
							assays.saved.counts[techniqueId].sites[siteId].total += assays.temp.counts[techniqueId].sites[siteId].total;
							//for(var schoolId = 0; schoolId < schools.length; schoolId++) {
							for(var schoolId in schools) {
								assays.saved.counts[techniqueId].sites[siteId].schools[schoolId].total += assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].total;
								//for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
								for(var childId in schools[schoolId].children) {
									assays.saved.counts[techniqueId].sites[siteId].schools[schoolId].children[childId] += assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].children[childId];
								}
								//for(var typeId = 0; typeId < types.length; typeId++) {
								for(var typeId in types) {
									assays.saved.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId] += assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId];
								}
							}
						}
						assays.temp.counts[techniqueId] = angular.copy(emptyCountsForTechnique);
						
						//Loop through the assays.temp.samples array for this technique and add each to the assays.saved.samples array, then reset assays.temp.samples
						for(var siteId in assays.temp.samples[techniqueId]) {
							for(var schoolId in assays.temp.samples[techniqueId][siteId]) {
								for(var childId in assays.temp.samples[techniqueId][siteId][schoolId]) {
									for(var typeId in assays.temp.samples[techniqueId][siteId][schoolId][childId]) {
										if(assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] === 1) {
											assays.saved.samples[techniqueId][siteId][schoolId][childId][typeId] = 1;
											
											//Check whether this test is required, and if so, mark the required test as done
											var requiredKey = "" + siteId + schoolId + childId + typeId;
											if(typeof(requiredTests.h[techniqueId]) !== "undefined" && requiredTests.h[techniqueId].hasOwnProperty(requiredKey)) {
												requiredTests.h[techniqueId][requiredKey] = 1;
											}
											if(typeof(requiredTests.n[techniqueId]) !== "undefined" && requiredTests.n[techniqueId].hasOwnProperty(requiredKey)) {
												requiredTests.n[techniqueId][requiredKey] = 1;
											}
										}
									}
								}
							}
						}
						assays.temp.samples[techniqueId] = angular.copy(emptyAssaysForTechnique);
						
						//Loop through the assays.temp.standards array and add each to the assays.saved.standards array
						for(var standardId in assays.temp.standards[techniqueId]) {
							if(assays.temp.standards[techniqueId][standardId] === 1) {
								assays.saved.standards[techniqueId][standardId] = 1;
							}
						}
						assays.temp.standards[techniqueId] = angular.copy(emptyStandardsForTechnique);
						deferred.resolve('Assays saved');
					}
					else {
						deferred.reject('Assay save failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Assay save error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		function setCurrentTechniqueId(techniqueId) {
			currentTechniqueId = techniqueId;
		}

		function setLabComplete(techniqueId) {
			var progress = progressFactory.getProgress();
			var sectionsToSave = [];
			if(!progress.hidentified) {
				//for(var techniqueId = 0; techniqueId < requiredTests.h.length; techniqueId++) {
				for(var techniqueId in requiredTests.h) {
					var identified = 1;
					for(var requirement in requiredTests.h[techniqueId]) {
						if(requiredTests.h[techniqueId][requirement] === 0) {
							identified = 0;
							break;
						}
					}
					if(identified) {
						//lockFactory.setComplete('hidentified');
						sectionsToSave.push('hidentified');
						break;	//No need to check other techniques if H has been identified
					}
				}
			}
			
			if(!progress.nidentified) {
				//for(var techniqueId = 0; techniqueId < requiredTests.n.length; techniqueId++) {
				for(var techniqueId in requiredTests.n) {
					var identified = 1;
					for(var requirement in requiredTests.n[techniqueId]) {
						if(requiredTests.n[techniqueId][requirement] === 0) {
							identified = 0;
							break;
						}
					}
					if(identified) {
						//lockFactory.setComplete('nidentified');
						sectionsToSave.push('nidentified');
						break;	//No need to check other techniques if N has been identified
					}
				}
			}
			
			//If lab section is not already marked as complete, mark it as such
			if(!progress.lab) {
				//lockFactory.setComplete('lab');
				sectionsToSave.push('lab');
			}
			
			if(sectionsToSave.length > 0) {
				return lockFactory.setComplete(sectionsToSave);
			}
			else {
				return 'No Lab progress to save';
			}
		};
		
		function setTab(techniqueId, tab) {
			//vm.activeTabs
			angular.forEach(activeTabs[techniqueId], function(value, key) {
				activeTabs[techniqueId][key] = false;
			});
			activeTabs[techniqueId][tab] = true;
		}
	};
	
		/*function initializeAssays() {
			var savedAssays = readSavedAssays();
			var allAssays = angular.copy(savedAssays);
			var tempAssays = readEmptyAssays();
			
			var assays = {
				all: allAssays,
				saved: savedAssays,
				temp: tempAssays
			};
			return assays;
		}*/

			/*function readEmptyAssays() {
			var emptyAssays = {
				standards: [],
				samples: [],
				counts: []
			};
			
			for(var technique = 0; technique < techniques.length; technique++) {
				emptyAssays.standards[technique] = angular.copy(emptyStandardsForTechnique);
				emptyAssays.samples[technique] = angular.copy(emptyAssaysForTechnique);
				emptyAssays.counts[technique] = angular.copy(emptyCountsForTechnique);
			}
			return emptyAssays;
		}*/
		
		/*function readEmptyAssaysForTechnique() {
			var emptyAssays = [];
			for(var siteId = 0; siteId < sites.length; siteId++) {
				emptyAssays[siteId] = [];
				for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					emptyAssays[siteId][schoolId] = [];
					for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						emptyAssays[siteId][schoolId][childId] = [];
						for(var typeId = 0; typeId < types.length; typeId++) {
							emptyAssays[siteId][schoolId][childId][typeId] = 0;
						}
					}
				}
			}
			return emptyAssays;
		}
		
		function readEmptyCountsForTechnique() {
			var emptyCounts = [];
			emptyCounts = {};
			emptyCounts.total = 0;
			emptyCounts.standards = 0;
			emptyCounts.sites = [];
			for(var siteId = 0; siteId < sites.length; siteId++) {
				emptyCounts.sites[siteId] = {};
				emptyCounts.sites[siteId].schools = [];
				emptyCounts.sites[siteId].total = 0;
				for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					emptyCounts.sites[siteId].schools[schoolId] = {};
					emptyCounts.sites[siteId].schools[schoolId].children = [];
					emptyCounts.sites[siteId].schools[schoolId].types = [];
					emptyCounts.sites[siteId].schools[schoolId].total = 0;
					for(var typeId = 0; typeId < types.length; typeId++) {
						emptyCounts.sites[siteId].schools[schoolId].types[typeId] = 0;
					}
					for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						emptyCounts.sites[siteId].schools[schoolId].children[childId] = 0;
					}
				}
			}
			return emptyCounts;
		}
		
		function readEmptyStandardsForTechnique() {
			var emptyStandards = [];
			for(var standard = 0; standard < standards.length; standard++) {
				emptyStandards[standard] = 0;
			}
			return emptyStandards;
		}*/
		
				/*function readSavedAssays() {
			//API: get saved assays from the DB
			//Also need to generate the counts of the saved assays
			var allSavedAssays = {
				standards: savedStandardAssays,
				samples: savedAssays,
				counts: [],
			};
			
			for(var standardIndex = 0; standardIndex < allSavedAssays.standards.length; standardIndex++) {
				if(assays.saved.standards[techniqueId][standardId] === 0) {
					assays.temp.standards[techniqueId][standardId] = assays.all.standards[techniqueId][standardId];
				}
				
				if(assays.all.standards[techniqueId][standardId] === 1) {
					assays.all.counts[techniqueId].total++;
					assays.all.counts[techniqueId].standards++;
				}
				if(assays.temp.standards[techniqueId][standardId] === 1) {
					assays.temp.counts[techniqueId].total++;
					assays.temp.counts[techniqueId].standards++;
				}
			}
						for(var siteId = 0; siteId < sites.length; siteId++) {
				for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						for(var typeId = 0; typeId < types.length; typeId++) {
							if(assays.saved.samples[techniqueId][siteId][schoolId][childId][typeId] === 0) {
								assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] = assays.all.samples[techniqueId][siteId][schoolId][childId][typeId];
							}
							if(assays.all.samples[techniqueId][siteId][schoolId][childId][typeId] === 1) {
								assays.all.counts[techniqueId].total++;
								assays.all.counts[techniqueId].sites[siteId].total++;
								assays.all.counts[techniqueId].sites[siteId].schools[schoolId].total++;
								assays.all.counts[techniqueId].sites[siteId].schools[schoolId].children[childId]++;
								assays.all.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId]++;
							}


			//For Dev, just use empty assays
			//var allSavedAssays = readEmptyAssays();
			
			return allSavedAssays;
		}*/

		/*function readStandards() {
			//API: Get these from the DB?
			//Note: Don't need to specify results, the image links will be automatically generated
			var standards = [
				{
					id: 'ash1',
					name: 'Antiserum H1',
				},
				{
					id: 'ash3',
					name: 'Antiserum H3',
				},
				{
					id: 'ash5',
					name: 'Antiserum H5',
				},
				{
					id: 'asn1',
					name: 'Antiserum N1',
				},
				{
					id: 'asn2',
					name: 'Antiserum N2',
				},
				{
					id: 'h2o', 
					name: 'Water',
				},
				{
					id: 'mna', 
					name: 'Mixed Nucleic Acids',
				},
				{
					id: 'vh3n2',
					name: 'Virus H3N2 Serotype',
				},
			];
			return standards;
		}*/
		

})();