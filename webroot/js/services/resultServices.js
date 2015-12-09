(function() {
	angular.module('flu.results')
		.factory('resultFactory', resultFactory);
		
	resultFactory.$inject = ['schoolFactory', 'techniqueFactory', 'assayFactory', '$resource', '$q'];
		
	function resultFactory(schoolFactory, techniqueFactory, assayFactory, $resource, $q) {
		//Variables
		var sections, assays, notes, defaultInitialTechnique, currentTechniqueId, techniqueChangedManually
		
		//Exposed Methods
		var factory = {
			getCurrentTechniqueId: getCurrentTechniqueId,
			getNotes: getNotes,
			//getResults: getResults,
			getSections: getSections,
			loadNotes: loadNotes,
			readQuickVue: readQuickVue,
			setCurrentTechniqueId: setCurrentTechniqueId,
			setDisabledTechniques: setDisabledTechniques,
			setNote: setNote,
			setup: setup,
		}
		return factory;
		
		//Methods
		function setup() {
			sections = readSections();
			assays = assayFactory.getAssays();
			//var results = readResults();
			//notes = readNotes();
			defaultInitialTechnique = 8;	//QuickVue
			currentTechniqueId = angular.copy(defaultInitialTechnique);
			techniqueChangedManually = false;
			setDisabledTechniques(true);
			sections[currentTechniqueId].active = true;
		}
		
		function getCurrentTechniqueId() {
			return currentTechniqueId;
		}
		function getNotes() {
			return notes;
		}
		/*function getResults() {
			return results;
		}*/
		function getSections() {
			return sections;
		}
		
		function loadNotes() {
			var deferred = $q.defer();
			var NotesCall = $resource('../../notes/load/:attemptId.json', {attemptId: '@id'});
			NotesCall.get({attemptId: ATTEMPT_ID}, function(result) {
				notes = result.notes;
				deferred.resolve('Notes loaded');
				deferred.reject('Notes not loaded');
			});
			return deferred.promise;
		}
		
		/*function readNotes() {
			//API: Get these from the DB
			var notes = {};
			for(var i = 0; i < sections.length; i++) {
				notes[sections[i].id] = "";
			}
			return notes;
		}*/
		
		//Add quickvue
		function readQuickVue() {
			var quickvue = {
				id: 'quickvue',
				menu: 'QuickVue',
				name: 'QuickVue',
			};
			return quickvue;
		}

		function readSections() {
			var sections = angular.copy(techniqueFactory.readTechniques('results'));	//Get lab only but not revision only techniques
			//sections.quickvue = readQuickVue();
			sections.xflu = techniqueFactory.getFluExtra();	//Add additional info
			
			return sections;
		}
		
		function setCurrentTechniqueId(techniqueId) {
			currentTechniqueId = techniqueId;
			techniqueChangedManually = true;
		}
		
		function setDisabledTechniques(setCurrentTechnique) {
			var initialTechniqueId = angular.copy(defaultInitialTechnique);
			//for(var i = 0; i < sections.length; i++) {
			for(var techniqueId in sections) {
				if(typeof(assays.saved.counts[techniqueId]) !== "undefined") {
					//If this section has results, make it the initial technique, if initial technique has not already been changed
					if(assays.saved.counts[techniqueId].total > 0) {
						sections[techniqueId].disabled = false;	//Make sure section is not disabled
						if(!techniqueChangedManually && initialTechniqueId === defaultInitialTechnique) {
							//Set all sections to inactive
							for(var t = 0; t < sections.length; t++) {
								sections[t].active = false;
							}
							sections[techniqueId].active = true;	//Set this section to active
							initialTechniqueId = angular.copy(techniqueId);	//Update the initial technique, so it is no longer equal to defaultInitialTechnique, so we never get here again
							if(setCurrentTechnique) {
								currentTechniqueId = angular.copy(techniqueId);
							}
						}
					}
					else {
						//Disable normal technique sections (i.e. those with results defined in the technique) with no results
						if(typeof(sections[techniqueId].technique_results) !== "undefined") {
							sections[techniqueId].disabled = true;
						}
					}
				}
			}
		}
		
		function setNote(techniqueId) {
			//API: Save notes to DB
			//TODO: Call this - autosave? on blur? save button? 
			var deferred = $q.defer();
			var NotesCall = $resource('../../notes/save', {});
			NotesCall.save({}, {attemptId: ATTEMPT_ID, techniqueId: techniqueId, note: notes[techniqueId].note}, function(result) {
				var message = result.message;
				deferred.resolve(message);
				deferred.reject("Error: " + message);
			});
			return deferred.promise;
		}
		
		/*function readResults() {
			//Replaced values with images
			//Results for each assay - results[techniqueId][siteId][schoolId][childId][typeId]
			var results = [
				[	//PFU
					[	//np
						[	//school 
							[null,'<1 PFU/ml'],	//ja (a, c)
							[null,'<1 PFU/ml'],	//js (a, c)
							[null,'<1 PFU/ml'],	//kt (a, c)
							[null,'<1 PFU/ml'],	//dl (a, c)
						],
						[	//school 2
							['>100 PFU/ml','<1 PFU/ml'],	//jb (a, c)
							['>100 PFU/ml','<1 PFU/ml'],	//ai (a, c)
							['>100 PFU/ml','<1 PFU/ml'],	//jo (a, c)
						],
					],
					[	//blood
						[	//school 
							['<1 PFU/ml','<1 PFU/ml'],	//ja (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//js (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//kt (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//dl (a, c)
						],
						[	//school 2
							['<1 PFU/ml','<1 PFU/ml'],	//jb (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//ai (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//jo (a, c)
						],
					],
					[	//csf
						[	//school 
							[null,'<1 PFU/ml'],	//ja (a, c)
							[null,'<1 PFU/ml'],	//js (a, c)
							[null,'<1 PFU/ml'],	//kt (a, c)
							[null,'<1 PFU/ml'],	//dl (a, c)
						],
						[	//school 2
							['<1 PFU/ml','<1 PFU/ml'],	//jb (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//ai (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//jo (a, c)
						],
					],
					[	//urine
						[	//school 
							[null,'<1 PFU/ml'],	//ja (a, c)
							[null,'<1 PFU/ml'],	//js (a, c)
							[null,'<1 PFU/ml'],	//kt (a, c)
							[null,'<1 PFU/ml'],	//dl (a, c)
						],
						[	//school 2
							['<1 PFU/ml','<1 PFU/ml'],	//jb (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//ai (a, c)
							['<1 PFU/ml','<1 PFU/ml'],	//jo (a, c)
						],
					],
				],
				[	//HA
					[	//np
						[	//school 
							[null,'<1/64'],	//ja (a, c)
							[null,'<1/64'],	//js (a, c)
							[null,'<1/64'],	//kt (a, c)
							[null,'<1/64'],	//dl (a, c)
						],
						[	//school 2
							['1/128','<1/64'],	//jb (a, c)
							['1/128','<1/64'],	//ai (a, c)
							['1/128','<1/64'],	//jo (a, c)
						],
					],
					[	//blood
						[	//school 
							[null,'<1/64'],	//ja (a, c)
							[null,'<1/64'],	//js (a, c)
							[null,'<1/64'],	//kt (a, c)
							[null,'<1/64'],	//dl (a, c)
						],
						[	//school 2
							['<1/64','<1/64'],	//jb (a, c)
							['<1/64','<1/64'],	//ai (a, c)
							['<1/64','<1/64'],	//jo (a, c)
						],
					],
					[	//csf
						[	//school 
							[null,'<1/64'],	//ja (a, c)
							[null,'<1/64'],	//js (a, c)
							[null,'<1/64'],	//kt (a, c)
							[null,'<1/64'],	//dl (a, c)
						],
						[	//school 2
							['<1/64','<1/64'],	//jb (a, c)
							['<1/64','<1/64'],	//ai (a, c)
							['<1/64','<1/64'],	//jo (a, c)
						],
					],
					[	//urine
						[	//school 
							[null,'<1/64'],	//ja (a, c)
							[null,'<1/64'],	//js (a, c)
							[null,'<1/64'],	//kt (a, c)
							[null,'<1/64'],	//dl (a, c)
						],
						[	//school 2
							['<1/64','<1/64'],	//jb (a, c)
							['<1/64','<1/64'],	//ai (a, c)
							['<1/64','<1/64'],	//jo (a, c)
						],
					],
				],
				[	//HAI
					[	//np
						[	//school 
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//ja (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//js (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//kt (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//jb (a, c)
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//ai (a, c)
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//jo (a, c)
						],
					],
					[	//blood
						[	//school 
							[null,'H1: 1/1024; H3: 1/64; H5: <1/64'],	//ja (a, c)
							[null,'H1: 1/512; H3: 1/64; H5: <1/64'],	//js (a, c)
							[null,'H1: 1/1024; H3: 1/128; H5: <1/64'],	//kt (a, c)
							[null,'H1: 1/1024; H3: 1/128; H5: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['H1: 1/64; H3: 1/128; H5: <1/64','H1: 1/512; H3: 1/128; H5: <1/64'],	//jb (a, c)
							['H1: 1/128; H3: 1/64; H5: <1/64','H1: 1/1024; H3: 1/64; H5: <1/64'],	//ai (a, c)
							['H1: 1/64; H3: 1/128; H5: <1/64','H1: 1/512; H3: 1/128; H5: <1/64'],	//jo (a, c)
						],
					],
					[	//csf
						[	//school 
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//ja (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//js (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//kt (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//jb (a, c)
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//ai (a, c)
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//jo (a, c)
						],
					],
					[	//urine
						[	//school 
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//ja (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//js (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//kt (a, c)
							[null,'H1: <1/64; H3: <1/64; H5: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//jb (a, c)
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//ai (a, c)
							['H1: <1/64; H3: <1/64; H5: <1/64','H1: <1/64; H3: <1/64; H5: <1/64'],	//jo (a, c)
						],
					],
				],
				[	//qRT-PCRH
					[	//np
						[	//school 1
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ja (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//js (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//kt (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//dl (a, c)
						],
						[	//school 2
							['H1: +ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jb (a, c)
							['H1: +ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ai (a, c)
							['H1: +ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jo (a, c)
						],
					],
					[	//blood
						[	//school 1
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ja (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//js (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//kt (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//dl (a, c)
						],
						[	//school 2
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jb (a, c)
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ai (a, c)
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jo (a, c)
						],
					],
					[	//csf
						[	//school 
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ja (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//js (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//kt (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//dl (a, c)
						],
						[	//school 2
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jb (a, c)
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ai (a, c)
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jo (a, c)
						],
					],
					[	//urine
						[	//school 
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ja (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//js (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//kt (a, c)
							[null,'H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//dl (a, c)
						],
						[	//school 2
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jb (a, c)
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//ai (a, c)
							['H1: -ve; H2: -ve; H5: -ve; H7: -ve','H1: -ve; H2: -ve; H5: -ve; H7: -ve'],	//jo (a, c)
						],
					],
				],
				[	//qRT-PCRN
					[	//np
						[	//school 
							[null,'N1: -ve; N2: -ve'],	//ja (a, c)
							[null,'N1: -ve; N2: -ve'],	//js (a, c)
							[null,'N1: -ve; N2: -ve'],	//kt (a, c)
							[null,'N1: -ve; N2: -ve'],	//dl (a, c)
						],
						[	//school 2
							['N1: -ve; N2: +ve','N1: -ve; N2: -ve'],	//jb (a, c)
							['N1: -ve; N2: +ve','N1: -ve; N2: -ve'],	//ai (a, c)
							['N1: -ve; N2: +ve','N1: -ve; N2: -ve'],	//jo (a, c)
						],
					],
					[	//blood
						[	//school 
							[null,'N1: -ve; N2: -ve'],	//ja (a, c)
							[null,'N1: -ve; N2: -ve'],	//js (a, c)
							[null,'N1: -ve; N2: -ve'],	//kt (a, c)
							[null,'N1: -ve; N2: -ve'],	//dl (a, c)
						],
						[	//school 2
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//jb (a, c)
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//ai (a, c)
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//jo (a, c)
						],
					],
					[	//csf
						[	//school 
							[null,'N1: -ve; N2: -ve'],	//ja (a, c)
							[null,'N1: -ve; N2: -ve'],	//js (a, c)
							[null,'N1: -ve; N2: -ve'],	//kt (a, c)
							[null,'N1: -ve; N2: -ve'],	//dl (a, c)
						],
						[	//school 2
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//jb (a, c)
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//ai (a, c)
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//jo (a, c)
						],
					],
					[	//urine
						[	//school 
							[null,'N1: -ve; N2: -ve'],	//ja (a, c)
							[null,'N1: -ve; N2: -ve'],	//js (a, c)
							[null,'N1: -ve; N2: -ve'],	//kt (a, c)
							[null,'N1: -ve; N2: -ve'],	//dl (a, c)
						],
						[	//school 2
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//jb (a, c)
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//ai (a, c)
							['N1: -ve; N2: -ve','N1: -ve; N2: -ve'],	//jo (a, c)
						],
					],
				],
				[	//ELISA
					[	//np
						[	//school 
							[null,'N1: <1/64; N2: <1/64'],	//ja (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//js (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//kt (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//jb (a, c)
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//ai (a, c)
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//jo (a, c)
						],
					],
					[	//blood
						[	//school 
							[null,'N1: 1/64; N2: 1/1024'],	//ja (a, c)
							[null,'N1: <1/64; N2: 1/512'],	//js (a, c)
							[null,'N1: 1/64; N2: 1/512'],	//kt (a, c)
							[null,'N1: 1/64; N2: 1/1024'],	//dl (a, c)
						],
						[	//school 2
							['N1: 1/64; N2: 1/128','N1: 1/64; N2: 1/1024'],	//jb (a, c)
							['N1: <1/64; N2: 1/64','N1: <1/64; N2: 1/512'],	//ai (a, c)
							['N1: 1/64; N2: 1/64','N1: 1/64; N2: 1/512'],	//jo (a, c)
						],
					],
					[	//csf
						[	//school 
							[null,'N1: <1/64; N2: <1/64'],	//ja (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//js (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//kt (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//jb (a, c)
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//ai (a, c)
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//jo (a, c)
						],
					],
					[	//urine
						[	//school 
							[null,'N1: <1/64; N2: <1/64'],	//ja (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//js (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//kt (a, c)
							[null,'N1: <1/64; N2: <1/64'],	//dl (a, c)
						],
						[	//school 2
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//jb (a, c)
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//ai (a, c)
							['N1: <1/64; N2: <1/64','N1: <1/64; N2: <1/64'],	//jo (a, c)
						],
					],
				],
			];
			return results;
		}*/
	}
})();