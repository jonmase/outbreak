(function() {
	angular.module('flu.samples')
		.factory('sampleFactory', sampleFactory);
		
	sampleFactory.$inject = ['$uibModal', 'siteFactory', 'schoolFactory'];
		
	function sampleFactory($uibModal, siteFactory, schoolFactory) {
		//Variables
		var sites = siteFactory.getSites();
		var siteIds = siteFactory.getSiteIds();
		var schools = schoolFactory.getSchools();
		var types = readTypes();

		//Samples are stored in a multidimensional object - samples[temp/saved][siteId][schoolId][childId][typeId] (type = acute or convalescent)
		var emptySamples = initializeSamples();
		var emptySamplesCounts = initializeSampleCounts();
		var samples = readSamples();
		setSampleCounts('saved', 'saved', false);	//Set the saved counts based on the saved samples
		var savedHappiness = readHappiness();	//Get the saved happiness
		var happiness = setHappiness();	//Work out the initial happiness
		var acuteSwabSamplesCollected = setAcuteSwabSamplesCollected();
		
		//Exposed Methods
		var factory = {
			checkSamples: checkSamples,
			collectAllAcuteSwabSamples: collectAllAcuteSwabSamples,
			getAcuteSwabSamplesCollected: getAcuteSwabSamplesCollected,
			getHappiness: getHappiness,
			getSamples: getSamples,
			getSampleTypes: getSampleTypes,
			selectAllOrNone: selectAllOrNone,
			setSamples: setSamples,
		}
		return factory;  
		
		//Methods
		//Note getTestSamples is out of order at the end as it is long and only used for development

		//When user clicks a sample, check whether the sample is available, and show any appropriate advice/warnings
		function checkSamples(siteId, schoolId, childId, typeId) {
			samples.temp.samples[siteId][schoolId][childId][typeId] = samples.all.samples[siteId][schoolId][childId][typeId];	//Copy the all samples value into temp samples
			
			//If user is trying to take an acute sample for a school where they are not available, alert the user and untick the box
			if(samples.temp.samples[siteId][schoolId][childId][typeId] === 1 && types[typeId] === "acute" && !schools[schoolId].acute) {
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
			var schoolId = 1;
			var typeId = 0;
			
			for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
				samples.saved.samples[siteId][schoolId][childId][typeId] = 1;
				samples.all.samples[siteId][schoolId][childId][typeId] = 1;
			}
			setSampleCounts('all', 'all');	//Count the all samples into the all counts
			setSampleCounts('saved', 'saved');	//Count the saved samples into the saved counts
		}
	
		function getAcuteSwabSamplesCollected() {
			return acuteSwabSamplesCollected;
		}
		
		function getHappiness() {
			return happiness;
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
				schools: [] 
			};
			
			//Set the counts for each site to 0
			for(var site = 0; site < sites.length; site++) {
				sampleCounts.sites[site] = 0;
			}
			sampleCounts.sites['np_convalescent'] = 0;
			sampleCounts.sites['np_acute'] = 0;
			//Set the counts for each school to 0
			for(var school = 0; school < schools.length; school++) {
				sampleCounts.schools[school] = {
					total: 0,	//total samples for the school
					acute: 0,	//total acute samples for the school
					convalescent: 0,	//total convalescent samples for the school
					//blood_acute: 0,	//blood acute samples for the school
					//blood_convalescent: 0,	//blood convlescent samples for the school
				};
				//Add a counter for each site for each school
				for(var site = 0; site < sites.length; site++) {
					sampleCounts.schools[school][site] = { total: 0 };
					for(var typeId = 0; typeId < types.length; typeId++) {
						sampleCounts.schools[school][site][types[typeId]] = 0;
					}
				}
			}
			
			return sampleCounts;
		}
		
		function initializeSamples() {
			var emptySamples = [];
			for(var siteId = 0; siteId < sites.length; siteId++) {
				emptySamples[siteId] = [];
				for(var schoolId = 0; schoolId < schools.length; schoolId++) {
					emptySamples[siteId][schoolId] = [];
					for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
						emptySamples[siteId][schoolId][childId] = [];
						for(var typeId = 0; typeId < types.length; typeId++) {
							emptySamples[siteId][schoolId][childId][typeId] = 0;
						}
					}
				}
			}
			return emptySamples;
		}
		
		function readHappiness() {
			//API: get from the DB
			var happiness = 3;
			return happiness;
		}
		
		function readSamples() {
			var samples = {
				all: {
					samples: angular.copy(emptySamples), //API: get the user's saved samples form the DB
					//samples: getTestSamples(),	//Development: get array of test samples
					counts: angular.copy(emptySamplesCounts),	//Start with the saved counts empty
				},
				saved: {
					samples: angular.copy(emptySamples), //API: get the user's saved samples form the DB
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
			if(allOrNone && types[typeId] === "acute" && !schools[schoolId].acute) {
				if(!schools[schoolId].acuteDisabled) {	//If the acute boxes haven't already been disabled for this school...
					tooLate(schoolId);	//...show warning and disable the boxes
				}
				
			}
			else {
				for(var childId = 0; childId < schools[schoolId].children.length; childId++) {
					if(samples.saved.samples[siteId][schoolId][childId][typeId] === 0) {
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

		function setAcuteSwabSamplesCollected() {
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
						for(var typeId = 0 in samples[sampleStatus].samples[siteId][schoolId][childId]) {
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
								/*if(sites[siteId].id === "blood") {	//School blood type
									samples[countStatus].counts.schools[schoolId]["blood_" + types[typeId]]++;
								}*/
								if(sites[siteId].id === "np") {
									//Count the acute swabs - needed for setting happiness
									if(types[typeId] === "convalescent") {
										samples[countStatus].counts.sites["np_convalescent"]++;
									}
									//Count the acute swabs - needed for the "it's flu" alert.
									//Note: doesn't need to be school specific, as can only collect these from one school
									else if(types[typeId] === "acute") {	 
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
			setSampleCounts('temp', 'saved', true);	//Set the saved counts based on the temp samples, and add the new samples to the saved samples array
			//API Save samples and happiness to DB, plus acutesDisabled
			savedHappiness = angular.copy(happiness);
			acuteSwabSamplesCollected = setAcuteSwabSamplesCollected();
			
			samples.temp.samples = angular.copy(emptySamples);	//Clear the temp samples array
			
			//samples.counts.saved = updateSavedCounts();	//Update the saved counts
			samples.temp.counts = angular.copy(emptySamplesCounts);	//Reset the temp counts
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
		
		function tooLate(schoolId) {
			schools[schoolId].acuteDisabled = true;
			//alert(tooLateMessage);
			$uibModal.open({
				animation: true,
				size: 'md',
				backdrop: 'static',
				templateUrl: '../../partials/modals/too-late-modal.html',
				controller: 'tooLateModalController',
				controllerAs: 'tooLateModalCtrl',
			});

			
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