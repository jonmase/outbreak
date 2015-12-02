(function() {
	angular.module('flu.lab')
		.value('moneyCutoff', 1)
		.value('timeCutoff', 1)
		.factory('assayFactory', assayFactory);
	
	assayFactory.$inject = ['techniqueFactory', 'siteFactory', 'schoolFactory', 'sampleFactory', 'progressFactory', 'lockFactory'];
	
	function assayFactory(techniqueFactory, siteFactory, schoolFactory, sampleFactory, progressFactory, lockFactory) {
		//Variables
		var techniques = techniqueFactory.getTechniques('lab');
		var currentTechniqueId = 0;
		var samples = sampleFactory.getSamples();
		var sites = siteFactory.getSites();
		var schools = schoolFactory.getSchools();
		var types = sampleFactory.getSampleTypes();
		var standards = readStandards();
		var requiredTests = readRequiredTests();
		var resources = progressFactory.getResources();
		var activeTabs = [];

		//The assays that the user has performed - assays[temp/saved][standards/samples/counts][techniqueId][siteId][schoolId][childId][typeId]
		var emptyStandardsForTechnique = readEmptyStandardsForTechnique();
		var emptyAssaysForTechnique = readEmptyAssaysForTechnique();
		var emptyCountsForTechnique = readEmptyCountsForTechnique();
		var assays = initializeAssays();

		//Exposed Methods
		var factory = {
			getActiveTabs: getActiveTabs,
			getAssays: getAssays,
			getCurrentTechniqueId: getCurrentTechniqueId,
			getStandards: getStandards,
			getRequiredTests: getRequiredTests,
			resetActiveTabs: resetActiveTabs,
			selectAllOrNoneBySite: selectAllOrNoneBySite,
			selectAllOrNoneByType: selectAllOrNoneByType,
			setAssayCount: setAssayCount,
			setAssays: setAssays,
			setCurrentTechniqueId: setCurrentTechniqueId,
			setLabComplete: setLabComplete,
			setTab: setTab,
		}
		return factory;
		
		//Methods

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
			var savedAssays = readSavedAssays();
			var allAssays = angular.copy(savedAssays);
			var tempAssays = readEmptyAssays();
			
			var assays = {
				all: allAssays,
				saved: savedAssays,
				temp: tempAssays
			};
			return assays;
		}
				
		function readEmptyAssays() {
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
		}
		
		function readEmptyAssaysForTechnique() {
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
		}
			
		function readRequiredTests() {
			//API: Store this in the DB somehow?? Leave for now
			var requiredTests = {
				h: [],
				n: [],
			};
			//Technique IDs
			var haiId = 2;
			var pcrhId = 3;
			var pcrnId = 4;
			var elisaId = 5;
			
			//Site IDs
			var npId = 0;
			var bloodId = 1;
			
			//School ID
			var schoolId = 1;

			requiredTests.h[haiId] = {};
			requiredTests.h[pcrhId] = {};
			requiredTests.n[elisaId] = {};
			requiredTests.n[pcrnId] = {};
			
			var schools = schoolFactory.getSchools();
			
			for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
				//To Identify H:
				//HAI - Serum, Acute and Convalescent for School 2 children
				requiredTests.h[haiId]["" + bloodId + schoolId + childId + "0"] = 0;
				requiredTests.h[haiId]["" + bloodId + schoolId + childId + "1"] = 0;
				//or PCRH - NP Swab, Acute for School 2 children
				requiredTests.h[pcrhId]["" + npId + schoolId + childId + "0"] = 0;
				
				//To Identify N:
				//ELISA - Serum, Acute and Convalescent for School 2 children
				requiredTests.n[elisaId]["" + bloodId + schoolId + childId + "0"] = 0;
				requiredTests.n[elisaId]["" + bloodId + schoolId + childId + "1"] = 0;
				//or PCRN - NP Swab, Acute for School 2 children
				requiredTests.n[pcrnId]["" + npId + schoolId + childId + "0"] = 0;
			}

			return requiredTests;
		}

		function readSavedAssays() {
			//API: get saved assays from the DB
			//Also need to generate the counts of the saved assays
			
			//For Dev, just use empty assays
			var savedAssays = readEmptyAssays();
			return savedAssays;
		}

		function readStandards() {
			//API: Get these from the DB?
			//Note: Don't need to specify results, the image links will be automatically generated
			var standards = [
				{
					id: 'ash1',
					name: 'Antiserum H1',
					/*results: [
						'100 PFU/ml',	//pfu
						'1/1024',	//HA
						'1/512',	//HAI
						'H1: -ve; H2: -ve; H5: -ve; H7: -ve',	//PCRH
						'N1: -ve; N2: -ve',	//PCRN
						'1/1024',	//ELISA
					],*/
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
				for(var t = 0; t < techniques.length; t++) {
					activeTabs[t] = angular.copy(activeTab);
				}
				//return activeTabs;
			}
		}

		function selectAllOrNoneBySite(allOrNone, techniqueId, siteId) {
			if(siteId === 'standards') {
				for(var standardId = 0; standardId < standards.length; standardId++) {
					if(assays.saved.standards[techniqueId][standardId] === 0) {
						assays.all.standards[techniqueId][standardId] = allOrNone;
						assays.temp.standards[techniqueId][standardId] = allOrNone;
					}
				}
			}
			else {
				for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						for(var typeId = 0; typeId < types.length; typeId++) {
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
			for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
				if(assays.saved.samples[techniqueId][siteId][schoolId][childId][typeId] === 0 && samples.saved.samples[siteId][schoolId][childId][typeId] === 1) {
					assays.all.samples[techniqueId][siteId][schoolId][childId][typeId] = allOrNone;
					assays.temp.samples[techniqueId][siteId][schoolId][childId][typeId] = allOrNone;
				}
			}
			setAssayCount(techniqueId);
		}		
		
		function setAssayCount(techniqueId) {
			assays.temp.counts[techniqueId] = angular.copy(emptyCountsForTechnique);	//Reset the counts for this technique
			
			//Count all of the standards in the current assay
			for(var standardId = 0; standardId < standards.length; standardId++) {
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
		
		function setAssays(techniqueId) {
			//API: Save assays performed
			//Add the tempCount to the permanent count and reset the tempCount for the technique
			assays.saved.counts[techniqueId].total += assays.temp.counts[techniqueId].total;
			assays.saved.counts[techniqueId].standards += assays.temp.counts[techniqueId].standards;
			for(var siteId = 0; siteId < sites.length; siteId++) {
				assays.saved.counts[techniqueId].sites[siteId].total += assays.temp.counts[techniqueId].sites[siteId].total;
				for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					assays.saved.counts[techniqueId].sites[siteId].schools[schoolId].total += assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].total;
					for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						assays.saved.counts[techniqueId].sites[siteId].schools[schoolId].children[childId] += assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].children[childId];
					}
					for(var typeId = 0; typeId < types.length; typeId++) {
						assays.saved.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId] += assays.temp.counts[techniqueId].sites[siteId].schools[schoolId].types[typeId];
					}
				}
			}
			assays.temp.counts[techniqueId] = angular.copy(emptyCountsForTechnique);
			
			//Loop through the assays.temp.samples array for this technique and add each to the assays.saved.samples array, then reset assays.temp.samples
			for(var siteId = 0; siteId < assays.temp.samples[techniqueId].length; siteId++) {
				for(var schoolId = 0; schoolId < assays.temp.samples[techniqueId][siteId].length; schoolId++) {
					for(var childId = 0; childId < assays.temp.samples[techniqueId][siteId][schoolId].length; childId++) {
						for(var typeId = 0; typeId < assays.temp.samples[techniqueId][siteId][schoolId][childId].length; typeId++) {
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
			for(var standardId = 0; standardId < assays.temp.standards[techniqueId].length; standardId++) {
				if(assays.temp.standards[techniqueId][standardId] === 1) {
					assays.saved.standards[techniqueId][standardId] = 1;
				}
			}
			assays.temp.standards[techniqueId] = angular.copy(emptyStandardsForTechnique);
		}
		
		function setCurrentTechniqueId(techniqueId) {
			currentTechniqueId = techniqueId;
		}

		function setLabComplete(techniqueId) {
			var progress = progressFactory.getProgress();
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
						lockFactory.setComplete('hidentified');
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
						lockFactory.setComplete('nidentified');
						break;	//No need to check other techniques if N has been identified
					}
				}
			}
			
			//If lab section is not already marked as complete, mark it as such
			if(!progress.lab) {
				lockFactory.setComplete('lab');
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
})();