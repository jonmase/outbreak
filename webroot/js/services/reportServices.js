(function() {
	angular.module('flu.report')
		.factory('reportFactory', reportFactory);
		
	reportFactory.$inject = ['techniqueFactory', 'resultFactory', '$resource', '$q'];
	
	function reportFactory(techniqueFactory, resultFactory, $resource, $q) {
		//Variables
		var boxes, editorOptions, lastSaved, lastSaveType, noteTechniques, report, submitted; 
		
		//Exposed Methods
		var factory = {
			//cancelAutosaveTimeout: cancelAutosaveTimeout,
			setup: setup,
			getAllNotesEmpty: getAllNotesEmpty,
			getBoxes: getBoxes,
			getDate: getDate,
			getEditorOptions: getEditorOptions,
			getFirstNote: getFirstNote,
			getLastSaved: getLastSaved,
			getLastSaveType: getLastSaveType,
			getNoteTechniques: getNoteTechniques,
			getReport: getReport,
			getSubmitted: getSubmitted,
			loadReport: loadReport,
			reopen: reopen,
			save: save,
			//setAutosaveTimeout: setAutosaveTimeout,
			setEditorsReadOnly: setEditorsReadOnly,
			//submit: submit,
		}
		return factory;
		
		//Methods
		function setup() {
			//boxes = readBoxes();
			editorOptions = readEditorOptions();
			//report = readReport();
			//lastSaved = readLastSaved();
			//submitted = readSubmitted();
			noteTechniques = readNoteTechniques(); 
		}
		
		function getAllNotesEmpty() {
			var allNotesEmpty = true;
			var notes = resultFactory.getNotes();
			for(var techniqueId in noteTechniques) {
				if(notes[techniqueId] && notes[techniqueId] != '') {
					//alert(noteTechniques[techniqueId]);
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
		
		function getLastSaveType() {
			return lastSaveType;
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
		
		function loadReport() {
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'reports/load/:attemptId/:token.json', {attemptId: null, token: null});
			ReportCall.get({attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						report = result.report;
						boxes = result.sections;
						if(report) {
							lastSaveType = report.type;
							submitted = report.type === 'submit';
							lastSaved = report.modified;
						}
						else {
							report = {};
							report.reports_sections = {};
							for(var box in boxes) {
								report.reports_sections[box] = '';
							}
							submitted = false;
							lastSaved = 'not yet saved';
							lastSaveType = 'none';
						}
						deferred.resolve('Report loaded');
					}
					else {
						deferred.reject('Report load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Report load error (' + result.status + ')');
				}
			);
			return deferred.promise;
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
		
		function readNoteTechniques() {
			var techniques = angular.copy(techniqueFactory.readTechniques('results'));
			//techniques.push(resultFactory.readQuickVue());	//Add additional info
			return techniques;
		}
		
		function reopen() {
			//API: Reopen report
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'reports/reopen', {});
			ReportCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						submitted = false;
						deferred.resolve('Report reopened');
					}
					else {
						deferred.reject('Report reopen failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Report reopen error (' + result.status + ')');
				}
			);
			return deferred.promise;
			//return now;
		}
		
		function save(type) {
			if(!type) { type = 'save'; }
			//API: Save report
			var deferred = $q.defer();
			var ReportCall = $resource(URL_MODIFIER + 'reports/save', {});
			ReportCall.save({}, {attemptId: ATTEMPT_ID, token: ATTEMPT_TOKEN, report: report.reports_sections, type: type},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						lastSaved = getDate();
						lastSaveType = type;
						if(type === 'submit') {
							submitted = true;
						}
						deferred.resolve('Reports saved (' + type + ')');
					}
					else {
						deferred.reject('Report save (' + type + ') failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Report save (' + type + ') error (' + result.status + ')');
				}
			);
			return deferred.promise;
			//return now;
		}
		
		//Set the CKEditors to read only (readOnly = true) or editable (readOnly = false)
		function setEditorsReadOnly(readOnly) {
			var editors = CKEDITOR.instances;	//Get all of the editors
			angular.forEach(editors, function(editor) {
				//editor.destroy();
				editor.setReadOnly(readOnly);	//Set the editors to readOnly/editable
			});
		}
		
		/*
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
		}*/
		
		/*function readReport() {
			//API: Get the user's report from the DB
			var report = [];
			for(var i = 0; i < boxes.length; i++) {
				report[i] = "";
			}
			return report;
		}*/
		
		/*function readLastSaved() {
			//API: get last saved date/time from DB
			var lastSaved = 'not yet saved';
			return lastSaved;
		}
		
		function readSubmitted() {
			//API: read submitted status from DB
			var submitted = false;
			return submitted;
		}*/
	}
})();