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
	angular.module('flu.samples')
		.factory('sampleFactory', sampleFactory);
		
	sampleFactory.$inject = ['$uibModal', 'modalFactory', 'siteFactory', 'schoolFactory', '$resource', '$q'];
		
	function sampleFactory($uibModal, modalFactory, siteFactory, schoolFactory, $resource, $q) {
		//Variables
		var loaded = false;
		var sites, siteIds, schools, types, savedSamples, emptySamples, emptySamplesCounts, samples, savedHappiness, acuteSwabSamplesCollected;
		
		//Exposed Methods
		var factory = {
			checkSamples: checkSamples,
			collectAllAcuteSwabSamples: collectAllAcuteSwabSamples,
			getAcuteSwabSamplesCollected: getAcuteSwabSamplesCollected,
			getHappiness: getHappiness,
			getLoaded: getLoaded,
			getSamples: getSamples,
			getSampleTypes: getSampleTypes,
			loadHappiness: loadHappiness,
			loadSamples: loadSamples,
			loadTypes: loadTypes,
			selectAllOrNone: selectAllOrNone,
			setHappiness: setHappiness,
			setLoaded: setLoaded,
			setSamples: setSamples,
			setup: setup,
		}
		return factory;  
		
		//Methods
		//Note getTestSamples is out of order at the end as it is long and only used for development
		
		function setup() {
			sites = siteFactory.getSites();
			siteIds = siteFactory.getSiteIds();
			schools = schoolFactory.getSchools();
			//var types = readTypes();
			
			//Samples are stored in a multidimensional object - samples[temp/saved][siteId][schoolId][childId][typeId] (type = acute or convalescent)
			emptySamples = initializeSamples();
			emptySamplesCounts = initializeSampleCounts();
			samples = readSamples();
			setSampleCounts('saved', 'saved', false);	//Set the saved counts based on the saved samples
			//var savedHappiness = readHappiness();	//Get the saved happiness
			//savedHappiness = loadHappiness();	//Get the saved happiness
			happiness = setHappiness();	//Work out the initial happiness
			acuteSwabSamplesCollected = setAcuteSwabSamplesCollected();
			setLoaded();
		}

		//When user clicks a sample, check whether the sample is available, and show any appropriate advice/warnings
		function checkSamples(siteId, schoolId, childId, typeId) {
			samples.temp.samples[siteId][schoolId][childId][typeId] = samples.all.samples[siteId][schoolId][childId][typeId];	//Copy the all samples value into temp samples
			
			//If user is trying to take an acute sample for a school where they are not available, alert the user and untick the box
			if(samples.temp.samples[siteId][schoolId][childId][typeId] === 1 && types[typeId].stage === "acute" && !schools[schoolId].acute) {
				samples.temp.samples[siteId][schoolId][childId][typeId] = samples.all.samples[siteId][schoolId][childId][typeId] = 0;
				//$event.target.checked = false;
				tooLate(schoolId);
				return samples;
			}
			
			setSampleCounts('temp', 'temp');	//Count the temp samples into the temp counts
			setSampleCounts('all', 'all');	//Count the all samples into the all counts
		
			//If user has chosen to collect as CSF sample, pop up confirm box to make sure they really want to do this
			/*
			if(sites[siteId].id === "csf" && $event.target.checked) {
				if(!confirm("This sample is particuarly invasive and will make the children unhappy. Are you sure that this is necessary given the symptons?")) {
					samples.temp.samples[siteId][schoolId][childId][typeId] = 0;
					setSampleCounts('temp', 'temp');	//Recount the temp samples into the temp counts
					$event.target.checked = false;	//Uncheck the sample that has just been checked
					return samples;
				}
			}
			*/
			
			//if user is wanting to take both acute and convalescent samples from the same school, notify them that they will require multiple trips
			/*
			if(!schools[schoolId].returnTripOk && samples.temp.counts.schools[schoolId].acute > 0 && samples.temp.counts.schools[schoolId].convalescent > 0) {
				if(!confirm("You will have to return to the school to take both acute and convalescent samples. This will cost more time, and will make the children unhappy. Is that OK?")) {
					samples.temp.samples[siteId][schoolId][childId][typeId] = 0;
					setSampleCounts('temp', 'temp');	//Recount the temp samples into the temp counts
					$event.target.checked = false;	//Uncheck the sample that has just been checked
					schools[schoolId].returnTripOk = false;
					return samples;
				}
				else {
					schools[schoolId].returnTripOk = true;
				}
			}
			*/
			happiness = setHappiness();
			//Return the samples (no need to update counts again)
			return samples;
		};
	
		function collectAllAcuteSwabSamples() {
			var siteId = siteIds['np'];
			var schoolId = 2;
			var typeId = 1;
			var samplesToSave = {};
			samplesToSave[siteId] = {};
			samplesToSave[siteId][schoolId] = {};
			var samplesToSaveCount = 0;
			
			
			//for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
			for(var childId in schools[schoolId].children) {
				//If any of the acute swabs haven't already been saved, save them
				if(samples.saved.samples[siteId][schoolId][childId][typeId] === 0) {
					samplesToSave[siteId][schoolId][childId] = {};
					samplesToSave[siteId][schoolId][childId][typeId] = 1;
					samples.saved.samples[siteId][schoolId][childId][typeId] = 1;
					samples.all.samples[siteId][schoolId][childId][typeId] = 1;
					samplesToSaveCount++;
				}
			}
			if(samplesToSaveCount > 0) {
				setSampleCounts('all', 'all');	//Count the all samples into the all counts
				setSampleCounts('saved', 'saved');	//Count the saved samples into the saved counts
				
				var deferred = $q.defer();
				var SamplesCall = $resource(URL_MODIFIER + 'samples/save', {});
				SamplesCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, samples: samplesToSave, happiness: null},
					function(result) {
						if(typeof(result.status) !== "undefined" && result.status === 'success') {
							deferred.resolve('Acute samples collected');
						}
						else {
							deferred.reject('Acute samples collection failed (' + result.status + ")");
						}
					},
					function(result) {
						deferred.reject('Acute samples collection error (' + result.status + ')');
					}
				);
				return deferred.promise;
			}
			return 'No extra samples collected';
		}
	
		function getAcuteSwabSamplesCollected() {
			return acuteSwabSamplesCollected;
		}
		
		function getHappiness() {
			return happiness;
		}
		
		function getLoaded() { 
			return loaded;
		}
		
		function getSamples() {
			return samples; 
		}
		
		function getSampleTypes() {
			return types;
		}
			
		function initializeSampleCounts() {
			//SampleCounts stores various counts of samples that are used for testing
			var sampleCounts = { 
				total: 0,
				sites: {}, 
				schools: {} 
			};
			
			//Set the counts for each site to 0
			//for(var siteIndex = 0; siteIndex < sites.length; siteIndex++) {
			for(var siteIndex in sites) {
				sampleCounts.sites[sites[siteIndex].id] = 0;
			}
			sampleCounts.sites['np_convalescent'] = 0;
			sampleCounts.sites['np_acute'] = 0;
			//Set the counts for each school to 0
			//for(var schoolIndex = 0; schoolIndex < schools.length; schoolIndex++) {
			for(var schoolIndex in schools) {
				var schoolId = schools[schoolIndex].id;
				sampleCounts.schools[schoolId] = {
					total: 0,	//total samples for the school
					acute: 0,	//total acute samples for the school
					convalescent: 0,	//total convalescent samples for the school
					//blood_acute: 0,	//blood acute samples for the school
					//blood_convalescent: 0,	//blood convlescent samples for the school
				};
				//Add a counter for each site for each school
				//for(var siteIndex = 0; siteIndex < sites.length; siteIndex++) {
				for(var siteIndex in sites) {
					sampleCounts.schools[schoolId][sites[siteIndex].id] = { total: 0 };
					//for(var typeIndex = 0; typeIndex < types.length; typeIndex++) {
					for(var typeIndex in types) {
						sampleCounts.schools[schoolId][sites[siteIndex].id][types[typeIndex].stage] = 0;
					}
				}
			}
			
			return sampleCounts;
		}
		
		function initializeSamples() {
			var emptySamples = {};
			//for(var siteIndex = 0; siteIndex < sites.length; siteIndex++) {
			for(var siteId in sites) {
				//var siteId = sites[siteIndex].id;
				emptySamples[siteId] = {};
				if(typeof(savedSamples[siteId]) === 'undefined') {
					savedSamples[siteId] = {};
				}
				//for(var schoolIndex = 0; schoolIndex < schools.length; schoolIndex++) {
				for(var schoolId in schools) {
					//var schoolId = schools[schoolIndex].id;
					emptySamples[siteId][schoolId] = {};
					if(typeof(savedSamples[siteId][schoolId]) === 'undefined') {
						savedSamples[siteId][schoolId] = {};
					}
					//for(var childIndex = 0; childIndex < schools[schoolIndex].children.length; childIndex++) {
					for(var childId in schools[schoolId].children) {
						//var childId = schools[schoolIndex].children[childIndex].id;
						emptySamples[siteId][schoolId][childId] = {};
						if(typeof(savedSamples[siteId][schoolId][childId]) === 'undefined') {
							savedSamples[siteId][schoolId][childId] = {};
						}
						//for(var typeIndex = 0; typeIndex < types.length; typeIndex++) {
						for(var typeId in types) {
							//var typeId = types[typeIndex].id;
							emptySamples[siteId][schoolId][childId][typeId] = 0;
							if(typeof(savedSamples[siteId][schoolId][childId][typeId]) === 'undefined') {
								savedSamples[siteId][schoolId][childId][typeId] = 0;
							}
						}
					}
				}
			}
			return emptySamples;
		}
		
		function loadHappiness() {
			var deferred = $q.defer();
			var HappinessCall = $resource(URL_MODIFIER + 'Attempts/loadHappiness/:attemptId/:token.json', {attemptId: null, token: null});
			HappinessCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						savedHappiness = result.happiness;
						deferred.resolve('Happiness loaded');
					}
					else {
						deferred.reject('Happiness load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Happiness load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}

		function loadSamples() {
			var deferred = $q.defer();
			var SamplesCall = $resource(URL_MODIFIER + 'Samples/load/:attemptId/:token.json', {attemptId: null, token: null});
			SamplesCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						savedSamples = result.samples;
						if(savedSamples.length === 0) {
							savedSamples = {};
						}
						deferred.resolve('Samples loaded');
					}
					else {
						deferred.reject('Samples load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Samples load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}

		function loadTypes() {
			var deferred = $q.defer();
			var TypesCall = $resource(URL_MODIFIER + 'SampleStages/load.json', {});
			TypesCall.get({},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						types = result.stages;
						deferred.resolve('Types loaded');
					}
					else {
						deferred.reject('Types load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Types load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}

		/*function readHappiness() {
			var happiness = 3;
			return happiness;
		}*/
		
		function readSamples() {
			var samples = {
				all: {
					samples: angular.copy(savedSamples), //API: get the user's saved samples form the DB
					//samples: angular.copy(emptySamples),
					//samples: getTestSamples(),	//Development: get array of test samples
					counts: angular.copy(emptySamplesCounts),	//Start with the saved counts empty
				},
				saved: {
					samples: angular.copy(savedSamples), //API: get the user's saved samples form the DB
					//samples: angular.copy(emptySamples),
					//samples: getTestSamples(),	//Development: get array of test samples
					counts: angular.copy(emptySamplesCounts),	//Start with the saved counts empty
				},
				temp: {
					samples: angular.copy(emptySamples),
					counts: angular.copy(emptySamplesCounts),	//Start with the temp counts empty
				}
			}
			return samples;
		}
		
		function readTypes() {
			//API: Get the from the DB - seems little point, but need the types in the DB to define the assays
			types = ['acute','convalescent'];
			return types;
		}
		
		function selectAllOrNone(allOrNone, siteId, schoolId, typeId) {
			//siteId = siteId.toString();
			//schoolId = schoolId.toString();
			//typeId = typeId.toString();
			if(allOrNone && types[typeId].stage === "acute" && !schools[schoolId].acute) {
				if(!schools[schoolId].acuteDisabled) {	//If the acute boxes haven't already been disabled for this school...
					tooLate(schoolId);	//...show warning and disable the boxes
				}
			}
			else {
				//for(var childIndex = 0; childIndex < schools[schoolId].children.length; childIndex++) {
				for(var childId in schools[schoolId].children) {
					//var childId = schools[schoolId].children[childIndex].id;
					//if(samples.saved.samples[siteId][schoolId][childId][typeId] === 0) {
					if(!isSampleSet(samples.saved.samples, siteId, schoolId, childId, typeId)) {
						samples.all.samples[siteId][schoolId][childId][typeId] = allOrNone;
						samples.temp.samples[siteId][schoolId][childId][typeId] = allOrNone;
					}
				}
				setSampleCounts('temp', 'temp');	//Count the temp samples into the temp counts
				setSampleCounts('all', 'all');	//Count the all samples into the all counts
			}
			happiness = setHappiness();
			return false;
		}

		function isSampleSet(samples, siteId, schoolId, childId, typeId) {
			if(typeof(samples[siteId]) === 'undefined' || typeof(samples[siteId][schoolId]) === 'undefined' || typeof(samples[siteId][schoolId][childId]) === 'undefined' || typeof(samples[siteId][schoolId][childId][typeId]) === 'undefined' || samples[siteId][schoolId][childId][typeId] === 0 || samples[siteId][schoolId][childId][typeId] === null) {
				return false;
			}
			else {
				return true;
			}
		}
		
		function setAcuteSwabSamplesCollected() {
			//API:
			if(samples.saved.counts.sites["np_acute"] > 0) {
				if(samples.saved.counts.sites["np_acute"] >= 3) {	//If 3 (or more, though should never be more) acute swabs have been collected, that's all of them
					return "all";
				}
				else {	//Otherwise, only some of the swabs have been collected
					return "some";	
				}
			}
			else {	//None of the acute swabs have been collected
				return "none";
			}
		}
		
		function setHappiness() {
			//Note work out happiness based on temp samples, but then don't let it be higher than saved happiness
			
			//If CSF is selected anywhere, set happiness to 0
			if(samples.temp.counts.sites[siteIds['csf']] > 0) {
				happiness = 0;
				return happiness;	//No point carry on once we've got to 0
			}
			
			//Set starting happines to maximum
			happiness = 3;
			
			//If convalescent swab is selected anywhere, set happiness to 2
			if(samples.all.counts.sites['np_convalescent'] > 0) {
				happiness = 2;
			}
			
			//If any blood is being taken, set happiness to 2
			if(samples.all.counts.sites[siteIds['blood']] > 0) {
				happiness = 2;
				/*
				//If blood is being taken for a second time from a particular school, set happiness to 1
				for(var schoolId in schools) {
					if(samples.temp.counts.schools[schoolId][siteIds['blood']].total > 0 && samples.saved.counts.schools[schoolId][siteIds['blood']].total > 0) {
						happiness = 1;
					}
				}*/
			}
			
			//New happines can't be higher than savedHappiness
			if(happiness > savedHappiness) {
				happiness = savedHappiness;
			}

			return happiness;
		}
		
		function setLoaded() { 
			loaded = true;
		}
		
		function setSampleCounts(sampleStatus, countStatus, copyToSamples) {
			if(!sampleStatus) { sampleStatus = 'temp'; }
			if(!countStatus) { countStatus = 'temp'; }
			if(!copyToSamples) { copyToSamples = false; }
			
			//If status are the same, reset the count before adding the new count
			if(sampleStatus === countStatus) {
				samples[countStatus].counts = angular.copy(emptySamplesCounts);	//reset sample counts
			}

			for(var siteId in samples[sampleStatus].samples) {
				for(var schoolId in samples[sampleStatus].samples[siteId]) {
					for(var childId in samples[sampleStatus].samples[siteId][schoolId]) {
						for(var typeId in samples[sampleStatus].samples[siteId][schoolId][childId]) {
							if(samples[sampleStatus].samples[siteId][schoolId][childId][typeId] === 1) {
								if(copyToSamples) {
									samples[countStatus].samples[siteId][schoolId][childId][typeId] = 1;
								}
								
								//Set up techniques array for child
								//testSamples[siteId][schoolId][childId][typeId] = { sample: 1, techniques: [] };
								//for(var technique in techniques) {	//Set up arrays for recoridng whether each technique will be used for that sample
								//	testSamples[siteId][schoolId][childId][typeId].techniques[technique] = 0;
								//}
								
								//Increment the appropriate counts
								samples[countStatus].counts.total++;	//Site count
								samples[countStatus].counts.sites[siteId]++;	//Site count
								samples[countStatus].counts.schools[schoolId].total++;	//School total
								samples[countStatus].counts.schools[schoolId][types[typeId]]++;	//School type
								samples[countStatus].counts.schools[schoolId][siteId].total++;	//School site
								samples[countStatus].counts.schools[schoolId][siteId][types[typeId]]++;	//School site and type
								/*if(sites[siteId].code === "blood") {	//School blood type
									samples[countStatus].counts.schools[schoolId]["blood_" + types[typeId]]++;
								}*/
								if(sites[siteId].code === "np") {
									//Count the acute swabs - needed for setting happiness
									if(types[typeId].stage === "convalescent") {
										samples[countStatus].counts.sites["np_convalescent"]++;
									}
									//Count the acute swabs - needed for the "it's flu" alert.
									//Note: doesn't need to be school specific, as can only collect these from one school
									else if(types[typeId].stage === "acute") {	 
										samples[countStatus].counts.sites["np_acute"]++;
									}
								}
							}
							//else {
							//	testSamples[siteId][schoolId][childId][typeId] = { sample: 0 };
							//}
						}
					}
				}
			}
		}
			
		function setSamples() {
			//API Save samples and happiness to DB, plus acutesDisabled
			var deferred = $q.defer();
			var SamplesCall = $resource('../../samples/save', {});
			SamplesCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, samples: samples.temp.samples, happiness: happiness}, 
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						setSampleCounts('temp', 'saved', true);	//Set the saved counts based on the temp samples, and add the new samples to the saved samples array
						savedHappiness = angular.copy(happiness);
						acuteSwabSamplesCollected = setAcuteSwabSamplesCollected();

						samples.temp.samples = angular.copy(emptySamples);	//Clear the temp samples array
						
						//samples.counts.saved = updateSavedCounts();	//Update the saved counts
						samples.temp.counts = angular.copy(emptySamplesCounts);	//Reset the temp counts
						deferred.resolve('Samples collected');
					}
					else {
						deferred.reject('Samples collection failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Samples collection error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}

		function tooLate(schoolId) {
			//API: Set this in the DB
			//var deferred = $q.defer();
			var TooLateCall = $resource(URL_MODIFIER + 'schools/tooLate', {});
			TooLateCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, schoolId: schoolId},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						schools[schoolId].acuteDisabled = true;
						console.log('Too late saved');
						//deferred.resolve('Too late saved');
						$uibModal.open({
							animation: true,
							size: 'md',
							backdrop: 'static',
							templateUrl: '../../partials/modals/too-late-modal.html',
							controller: 'tooLateModalController',
							controllerAs: 'tooLateModalCtrl',
						});
					}
					else {
						console.log('Too late save failed (' + result.status + ")");
						$uibModal.open(modalFactory.getErrorModalOptions());
						//deferred.reject('Too late save failed (' + result.status + ")");
					}
				},
				function(result) {
					console.log('Too late save error (' + result.status + ')');
					$uibModal.open(modalFactory.getErrorModalOptions());
					//deferred.reject('Too late save error (' + result.status + ')');
				}
			);
			
			//alert(tooLateMessage);
		}
	
		function getTestSamples() {
			var testSamples = [
				[	//np
					[	//school 
						[0,0],	//ja (a, c)
						[0,0],	//js (a, c)
						[0,0],	//kt (a, c)
						[0,0],	//dl (a, c)
					],
					[	//school 2
						[1,0],	//jb (a, c)
						[1,0],	//ai (a, c)
						[1,0],	//jo (a, c)
					],
				],
				[	//blood
					[	//school 
						[0,1],	//ja (a, c)
						[0,1],	//js (a, c)
						[0,1],	//kt (a, c)
						[0,1],	//dl (a, c)
					],
					[	//school 2
						[1,1],	//jb (a, c)
						[1,1],	//ai (a, c)
						[1,1],	//jo (a, c)
					],
				],
				[	//csf
					[	//school 
						[0,0],	//ja (a, c)
						[0,0],	//js (a, c)
						[0,0],	//kt (a, c)
						[0,0],	//dl (a, c)
					],
					[	//school 2
						[0,1],	//jb (a, c)
						[0,1],	//ai (a, c)
						[0,1],	//jo (a, c)
					],
				],
			];
			
			//Test samples with all collected
			/*var testSamples = [
				[	//np
					[	//school 
						[0,1],	//ja (a, c)
						[0,1],	//js (a, c)
						[0,1],	//kt (a, c)
						[0,1],	//dl (a, c)
					],
					[	//school 2
						[1,1],	//jb (a, c)
						[1,1],	//ai (a, c)
						[1,1],	//jo (a, c)
					],
				],
				[	//blood
					[	//school 
						[0,1],	//ja (a, c)
						[0,1],	//js (a, c)
						[0,1],	//kt (a, c)
						[0,1],	//dl (a, c)
					],
					[	//school 2
						[1,1],	//jb (a, c)
						[1,1],	//ai (a, c)
						[1,1],	//jo (a, c)
					],
				],
				[	//csf
					[	//school 
						[0,1],	//ja (a, c)
						[0,1],	//js (a, c)
						[0,1],	//kt (a, c)
						[0,1],	//dl (a, c)
					],
					[	//school 2
						[1,1],	//jb (a, c)
						[1,1],	//ai (a, c)
						[1,1],	//jo (a, c)
					],
				],
			];*/
			return testSamples;
		}
		
		/*
		//Update the sample counts - old version
		function setCounts(siteId, schoolId, type, action) {
			if(action === "+") { 
				vm.sampleCounts.sites[siteId]++;	//Add one to the site count
				if(vm.subsections[siteId].id === 'blood') {
					vm.sampleCounts.schools[schoolId]['blood_' + type]++;
				}
				vm.sampleCounts.schools[schoolId][siteId]++;	//Add one to the site count for the school
				vm.sampleCounts.schools[schoolId][type]++;	//Add one to the type (acute or conv) count
				vm.sampleCounts.schools[schoolId]['total']++;	//Add one to the school total
			}
			else { 
				vm.sampleCounts.sites[siteId]--;	//Add one to the site count
				if(vm.subsections[siteId].id === 'blood') {
					vm.sampleCounts.schools[schoolId]['blood_' + type]--;
				}
				vm.sampleCounts.schools[schoolId][siteId]--;	//Subtract one from the site count for the school
				vm.sampleCounts.schools[schoolId][type]--;	//Subtract one from the type (acute or conv) count
				vm.sampleCounts.schools[schoolId]['total']--;	//Subtract one from the school total
			}
		};
		*/
	}
})();