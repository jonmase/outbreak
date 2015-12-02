(function() {
	angular.module('flu.samples')
		.factory('schoolFactory', schoolFactory)
		
	function schoolFactory() {
		//Variables
		var schools = readSchools();
		
		//Exposed Methods
		var factory = {
			getSchools: getSchools,
		};
		return factory;
		
		//Methods

		function getSchools() { 
			return schools; 
		}
		
		function readSchools() {
			//API: get the schools from the DB? [constant]
			//API: Get acuteDisabled from the DB for each school
			var schools = [
				{
					id: 'school1',
					name: 'Cabot Road Primary School, Westbridge',
					details: 'Infected December 2015. Children are convalescent.',
					//infected: 'Dec 2015',
					//current: 'Children have recovered',
					acute: false,	//Can acute samples be taken
					acuteDisabled: false,	//Are acute checkboxes disabled for this school. Will be set to true once user has seen the "can't get acute samples" message
					convalescent: true,	//Can convalescent samples be taken
					//convalescentDisabled: false,	//Are convalescent checkboxes disabled for this school. Currently no reason why they should ever be
					//showReturnTripCheck: false,	//Toggle for whether return trip check text and box should be shown - always start as false (and will always be false here as can't take acute samples)
					//returnTripOk: false,	//Toggle for whether return trip has been OK'd. Not currenlty used
					children: [
						{id: 'ja', name: 'Jennifer A'},
						{id: 'js', name: 'Jason S'},
						{id: 'kt', name: 'Kasim T'},
						{id: 'dl', name: 'Dawn L'},
					],
				},
				{
					id: 'school2',
					name: 'St. Bride\'s Primary School, Tovington',
					details: 'Infected January 2016. Children are still symptomatic.',
					//infected: 'Jan 2016',
					//current: 'Children still symptomatic',
					acute: true,
					acuteDisabled: false,
					convalescent: true,
					//convalescentDisabled: false,	//Are convalescent checkboxes disabled for this school. Currently no reason why they should ever be
					//showReturnTripCheck: false,
					//returnTripOk: false,
					children: [
						{id: 'jb', name: 'Johnny B'},
						{id: 'ai', name: 'Ann I'},
						{id: 'jo', name: 'Jamal O'},
					],
				}
			];
			
			return schools;
		}
	}
})();