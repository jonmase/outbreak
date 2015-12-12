(function() {
	angular.module('flu.techniques')
		.factory('techniqueFactory', techniqueFactory);
		
	techniqueFactory.$inject = ['progressFactory', 'lockFactory', '$resource', '$q'];

	function techniqueFactory(progressFactory, lockFactory, $resource, $q) {
		//Variables
		var fluExtra = readFluExtra();
		var techniques, labTechniques, researchTechniques, revisionTechniques, usefulTechniques;
		var currentRevisionTechniqueId = 1;
		var currentResearchTechniqueId = 1;
		//var loaded = false;
			
		//Exposed Methods
		var factory = {
			getCurrentTechniqueId: getCurrentTechniqueId,
			getFluExtra: getFluExtra,
			getLabTechnique: getLabTechnique,
			getLoaded: getLoaded,
			getTechniques: getTechniques,
			getUsefulTechniques: getUsefulTechniques,
			loadTechniques: loadTechniques,
			loadResearchTechniques: loadResearchTechniques,
			loadUsefulTechniques: loadUsefulTechniques,
			readTechniques: readTechniques,
			setCurrentTechniqueId: setCurrentTechniqueId,
			setLoaded: setLoaded,
			setRevisionComplete: setRevisionComplete,
			setUsefulTechnique: setUsefulTechnique,
		}
		return factory;
		
		//Methods
		function getCurrentTechniqueId(sectionId) { 
			if(sectionId === 'research') {
				return currentResearchTechniqueId;
			}
			else if(sectionId === 'revision') {
				return currentRevisionTechniqueId;
			}
		}
		
		function getFluExtra() { 
			return fluExtra;
		}
		
		function getLabTechnique(techniqueId) { 
			return labTechniques[techniqueId];
		}
		
		function getLoaded() { 
			return loaded;
		}
		
		function getTechniques(sectionId) { 
			if(sectionId === 'lab') {
				return labTechniques;
			}
			else if(sectionId === 'research') {
				return researchTechniques;
			}
			else if(sectionId === 'revision') {
				return revisionTechniques;
			}
		}
		
		function getUsefulTechniques() { 
			return usefulTechniques;
		}
		
		function loadTechniques() {
			//API: get these from the DB? [constant]
			var deferred = $q.defer();
			var Techniques = $resource('../../techniques/load.json', {});
			Techniques.get({}, function(result) {
				techniques = result.techniques;
				labTechniques = readLabTechniques();
				revisionTechniques = readRevisionTechniques();
				deferred.resolve('Techniques loaded');
				deferred.reject('Techniques not loaded');
			});
			return deferred.promise;
		}
		
		function loadResearchTechniques() {
			//API: get these from the DB? [constant]
			var deferred = $q.defer();
			var Techniques = $resource('../../researchTechniques/load.json', {});
			Techniques.get({}, function(result) {
				researchTechniques = result.techniques;
				//researchTechniques.push(fluExtra);
				researchTechniques.xflu = fluExtra;
				deferred.resolve('Research Techniques loaded');
				deferred.reject('Research Techniques not loaded');
			});
			return deferred.promise;
		}
		
		function loadUsefulTechniques() { 
			//API: get this from the DB - this will be saved for each attempt
			var deferred = $q.defer();
			var Useful = $resource('../../techniqueUsefulness/load/:attemptId.json', {attemptId: '@id'});
			Useful.get({attemptId: ATTEMPT_ID}, function(result) {
				usefulTechniques = result.usefulness;
				deferred.resolve('Technique Usefulness loaded');
				deferred.reject('Technique Usefulness not loaded');
			});
			return deferred.promise;
			
			//Development: start with an empty array
			//return []; 
			//Testing: leave only the first technique needing to be selected
			//return [null,1,1,0,0];
		}

		function readFluExtra() { 
			var fluExtra = 	{
				id: 'xflu',
				menu: 'Extra Info',
				name: 'Extra Influenza Information',
				video: null,
				content: '<ul><li><a href="http://www.info.gov.hk/info/flu/eng/home.htm" target="_blank">Government of Hong Kong SAR (PRC) Avian Influenza</a></li><li><a href="http://www.who.int/influenza/en/" target="_blank">World Health Organization Influenza resources</a></li><li><a href="http://www.cdc.gov/flu/" target="_blank">US Centers for Disease Control Influenza resources</a></li><li><a href="https://www.gov.uk/government/statistics/weekly-national-flu-reports" target="_blank">Public Health England: Weekly national flu reports</a></li></ul>',
				revision_only: false,
				lab_only: true,
				infoOnly: true,
			};
			return fluExtra;
		}
		
		function readLabTechniques() { 
			var labTechniques = angular.copy(readTechniques('lab'));	//Include lab only but not revision only
			//labTechniques.push(readFluExtra());
			labTechniques.xflu = readFluExtra();
			return labTechniques;
		}

		function readResearchTechniques() { 
			//API: get these from the DB? [constant]
			return researchTechniques;
		}
		
		function readRevisionExtra() { 
			var revisionExtra = {
				id: 'xrevision',
				menu: 'Lecture Notes',
				name: 'Lecture Notes',
				video: null,
				content: '<ul><li><a href="../../files/WSJames_Flu_Lecture_Notes.pdf" target="_blank">WS James Lecture notes on Influenza HA, antigenic variation and vaccines (.pdf, 6.59MB)</a></li><li><a href="../../files/EFodor_Influenza_Viruses_Part_I.pdf" target="_blank">E Fodor Lecture notes on Influenza viruses, Part I (.pdf, 2.90MB)</a></li><li><a href="../../files/EFodor_Influenza_Viruses_Part_II.pdf" target="_blank">E Fodor Lecture notes on Influenza viruses, Part II (.pdf, 1.98MB)</a></li></ul>',
				infoOnly: true,
			};
			return revisionExtra;
		}
		
		function readRevisionTechniques() { 
			var revisionTechniques = angular.copy(readTechniques('revision'));	//Include revision only but not lab only
			//revisionTechniques.push(readRevisionExtra());
			revisionTechniques.xrevision = readRevisionExtra();
			return revisionTechniques;
		}

		function readTechniques(section) { 
				//Ensure showRevisionOnly and labOnly are booleans, defaulting to false if not set
				//if(showRevisionOnly) { showRevisionOnly = true; } else { showRevisionOnly = false; }
				//if(showLabOnly) { showLabOnly = true; } else { showLabOnly = false; }
				
				var returnTechniques = {};
				//for(var i = 0; i < techniques.length; i++) {
				for(var techniqueId in techniques) {
					//If technique is neither revision only or lab only return it
					//Or If technique is revision only and showRevisionOnly is true, return it
					//Or If technique is lab only and showLabOnly is true, return it
					//if((!techniques[techniqueId].revision_only && !techniques[techniqueId].lab_only) || ((techniques[techniqueId].revision_only === showRevisionOnly) && (techniques[techniqueId].lab_only === showLabOnly))) {
					if(techniques[techniqueId][section]) {
						//returnTechniques.push(techniques[techniqueId]);
						returnTechniques[techniqueId] = techniques[techniqueId];
					}
				}
				return returnTechniques;
		}
		
		function setCurrentTechniqueId(sectionId, techniqueId) { 
			if(sectionId === 'research') {
				currentResearchTechniqueId = techniqueId;
			}
			else if(sectionId === 'revision') {
				currentRevisionTechniqueId = techniqueId;
			}
		}
		
		function setLoaded() { 
			loaded = true;
		}
		
		function setRevisionComplete() {
			var sectionId = 'revision';
			if(progressFactory.checkProgress(sectionId)) {	//If progress is already set, don't need to set it again
				return 'Revision already complete';
			}
			else {
				for(var techniqueId in revisionTechniques) {
					if(!revisionTechniques[techniqueId].infoOnly && typeof(usefulTechniques[techniqueId]) === "undefined" || usefulTechniques[techniqueId] === null) {
						return 'Revision not yet completed'; //Exit the check, don't need to do anything more
					}
				}
				return lockFactory.setComplete(sectionId);	//Set the progress for this section to complete
			}
		};
		
		function setUsefulTechnique(techniqueId, usefulness) { 
			//API: save useful technique update to DB
			var deferred = $q.defer();
			var Useful = $resource('../../techniqueUsefulness/save', {});
			Useful.save({}, {attemptId: ATTEMPT_ID, techniqueId: techniqueId, usefulness: usefulness}, function(result) {
				var message = result.message;
				deferred.resolve(message);
				deferred.reject("Error: " + message);
			});
			return deferred.promise;
			
		}
	}
})();

/***Techniques DATA ***/
/*
var pcrVideo = 'https://www.youtube.com/embed/A3XmTyUmzOI?rel=0';
var pcrText = '<ul><li>Quantifies the amount of nucleic acid (DNA for qPCR and RNA for qRT-PCR) in a sample</li><li>Choice between qPCR and qRT-PCR requires information about the genome type (i.e. Baltimore class of the virus)</li><li>	Specificity dependent on the primers chosen and the sequence divergence between viruses</li><li>Requires samples containing viral genomes (i.e. free virions or virus infected cells)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_9__Real_Time_RT_PCR_fo" target="_blank">Brimouth Protocol 9</a></li></ul>';

var techniques = [
	{
		id: 'pfu',
		menu: 'Plaque Assay',
		name: 'The Plaque Assay',
		//video: {
		//	file: '../../videos/nerve.mp4',
		//	image: '../../videos/nerveImage.jpg',
		//},
		video: 'https://www.youtube.com/embed/er2dwOPwSRo?rel=0',
		content: '<ul><li>Quantifies the amount of virus in a sample if:<ul><li>Virus can replicate in cells chosen</li><li>Virus causes observable changes in the cells upon infection (i.e. cytopathic effect or CPE)</li></ul></li><li>Resulting plaques do vary in size and shape for each virus type but it is not diagnostic</li><li>Requires samples containing replication competent (i.e. not inactivated) virus<ul><li>Dealing with replication competent viruses requires elevated safety considerations (<a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_1__Safe_Handling_of_Bl" target="_blank">see Brimouth Protocols 1 & 2</a>)</li></ul></li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_3__In_vitro_culture_of" target="_blank">Brimouth Protocol 3</a></li></ul>',
		revision_only: false,
		lab_only: false,
		time: 120,
		money: 10,
		results: [
			{ id: 'p' }
		],
	},
	{
		id: 'ha',
		menu: 'HA',
		name: 'The Haemagglutination (HA) Assay',
		video: 'https://www.youtube.com/embed/CFCi5Q4rhOU?rel=0',
		content: '<ul><li>Quantifies amount of virus in a sample</li><li>Specific to any virus that can bind red blood cell surface proteins</li><li>Requires samples containing whole (live or inactivated) virus</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_4__Titration_of_influe" target="_blank">Brimouth Protocol 4</a></li></ul>',
		revision_only: false,
		lab_only: false,
		time: 4,
		money: 2,
		results: [
			{ id: 'ha' }
		],
	},
	{
		id: 'hai',
		menu: 'HAI',
		name: 'The Haemagglutination Inhibition (HAI) Assay',
		video: 'https://www.youtube.com/embed/nN8MBU8S4EI?rel=0',
		content: '<ul><li>Quantifies amount of antibody in a sample</li><li>Antibody serotype assayed by altering the serotype of the virus to initiate haemagglutination</li><li>Requires samples containing antibody (e.g. serum from blood)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_5__Titration_of_anti_H" target="_blank">Brimouth Protocol 5</a></li></ul>',
		revision_only: false,
		lab_only: false,
		time: 6,
		money: 3,
		results: [
			{ id: 'hai1', label: 'H1' },
			{ id: 'hai3', label: 'H3' },
			{ id: 'hai5', label: 'H5' },
		]
	},
	{
		id: 'pcrh',
		menu: 'qRT-PCR (H)',
		name: 'qRT-PCR (H1/H3/H5/H7)',
		video: pcrVideo,
		content: pcrText,
		revision_only: false,
		lab_only: true,
		time: 6,
		money: 8,
		results: [
			{ id: 'pcrh1', label: 'H1' },
			{ id: 'pcrh3', label: 'H3' },
			{ id: 'pcrh5', label: 'H5' },
			{ id: 'pcrh7', label: 'H7' },
		]
	},
	{
		id: 'pcrn',
		menu: 'qRT-PCR (N)',
		name: 'qRT-PCR (N1/N2)',
		video: pcrVideo,
		content: pcrText,
		revision_only: false,
		lab_only: true,
		time: 6,
		money: 4,
		results: [
			{ id: 'pcrn1', label: 'N1' },
			{ id: 'pcrn2', label: 'N2' },
		]
	},
	{
		id: 'pcr',
		menu: 'PCR',
		name: 'The Polymerase Chain Reaction (PCR)',
		video: pcrVideo,
		content: pcrText,
		revision_only: true,
		lab_only: false,
	},
	{
		id: 'elisa',
		menu: 'ELISA',
		name: 'The Enzyme-linked Immunosorbent Assay (ELISA)',
		video: 'https://www.youtube.com/embed/1iNwFNinhQo?rel=0',
		content: '<ul><li>Quantifies amount of virus or antibody in a sample (depending on setup)</li><li>Specificity determined by choice of antibody or antigen used as the capture molecule</li><li>Requires samples containing either antibody or protein (depending on setup)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_7__ELISA_assay_for_ant" target="_blank">Brimouth Protocol 7</a></li></ul>',
		revision_only: false,
		lab_only: false,
		time: 24,
		money: 4,
		results: [
			{ id: 'en1', label: 'N1' },
			{ id: 'en2', label: 'N2' },
		]
	},
];

var researchTechniques = [
{
	id: 'em',
	menu: 'EM',
	name: 'Electron Microscopy',
	video: 'https://www.youtube.com/embed/7GB4HyPE1AA?rel=0',
	//video: {
	//	file: '../../videos/nerve.mp4',
	//	image: '../../videos/nerveImage.jpg',
	//},
	content: '<p><strong>Paper:</strong> <a href="../../files/techniques/York_2013.pdf" target="_blank">Isolation and characterization of the positive-sense replicative intermediate of a negative-strand RNA virus, York et al, 2013</a></p><p>Using EM it is possible to visualise the virion itself, to observe the gross substructure and viral envelope glycoprotein spikes and the constituent components.</p><table><tr><td style="width: 45%">Pseudocolour of Influenza with orange envelpe glycoproteins Yellow membrane and purple RNPs</td><td style="width: 5%"></td><td style="width: 50%">Negative stain electron micrograph of purified RNPs extracted from virions</td></tr><tr><td colspan=3 class="align-center"><img src="../../img/techniques/em/flu_em.png" style="width: 100%" alt="Influenza Electron Micrograph" /><br />Ruigrok et al (2010) Curr Opin Struct Biol 20: 104</td></tr></table><p>Also using averaging of many similar particles it is possible to see even smaller units of the virus with enough resolution to propose models of how the proteins are arranged, e.g. the viral proteins and RNA within the RNP complex as shown in the image below:</p><p><img src="../../img/techniques/em/flu_structural_organisation.png" alt="Structural organization of the influenza virus cRNP replicative intermediate." /></p>',
},
//{
//	id: 'fm',
//	menu: 'FM',
//	name: 'FM',
//	content: '<p>Text about FM</p><p>Another line of text about FM</p>',
//},
{
	id: 'facs',
	menu: 'FACS',
	video: 'https://www.youtube.com/embed/7GB4HyPE1AA?rel=0',
	name: 'Fluorescence-activated Cell Sorting (FACS)',
	content: '<p><strong>Paper:</strong> <a href="../../files/techniques/Baxter_2014.pdf" target="_blank">Macrophage Infection via Selective Capture of HIV-1-Infected CD4+ T Cells, Baxter et al, 2014</a></p><p>Flow cytometry can be used to detect the presence of viral replication or gene expression as well as effects the virus has on cellular gene expression. Here we are observing an HIV vector encoding the fluorescent reporter gene, GFP. Upon infection a cell, here a CD14 (APC)-stained macrophage, becomes fluorescent and can be detected and quantified by flow cytometry.</p><p><img src="../../img/techniques/facs/facs.png" style="width: 100%" alt="Flow cytometry" /></p>',
},
//{
//	id: 'ms',
//	menu: 'MS',
//	name: 'Mass Spectrometry',
//	content: '<p>Text about MS</p><p>Another line of text about MS</p>',
//},
{
	id: 'wb',
	menu: 'WB',
	name: 'Western Blotting',
	video: [
		{
			title: 'Part I',
			url: 'https://www.youtube.com/embed/GJJGNOdhP8w?rel=0',
		},
		{
			title: 'Part II',
			url: 'https://www.youtube.com/embed/JcN0EkcHrKk?rel=0',
		},
		{
			title: 'Part III',
			url: 'https://www.youtube.com/embed/IoVzpL_heFo?rel=0',
		}
	],
	content: '<p>Western blotting can be used to identify and semi-quantitatively measure the amount of a specific protein, or protein modification such as phosphorylation. Here we are seeing a blot probing for a cellular host restriction factor, SAMHD1 and observing the effect type 1 interferon or LPS stimulation has on its phosphorylation within patient-derived macrophages.</p><p><img src="../../img/techniques/wb/wb.png" style="width: 100%; max-width: 500px;" alt="Western blotting" /></p>',
},
{
	id: 'seq',
	menu: 'Sequencing',
	name: 'Sequencing',
	video: null,
	content: '<p><strong>Paper:</strong> <a href="../../files/techniques/Sutton_2014.pdf" target="_blank">Airborne Transmission of Highly Pathogenic H7N1 Influenza Virus in Ferrets, Sutton et al, 2014</a></p>',
},
{
	id: 'vs1',
	menu: 'Epidemic Simulator',
	name: 'Epidemic Simulator',
	video: null,
	content: '<p>This simulator should appear below, but may not, depending on your browser settings. If it does not appear, or does not work properly, you can access it at <a href="http://vax.herokuapp.com/" target="_blank">vax.herokuapp.com/</a></p><iframe src="http://vax.herokuapp.com/" style="width: 100%; height: 870px;">iFrames not supported</iframe>',
},
{
	id: 'vs2',
	menu: 'Herd Immunity Simulator',
	name: 'Herd Immunity Simulator',
	video: null,
	content: '<p>This simulator should appear below, but may not, depending on your browser settings. If it does not appear, or does not work properly, you can access it at <a href="http://www.software3d.com/Home/Vax/Immunity.php" target="_blank">www.software3d.com/Home/Vax/Immunity.php</a></p><iframe src="http://www.software3d.com/Home/Vax/Immunity.php" style="width: 100%; height: 990px;">iFrames not supported</iframe>',
},
];*/
