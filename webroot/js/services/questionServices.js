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
	angular.module('flu.questions')
		.factory('questionFactory', questionFactory);
		
	questionFactory.$inject = ['lockFactory', 'progressFactory', '$resource', '$q'];

	function questionFactory(lockFactory, progressFactory, $resource, $q) {
		//Variables
		//var questions = readQuestions();
		var questions, responses, questionOrders;
		//var responses = readResponses();
		//var responses = [];
		//setAnswered();	//Set whether each question has been answered
		//var notAnswered = setNotAnswered();
		var currentQuestionId = 1;
		var romans = ["(i) ", "(ii) ", "(iii) ", "(iv) ", "(v) ", "(vi) ", "(vii) ", "(viii) ", "(ix) ", "(x) ", "(xi) ", "(xii) ", "(xiii) ", "(xiv) ", "(xv) "];
		//var loaded = false;
		var saving = [];
		
		//Exposed Methods
		var factory = {
			checkAllAnswered: checkAllAnswered,
			checkAnswers: checkAnswers,
			clearAnswers: clearAnswers,
			getResponses: getResponses,
			getCurrentQuestionId: getCurrentQuestionId,
			getLoaded: getLoaded,
			//getNotAnswered: getNotAnswered,
			getNextOrPrev: getNextOrPrev,
			getQuestions: getQuestions,
			getRoman: getRoman,
			getRomans: getRomans,
			getSaving: getSaving,
			loadQuestions: loadQuestions,
			loadResponses: loadResponses,
			setAnswered: setAnswered,
			setCurrentQuestionId: setCurrentQuestionId,
			setQuestionsComplete: setQuestionsComplete,
			setLoaded: setLoaded,
			setSaving: setSaving,
		}
		return factory;

		//Methods
		//note that readQuestions is out of order at the end, because it is so long
		
		function checkAllAnswered(questionId) {
			responses.answered[questionId] = setAnsweredByQuestion(questionId)
		}

		function checkAnswers(questionId) {
			responses.answered[questionId] = setAnsweredByQuestion(questionId);
			if(!responses.answered[questionId]) {	//If there are any unanswered questions...
				//var notAnsweredMessage = "Please attempt all question parts before checking your answers."; 
			}
			else {
				//API: Save responses (answers, score) to DB
				var score = setScoreByQuestion(questionId);
				var deferred = $q.defer();
				var QuestionsCall = $resource(URL_MODIFIER + 'question_answers/save', {});
				QuestionsCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, questionId: questionId, answers: responses.answers[questionId], score: score},
					function(result) {
						if(typeof(result.status) !== "undefined" && result.status === 'success') {
							responses.scores[questionId] = score;
							deferred.resolve('Question response saved');
						}
						else {
							deferred.reject('Question response save failed (' + result.status + ")");
						}
					},
					function(result) {
						deferred.reject('Question response save error (' + result.status + ')');
					}
				);
				return deferred.promise;

			}
		}

		function clearAnswers(questionId) {
			if(!(responses.scores[questionId] > -1)) {	//If question has not been answered...
				var stems = questions[questionId].question_stems;
				for(var s = 0; s < stems.length; s++) {
					responses.answers[questionId][stems[s].id] = null;
				}
			}
			responses.answered[questionId] = setAnsweredByQuestion(questionId);
		}

		function getCurrentQuestionId() { 
			return currentQuestionId; 
		}
		
		function getLoaded() { 
			return loaded;
		}
		
		function getNextOrPrev(questionId, direction) {
			//var order = questions[questionId].order;
			var nextQuestionId = questionId;
			if(direction === 'next') {
				//if(order >= (questionOrders.length-1)) {
				if(!questions[questionId].last) {
					nextQuestionId = questionId+1;
				}
			}
			if(direction === 'prev') {
				//if(order <= 1) {
				if(!questions[questionId].first) {
					nextQuestionId = questionId-1;
				}
			}
			return nextQuestionId;
		}
		
		function getNotAnswered() { 
			return notAnswered; 
		}
		
		function getQuestions() { 
			return questions; 
		}
		
		function getQuestionOrders() { 
			return questionOrders; 
		}
		
		function getResponses() { 
			return responses; 
		}
		
		function getRoman(number) {
			return romans[number];
		}
		
		function getRomans() {
			return romans;
		}

		function getSaving() {
			return saving;
		}

		function loadQuestions() {
			var deferred = $q.defer();
			var QuestionsCall = $resource(URL_MODIFIER + 'questions/load.json', {});
			QuestionsCall.get({},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						questions = result.questions;
						var first = true;
						for(var questionId in questions) {
							if(first) {	//Set first to true for the first question
								questions[questionId].first = true;
								first = false;
							}
						}
						questions[questionId].last = true;	//questionId will now be that of the last question, so set last to true
						deferred.resolve('Questions loaded');
					}
					else {
						deferred.reject('Questions load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Questions load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		function loadResponses() {
			var deferred = $q.defer();
			var ResponsesCall = $resource(URL_MODIFIER + 'question_answers/load/:attemptId/:token.json', {attemptId: null, token: null});
			ResponsesCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						responses = result.responses;
						deferred.resolve('Responses loaded');
					}
					else {
						deferred.reject('Responses load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Responses load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		/*function readAnswers() {
			//API: Get these from the DB
			var answers = [];
			for(var q = 0; q < questions.length; q++) {
				answers[q] = [];
				for(var s = 0; s < questions[q].stems.length; s++) {
					answers[q][s] = null;
				}
			}
			return answers;
		}
		
		function readResponses() {
			var responses = {
				//answered: readAnswered(),
				answers: readAnswers(),
				//checked: readChecked(),
				scores: readScores(),
			}
			
			return responses;
		}
		
		function readScores() {
			//API: Get these from the DB
			var scores = [];
			for(var q = 0; q < questions.length; q++) {
				scores[q] = null;
			}
			return scores;
		}*/
		
		function setAnswered() {
			responses.answered = [];
			for(var questionId in questions) {
				responses.answered[questionId] = setAnsweredByQuestion(questionId);
			}
			//return answered;
		}
		
		function setAnsweredByQuestion(questionId) {
			var answered = true;
			
			//If there are no answers at all for this question, then it is unanswered
			if(!responses.answers[questions[questionId].id]) {
				answered = false;
			} 
			else {
				//If there is no answer for any of the stems, then the question is unanswered
				for(var s = 0; s < questions[questionId].question_stems.length; s++) {
					var stemId = questions[questionId].question_stems[s].id;
					if(!responses.answers[questionId][stemId]) {
						answered = false;
						break;
					}
				}
			}
			return answered;
		}
		
		function setCurrentQuestionId(questionId) { 
			currentQuestionId = questionId; 
		}
		
		function setLoaded() { 
			loaded = true;
		}
		
		function setQuestionsComplete() {
			var sectionId = 'questions';
			if(progressFactory.checkProgress(sectionId)) {	//If progress is already set, don't need to set it again
				return 'Questions already complete';
			}
			else {
				for(var questionId in questions) {
					if(!(responses.scores[questionId] > -1)) {
						return 'Questions not yet complete';
					}
				}
				//If we've got here, none of the questions are unscored (and therefore unchecked), so set the section to complete
				return lockFactory.setComplete(sectionId);
			}
		}

		function setSaving(questionId, value) { 
			saving[questionId] = value;
		}
		
		function setScoreByQuestion(questionId) {
			var score = 0;
			for(var s = 0; s < questions[questionId].question_stems.length; s++) {
				var stem = questions[questionId].question_stems[s];
				if(responses.answers[questionId][stem.id] === stem.question_option.id) {
					score++;
				}
			}
			return score;
		}
		
		/*function readQuestions() {
			//API: Get these from the DB?
			var questions = [
				{
					background: 'Are the following statements concerning neuraminidase true or false?',
					menu: '1',
					name: 'Neuraminidase',
					options: ['True', 'False'],
					stems: [
						{
							text: 'Anti-N ELISA measures the concentration of virion-associated neuraminidase in patients\' serum',
							feedback: 'ELISAs can measure either antigen or antibody, but an Anti-N ELISA measures antibodies directed to the N antigen (neuraminidase). Circulating antibodies are found in the blood and serum derived from the blood, but influenza viruses are not. (This would vary for different viruses depending on their tropism.)',
							answer: 1,	//False
						},
						{
							text: 'Anti-N ELISA tests for previous exposure to a virus or vaccine',
							feedback: 'The assay tests for antibodies generated following either exposure to the virus or to a vaccine against the virus. This is an indirect way of detecting exposure, and exposure to a particular serotype of virus can be most clearly inferred if samples are available at different time points following a challenge to the immune system (e.g.  by comparing sera collected during the acute and then the convalescent phases of an infection and looking for a rising titre of antibody).',
							answer: 0,	//True
						},
						{
							text: 'Antigenic shift can result in the circulation of viruses with antigenically new forms of neuraminidase',
							feedback: 'Antigenic <strong>drift</strong> is the sequential accumulation of mutations which subtly alter the immunogenicity of a particular serotype of a virus. Circulating viruses evolve away from immune responses generated to previous infections but they stay within the general "serotype" designation of H1, H3 etc. Hosts therefore retain partial immunity from previous exposures. In contrast, antigenic <strong>shift</strong> occurs when the virus replaces the genes of one or more highly immunogenic proteins (typically HA and NA) with substantially different versions, typically from an influenza virus that was circulating in a different species. This changes the serotype of the virus, removing the protection offered by previous infection or vaccination.',
							answer: 0,	//True
						},
						{
							text: 'Neuraminidase assists new virions in escaping from the host cell',
							feedback: 'The neuraminidase enzyme cleaves the terminal sialic acid residues off the glycoproteins present on surface of cells as well as within the mucus of the respiratory tract. As the sialic acids are the target of the viral HA protein this helps the virus diffuse through the mucus towards its target cell at the beginning of infection, and later in an infection allows newly-formed viruses to be efficiently released from the infected cell.',
							answer: 0,	//True
						},
						{
							text: 'Neuraminidase is a nucleic acid polymerase',
							feedback: 'Neuraminidases are glycoside hydrolases which catalyse cleavage of glycosidic bonds in sugar chains, specifically the glycosidic linkages of neuraminic acids. Influenza virus neuraminidase cleaves the terminal sialic acid residue from the sugar chains on glycoproteins and glycolipids.',
							answer: 1,	//False
						},
						{
							text: 'Neuraminidase is immunologically identical in all strains of influenza',
							feedback: 'Neuraminidase is located on the surface of both infected cells and virions, and as such is visible to the immune response and is highly immunogenic. As with HA, NA is therefore highly variable in order to escape pre-existing immunity. A number of different serotypes of NA have been characterised, though all influenza viruses circulating in humans carry NAs from serotypes N1 and N2.',
							answer: 1,	//False
						},
					],
				},
				{
					background: 'Are the following statements concerning qRT-PCR true or false?',
					menu: '2',
					name: 'qRT-PCR',
					options: ['True', 'False'],
					stems: [
						{
							text: 'Nose and throat swabs from convalescent phase patients are a good source of samples for qRT-PCR.',
							feedback: '<p>RT-PCR assays for viral nucleic acid, specifically viral RNA (a simple qPCR would be used for DNA containing viruses), and as such requires the presence of actual viral particles. As flu is an infection of the upper respiratory tract the virions can be isolated from the nose and throat.</p><p>Once the patient has recovered the immune system has cleared the virus and therefore there are no virions to detect by qRT-PCR at any sampling site.</p>',
							answer: 1,	//False
						},
						{
							text: 'qRT-PCR can be used to measure the concentration of viral RNA in patient samples.',
							feedback: '<p>Using any sample containing viral particles the qRT-PCR (or qPCR for DNA viruses) can accurately provide a quantification of the amount of viral nucleic acid. The process uses the knowledge that each round of PCR (cycle) results in a doubling of the total amount of DNA, and so if we know the cycle number at which a particular sample appears positive (Ct value = cycle number (C) at which the fluorescence reaches a target threshold (t) value), you can compare that Ct value to a standard curve of known amounts of viral RNA and calculate the concentration of viral RNA in your unknown sample.</p><p>qRT-PCR cannot be used to measure protein or antibody response.</p>',
							answer: 0,	//True
						},
						{
							text: 'Serum samples from acute phase patients are usually positive for influenza by qRT-PCR.',
							feedback: 'During the course of a normal influenza infection only cells of the upper respiratory tract are infected and these shed viruses from their apical surface, releasing virus back into the respiratory mucus. This means that virions are not generally found within the blood, except (in rare and mostly fatal cases (e.g. a fatal H5N1 avian influenza case).',
							answer: 1,	//False
						},
						{
							text: 'Serum samples from convalescent patients are a good source of samples for qRT-PCR.',
							feedback: 'During the course of a normal influenza infection only cells of the upper respiratory tract are infected and these shed viruses from their apical surface, releasing virus back into the respiratory mucus. This means that virions are not generally found within the blood, except (in rare and mostly fatal cases (e.g. a fatal H5N1 avian influenza case). Also once the patient has recovered the immune system will have cleared all active viral replication from the patient and no sample even from the nose or throat will be positive by qRT-PCR.',
							answer: 1,	//False
						},
						{
							text: 'qRT-PCR can be used to distinguish different influenza serotypes.',
							feedback: 'Different serotypes are identified by virtue of different antibodies ability to bind the HA or NA. This binding is dependent on the structure of the viral proteins, which in turn is dependent on their amino acid sequence, and therefore on their viral RNA sequence. Hence, serotypes can be distinguished by not only antibody binding, but by their primary nucleotide sequence. The qRT-PCR reaction requires complementary primers to reverse transcribe the target RNA and amplify a stretch of DNA from it, and the product is detected using probes that bind to complementary sequences in this amplicon. As different primer and probe sets will only recognise very specific sequences, they can be used to identify the presence of different serotypes.',
							answer: 0,	//True
						},
					],
				},
				{
					background: 'Are the following statements concerning haemagglutinin true or false?',
					menu: '3',
					name: 'Haemagglutinin',
					options: ['True', 'False'],
					stems: [
						{
							text: 'Haemagglutinin adopts a fusion-promoting conformation at pH values above 8.2',
							feedback: 'The virus enters target cells through receptor mediated endocytosis, and HA only promotes fusion as an endosome alters its pH. As endosomes mature protons are pumped in and the pH lowers. Once a pH of about 5 is reached (actual pH varies by strain from 4.6 to 6) the HA protein alters its conformation to insert a fusion peptide into the endosomal membrane. It then folds back on itself, bringing the viral and endosomal membranes together and encouraging membrane fusion and the escape of the viral contents into the cytoplasm.',
							answer: 1,	//False
						},
						{
							text: 'Haemagglutinin binds to sialic acid residues',
							feedback: 'The receptor bound by different viruses plays a major role in determining the host range, tissue tropism, pathogenicity and transmissibility of different viruses. The HA protein binds to sialic acid containing cell surface receptors. Specifically human adapted viral strains preferentially utilise &alpha;(2,6)-linked sialic acid residues found in the upper respiratory tract (and are therefore easily shed into the environment and transmitted from person to person) whereas avian influenza (e.g. H5N1) uses &alpha;(2,3)-linked sialic acid residues, which in humans are found only in the lower respiratory tract (making them less transmissible).',
							answer: 0,	//True
						},
						{
							text: 'Haemagglutinin changes conformation at low pH',
							feedback: 'Viruses that enter cells through receptor-mediated endocytosis often use the natural successive lowing of the pH within the maturing endosome as a mechanistic trigger to time the fusion event. The HA protein within the viral membrane is in a metastable state (not in its lowest free energy state, but like a locked loaded spring) and upon exposure to external stimuli (change in pH for HA), it is able to transition to the lowest energy state via a conformational change that inserts the fusion peptide into the endosomal membrane, initiating the fusion reaction and resulting in the viral core gaining access to the cytoplasm.',
							answer: 0,	//True
						},
						{
							text: 'Haemagglutinin cleaves n-acetyl neuraminic acid from oligosaccharides',
							feedback: 'N-acetyl neuraminic acid or sialic acid is cleaved from oligosaccarides on glycoproteins by the viral neuraminidase.',
							answer: 1,	//False
						},
						{
							text: 'Haemagglutinin enables the virion to attach to the cell surface',
							feedback: 'All viruses require a viral attachment protein that enables the virus to bind its target cell using a specific receptor e.g. sialic acid. For influenza viruses this is carried out by HA, which in circulating human strains of the virus bind to &alpha;(2,6)-linked sialic acid residues in oligosaccaride chains found on cell surface glycoproteins of the upper respiratory tract.',
							answer: 0,	//True
						},
						{
							text: 'Haemagglutinin is an antigen that is variable between subtypes',
							feedback: 'HA is an integral membrane protein, most of which is found on the outside of the virion and is therefore highly visible to the immune response. Anti-HA antibodies (both secreted IgA and plasma IgG) form the primary adaptive immune response to influenza infection, and as such there is selective pressure to evolve variants in this protein. Sufficiently different protein variants can be grouped according to their detection by different antibodies and are classed as different serotypes (e.g. H1, H2, H3, H5, etc.).',
							answer: 0,	//True
						},
					],
				},
				{
					background: 'Are the following statements concerning haemagglutination true or false?',
					menu: '4',
					name: 'Haemagglutination',
					options: ['True', 'False'],
					stems: [
						{
							text: 'Haemagglutination can be used to titrate virus concentration',
							feedback: 'In sufficient concentrations, influenza virions will  cross-link red blood cells to each other via interactions between sialic acids on the red blood cell glycoproteins and the multiple HA proteins in each virion. If the virus is sequentially diluted eventually there will not be enough present to agglutinate the red blood cells. By observing how many dilutions are required to lose this haemagglutination response it is possible to provide a standardised measure of viral titre (i.e. the concentration of virus) in an unknown sample. As haemagglutination is very dependent on experimental conditions, the units of this titration (haemagglutinating units (HAU) /ml) are somewhat arbitrary.',
							answer: 0,	//True
						},
						{
							text: 'Haemagglutination is an important mechanism of influenza pathogenesis',
							feedback: 'As, in a normal infection, influenza virions are restricted to the upper respiratory tract, they do not enter the blood and therefore do not encounter red blood cells. Therefore, haemagglutination is not a mechanism of viral pathogenesis during influenza infection. It is however a useful, if relatively non-specific, technique to detect any virus that can bind sialic acid residues.',
							answer: 1,	//False
						},
						{
							text: 'Haemagglutination is inhibited by antibodies to haemagglutinin',
							feedback: '<p>As the HA proteins on the virus are integral to the cross-linking of red blood cells in the haemagglutination assay, if they are "neutralised" by antibodies directed to HA then no haemagglutination will occur and the assay will be negative. This is useful, as it can be used to titre anti-HA antibodies in patient serum. The higher the titre of antibodies, the more the serum will have to be diluted until it no longer inhibits haemagglutination. This forms the basis of the haemagglutination inhibition (HI) assay, which measures anti-HA antibody titres and can be used to assess seroconversion.</p><p>The viral HA protein is the only protein required to initiate haemagglutination, so adding antibodies to a different viral protein, even NA, has no effect on the response.</p>',
							answer: 0,	//True
						},
						/*{
							text: 'Haemagglutination is inhibited by antibodies to neuraminidase',
							feedback: 'The viral HA protein is the major viral surface protein and the only protein on the surface of the virion required to initiate haemagglutination. Adding antibodies to a different viral protein, even NA, has no effect on the response.',
							answer: 1,	//False
						},*/
						/*{
							text: 'Haemagglutination is recognised by the formation of a \'shield\' in the base of a microtitre plate',
							feedback: 'A \'button\' on the bottom of the microtitre plate results when  red blood cells sediment under gravity into a dense pellet. Haemagglutination is the cross-linking of multiple red blood cells via the HA proteins on virions; once cross-linked the red blood cells can\'t settle to the bottom of the plate as freely and get stuck on the sides, thereby appearing as a diffuse \'shield\'.',
							answer: 0,	//True
						},
						{
							text: 'Haemagglutination results from the cross-linkage of erythrocytes by virus surface glycoproteins',
							feedback: 'Erythrocytes contain multiple glycolipids and glycoproteins that have a terminal sialic acid residue. The HA of influenza binds the sialic acid, and by virtue of having multiple HA molecules per particle the virus effectively cross-links multiple erythrocytes into a large conglomerate.',
							answer: 0,	//True
						},
						{
							text: 'Haemagglutination results from the insertion of pores into the red cell membrane',
							feedback: 'Although the viral HA is able to create a pore in an endosomal membrane by initiating fusion between the viral membrane and that of the endosome, this is a pH-dependent step. Haemagglutination is simply a binding reaction between the HA and sialic acid residues, which forms a mat of cross-linked virus particles and erythrocytes.',
							answer: 1,	//False
						},
					],
				},
				{
					background: 'Are the following statements concerning the haemagglutination-inhibition (HAI) test true or false?',
					menu: '5',
					name: 'The HAI test',
					options: ['True', 'False'],
					stems: [
						{
							text: 'The HAI test involves concentrating virions by centrifugation',
							feedback: 'Gravity is sufficient to pellet the red blood cells in this assay. To pellet virions, extremely high-speed ultracentrifugation would be require.',
							answer: 1,	//False
						},
						{
							text: 'The HAI test involves testing serial dilutions of patient sera',
							feedback: 'Serial dilutions are important to find the "end-point" dilution at which a particular reaction no longer takes place (i.e. how far do I need to dilute a sample before I lose the inhibition of haemagglutination). The assay detects anti-HA antibodies such as the IgG present in a patient\'s serum.',
							answer: 0,	//True
						},
						{
							text: 'The HAI test is an assay for antibodies that bind to a specific viral toxin',
							feedback: 'The assay is a test for antibodies, but ones that bind to HA of influenza, which is not a toxin but a viral glycoprotein.',
							answer: 1,	//False
						},
						{
							text: 'The HAI test is an assay for antibodies that bind to specific antigenic variants of haemagglutinin',
							feedback: 'As serotypes are defined by the ability of a particular strain of virus to be neutralised by a particular type of antibody. By using different viruses to cause haemagglutination in different wells, it is possible to probe a single serum sample for antibodies to different serotypes of virus. Each viral serotype will only be neutralised by its cognate antibody.',
							answer: 0,	//True
						},
						{
							text: 'The HAI test takes as its end point the first dilution failing to inhibit haemagglutination',
							feedback: 'The end point is recorded as the last dilution showing inhibition.',
							answer: 1,	//False
						},
						{
							text: 'The HAI test takes as its end point the last dilution showing inhibition of haemagglutination',
							feedback: 'The last well showing a positive inhibition of the haemagglutination reaction can be said to contain enough antibody to inhibit a known standard amount of virus from agglutinating a standard amount of red blood cells. Using the amount of sample added to each well and the dilution factor providing the final positive response it is possible to calculate the amount of antibody in the starting sample as an arbitrary but standardised measurement.',
							answer: 0,	//True
						},
						{
							text: 'The HAI test produces a \'button\' as a positive result (presence of antibody)',
							feedback: 'A button is produced from settling red blood cells. Enough virus is added to each well to cause haemagglutination (producing a shield), so a button can only result if antibodies are present to neutralise the virus and prevent haemagglutination - thus a button is a positive result.',
							answer: 0,	//True
						},
						{
							text: 'The HAI test produces a \'shield\' as a positive result (presence of antibody).',
							feedback: 'A shield is the product of red blood cells being hindered from forming a pellet by the cross linking of the cells via viruses. This occurs when insufficient antibodies are present to neutralise the viruses, and therefore a shield is a negative result.',
							answer: 1,	//False
						},
					],
				},
				{
					background: '<p>This images represents a completed HAI assay.</p><p><img src="../../img/question6_hai_results.png" /></p><p>Are the following statements concerning this assay true or false?</p>',
					menu: '6',
					name: 'Interpreting an HAI assay',
					options: ['True', 'False'],
					stems: [
						{
							text: 'Patient \'JB\' was probably suffering from infection by an H1 strain of influenza during the acute phase',
							feedback: '<p>With suitable samples the HAI assay indicates the history of influenza infections for a patient, and if acute phase samples are present the timings of infections can also be resolved. Patient JB does not have antibodies to H1 during acute infection but has developed them by the time the convalescent sample was taken. The correlation of recovery with seroconversion suggests that JB was suffering from an H1 serotype influenza virus.</p><p>Although there is very little response to H1 antigen in JB acute serum (antibody response has not had time yet to produce antibodies during the acute phase of infection), in the convalescent serum there are high titres of antibody capable of preventing haemagglutination even after diluting 1/512. </p><p>There is no difference between the end-point titration of JB acute serum and JB convalescent serum when assayed against H3 antigen, so no additional immune response had been mounted against H3 antigen in the time frame of sampling. The low level response detected (positive at 1/4 dilution) could result from cross-reactivity by antibodies previously raised against other influenza serotypes.</p>',
							answer: 0,	//True
						},
						{
							text: 'The end point of HAI in sample C corresponds to a test serum dilution of 1/1024',
							feedback: 'The last dilution to produce a positive result (button) is the end point, which for JB sample C is 1/512.',
							answer: 1,	//False
						},
						{
							text: 'The evidence shows that patient \'JB\' is likely to be immune to H7 and H5 strains of influenza',
							feedback: 'No data is provided by this assay on the exposure of JB to H5 or H7 strains. The assay is only able to indicate antibodies specific to the antigens tested (for this assay only H1 and H3 were used).',
							answer: 1,	//False
						},
						{
							text: 'The presence of a falling HAI titre between acute and convalescent serum samples taken from the same subject indicates a recent infection by that strain of virus',
							feedback: 'Antibodies are not produced until the later stages of influenza infection when the virus is being cleared. So during the acute phase no antibodies (except pre-existing ones) will be detected. However, towards the later stages of infection antibodies are produced, help clear the virus and remain to help with future challenges by similar viruses. Therefore, a rising titre (more than three-fold increase in titre from acute sample to convalescent sample) of haemagglutination-inhibiting (HAI) antibodies indicates recent infection with the corresponding serotype of virus. If only convalescent sera are available, no conclusions can be drawn about the timing of the infection, though it can still be useful to know what viruses a person has been exposed to.',
							answer: 1,	//False
						},
					],
				},
				{
					background: 'Are the following statements concerning the Plaque Assay true or false?',
					menu: '7',
					name: 'The Plaque Assay',
					options: ['True', 'False'],
					stems: [
						{
							text: 'The plaques seen are individual cells transformed by the virus to grow into colonies',
							feedback: 'The end point of the plaque assay is a monolayer of cells that has been stained for protein with Coomassie Blue, therefore the observed plaques are indicative of cell loss.',
							answer: 1,	//False
						},
						{
							text: 'The plaques are holes in the cell monolayer created by a viral infection spreading to neighbouring cells and leading to cell death',
							feedback: 'The assay requires that infected cells die and fall off the plate so that the effect of the virus can be observed, without this lytic effect the plaque assay will not work (unless another form of observation is possible i.e. immunostaining the plate).',
							answer: 0,	//True
						},
						{
							text: 'The plaque assay is qualitative not quantitative',
							feedback: 'As well as being a good assay to show the presence of a virus, with appropriate dilutions, counting of plaques and back calculations the plaque assay can be used to quantify (titrate) the number of plaque forming units per ml (PFU/ml) in a solution.',
							answer: 1,	//False
						},
						{
							text: 'The plaque assay can be used for many different viruses',
							feedback: 'As long as the virus is viable, replication competent, can infect the cells used in the monolayer and causes visible cytopathic effects (CPE, e.g. death) then the virus can be quantified.',
							answer: 0,	//True
						},
						{
							text: 'The plaque assay allows us to visualise by eye the effects of a single virus',
							feedback: 'Individual virions are too small to see by eye, or even by light microscopy, but over time as the virus replicates and kills more cells we begin to be able to observe the effect of the virus by eye. The assay does assume that a single virus particle is able to initiate an infection (not universally true, but typical for animal viruses) and that any complexes of virus particles were dispersed by dilution.',
							answer: 0,	//True
						},
					],
				},
				{
					background: 'Are the following statements concerning Assay Controls true or false?',
					menu: '8',
					name: 'Assay Controls',
					options: ['True', 'False'],
					stems: [
						{
							text: 'A positive control is useful to show that your sample is positive',
							feedback: 'A positive control is used to identify false negatives. When testing a sample, a negative result could mean that the sample is negative, or that there is an error in the assay. A positive control is a known sample that contains the "substance of interest" and therefore always gives a positive result. It demonstrates that your assay has worked adequately and that all your reagents are still working. If the positive control does not result in a positive signal it indicates that your experiment has failed for some reason, and negative results when testing the samples would therefore be meaningless.',
							answer: 1,	//False
						},
						{
							text: 'The same positive control can be used for all assays',
							feedback: 'Each assay is testing for a very different, specific "feature", which the controls must match. For example, you need samples containing nucleic acids for a PCR based positive control, and viable virus if the assay relies on virus replication.',
							answer: 1,	//False
						},
						{
							text: 'A negative control is unimportant',
							feedback: 'A negative control indicates the background for your particular assay, and shows that there is no general contamination in any of the assay components. Contamination would produce false positives and a high background reduces the dynamic range of your assay.',
							answer: 1,	//False
						},
						{
							text: 'A good control for the HAI assay would be serum from a convalescent influenza patient',
							feedback: 'Though for it to work the serotype of the virus infecting the patient would need to match the serotype of the virus used in the HAI assay.',
							answer: 0,	//True
						},
						{
							text: 'A good control for the HA assay would be purified HA antigen',
							feedback: 'The HA assay relies on the virus binding multiple red blood cells, if only HA antigen was used it would bind to the red blood cells but would not be able to cross-link multiple cells together as the HAs would not be linked by being on a virus. Intact virions would be needed.',
							answer: 1,	//False
						},
					],
				},
			];
			return questions;
		}*/
	}
	
		/*function readChecked() {
			//API: Get these from the DB
			var checked = [];
			for(var q = 0; q < questions.length; q++) {
				checked[q] = false;
			}
			return checked;
		}*/
		
		/*function setNotAnswered() {
			var notAnswered = [];
			for(var q = 0; q < questions.length; q++) {
				notAnswered[q] = setNotAnsweredByQuestion(q);
			}
			return notAnswered;
		}*/
		
		/*function setNotAnsweredByQuestion(questionId) {
			var notAnswered = [];
			for(var s = 0; s < questions[questionId].stems.length; s++) {
				if(answers[questionId][s] === null) {
					notAnswered.push(s);
				}
			}
			return notAnswered;
		}*/
		
		/*
		//No longer needed - answered generated on load
		//Answered refers to whether all of the question parts have an answer, not whether the question has been checked
		//Answers are only saved when question is checked
		function readAnswered() {
			//API: Get these from the DB
			var answered = [];
			for(var q = 0; q < questions.length; q++) {
				answered[q] = false;
			}
			return answered;
		}*/
		
	//Use global questions var defined in jsquestions on learntech
	//This (using the global) is BAD, but would need to modify jsquestions to fix it
	/*questions = [
		{
			type: 'MCQ',
			background: 'MCQ background. Pick the best answer',
			name: 'MCQ name',
			//options: ['opt1', 'opt2', 'opt3', 'opt4', 'opt5'],
			answers: "opt2",
			distractors: ['opt1', 'opt3', 'opt4', 'opt5'],
			attempts: 3,
			letters: false,
			forceAnswer: true,
			sortOptions: true,
			autoFeedback: true,
			feedback: [
				'MCQ first feedback',
				'MCQ second feedback',
				'MCQ third/final feedback',
			],
		},
		{
			type: 'MRQ',
			background: 'MRQ background. Pick all that apply',
			name: 'MRQ name',
			//options: ['opt1', 'opt2', 'opt3', 'opt4', 'opt5', 'opt6', 'opt7'],
			answers: ['opt2', 'opt4', 'opt6'],
			distractors: ['opt1', 'opt3', 'opt5', 'opt7'],
			attempts: 3,
			letters: false,
			forceAnswer: true,
			sortOptions: true,
			autoFeedback: true,
			feedback: [
				'MRQ first feedback',
				'MRQ second feedback',
				'MRQ third/final feedback',
			],
		},
		{
			type: 'EMQ',
			background: 'EMQ background. Pick the best answer for each section',
			name: 'EMQ name',
			//options: ['opt1', 'opt2', 'opt3', 'opt4', 'opt5', 'opt6', 'opt7'],
			stems: ['stem 1', 'stem 2', 'stem 3', 'stem 4'],
			answers: ['opt2', 'opt3', 'opt5', 'opt7'],
			distractors: ['opt1', 'opt4', 'opt6', 'opt8', 'opt9', 'opt10'],
			attempts: 3,
			letters: true,
			forceAnswer: true,
			sortOptions: true,
			autoFeedback: true,
			feedback: [
				'EMQ first feedback',
				'EMQ second feedback',
				'EMQ third/final feedback',
			],
		},
		{
			type: 'Matrix',
			background: 'Matrix background. Pick the best answer for each section',
			name: 'Matrix name',
			options: ['True', 'False'],
			stems: ['stem 1', 'stem 2', 'stem 3', 'stem 4'],
			answers: ['True', 'False', 'False', 'True'],
			//distractors: ['opt1', 'opt4', 'opt6', 'opt8', 'opt9', 'opt10'],
			attempts: 3,
			letters: true,
			forceAnswer: true,
			sortOptions: true,
			autoFeedback: true,
			feedback: [
				'Matrix first feedback',
				'Matrix second feedback',
				'Matrix third/final feedback',
			],
		},
		{
			type: 'Model',
			background: 'Model Answer background. Type your answer in the box',
			name: 'Model Answer name',
			//options: ['opt1', 'opt2', 'opt3', 'opt4', 'opt5', 'opt6', 'opt7'],
			answers: ['answer to model answer'],
			attempts: 3,
			forceAnswer: true,
		},
	];*/

})();