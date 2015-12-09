(function() {
	angular.module('flu.report')
		.factory('reportFactory', reportFactory);
		
	reportFactory.$inject = ['techniqueFactory', 'resultFactory'];
	
	function reportFactory(techniqueFactory, resultFactory) {
		//Variables
		var boxes = readBoxes();
		var editorOptions = readEditorOptions();
		var report = readReport();
		var lastSaved = readLastSaved();
		var submitted = readSubmitted();
		var noteTechniques = readNoteTechniques(); 
		
		//Exposed Methods
		var factory = {
			getAllNotesEmpty: getAllNotesEmpty,
			getBoxes: getBoxes,
			getDate: getDate,
			getEditorOptions: getEditorOptions,
			getFirstNote: getFirstNote,
			getLastSaved: getLastSaved,
			getNoteTechniques: getNoteTechniques,
			getReport: getReport,
			getSubmitted: getSubmitted,
			save: save,
			submit: submit,
		}
		return factory;
		
		//Methods

		function getAllNotesEmpty() {
			var allNotesEmpty = true;
			var notes = resultFactory.getNotes();
			for(var i = 0; i < noteTechniques.length; i++) {
				if(notes[noteTechniques[i].id] != '') {
					//alert(noteTechniques[i]);
					allNotesEmpty = false;
					break;
				}
			}
			return allNotesEmpty;
		}
		
		function getBoxes() {
			return boxes;
		}
		
		function getDate() {
			var now = Date.now();
			return now;
		}
		
		function getEditorOptions(readOnly) {
			//Set the readOnly value
			if(!readOnly) { readOnly = false; }
			editorOptions.readOnly = readOnly;
			return editorOptions;
		}
		
		function getFirstNote() {
			var firstNote = false;
			var notes = resultFactory.getNotes();
			for(var i = 0; i < noteTechniques.length; i++) {
				if(notes[noteTechniques[i].id] != '') {
					//alert(noteTechniques[i]);
					firstNote = i;
					break;
				}
			}
			return firstNote;
		}
		
		function getLastSaved() {
			return lastSaved;
		}
		
		function getNoteTechniques() {
			return noteTechniques;
		}
		
		function getReport() {
			return report;
		}
		
		function getSubmitted() {
			return submitted;
		}
		
		function readBoxes() {
			//API: Get these from DB
			var boxes = [
				{
					id: 'patients',
					label: 'Clinical presentations and summary patient information',
					instructions: 'Who was tested and why?',
				},
				{
					id: 'investigations',
					label: 'Investigations',
					instructions: 'What tests did you perform and why?',
				},
				{
					id: 'results',
					label: 'Key results',
					instructions: 'What were the results (HI assay titers, virus serotype confirmation etc)?',
				},
				{
					id: 'conclusions',
					label: 'Conclusions and Recommendations',
					instructions: 'Given the results you have just outlined, what do you think is a suitable response? Do we have vaccines that will combat this virus? (WHO recommendations can be found here <a href="http://www.who.int/influenza/vaccines/virus/recommendations/en/" target="_blank">http://www.who.int/influenza/vaccines/virus/recommendations/en/</a>, but our stocks contain H1N1, H3N2 and Influenza B.)',
				},
				{
					id: 'other',
					label: 'Any other business',
					instructions: 'Any other useful comments to help put the results into context for any reader of the report or other future investigations that you would suggest for follow up.',
				},
				{
					id: 'summary',
					label: 'Summary',
					instructions: 'In less than 250 words provide a succinct account of the investigation as a whole, including what was done, why it was done, what was found out and what you recommend as a course of action. Imagine a busy manager (or in this case Minister) glancing over this section to obtain all the info they need to make the big but defendable decisions.',
				},
			]
			return boxes;
		}
		
		function readEditorOptions() {
			var editorOptions = {
				language: 'en',
				height: '200px',
				toolbar: [ //jshint ignore:line
					{name: 'format', items: ['Format'] },
					{name: 'basicstyles', items: ['Bold', 'Italic', 'Strike', 'Underline', 'Subscript', 'Superscript']},
					{name: 'paragraph', items: ['BulletedList', 'NumberedList']},
					{name: 'forms', items: ['Outdent', 'Indent']},
					{name: 'editing', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight']},
					{name: 'links', items: ['Link', 'Unlink']},
					{name: 'insert', items: ['Table']},
					{name: 'tools', items: ['Maximize']},
					//'/',
					{name: 'styles',items: ['PasteText', 'PasteFromWord', 'RemoveFormat']},
					{name: 'clipboard', items: ['Undo', 'Redo']},
					//{name: 'document', items: ['PageBreak', 'Source']}
				],
				extraPlugins: 'wordcount',
				wordcount: {
					showParagraphs: false,
					showWordCount: true,
					showCharCount: false,
				}
			};
			return editorOptions;
		}
		
		function readLastSaved() {
			//API: get last saved date/time from DB
			var lastSaved = 'not yet saved';
			return lastSaved;
		}
		
		function readNoteTechniques() {
			var techniques = angular.copy(techniqueFactory.readTechniques(false, true));
			techniques.push(resultFactory.readQuickVue());	//Add additional info
			return techniques;
		}
		
		function readReport() {
			//API: Get the user's report from the DB
			var report = [];
			for(var i = 0; i < boxes.length; i++) {
				report[i] = "";
			}
			return report;
		}
		
		function readSubmitted() {
			//API: read submitted status from DB
			var submitted = false;
			return submitted;
		}
		
		function save() {
			//API: Save report
			lastSaved = getDate();
			//return now;
		}
		
		function submit() {
			save();
			//API: Set submitted to true in DB
			submitted = true;
			//return submitted;
		}
	}
})();