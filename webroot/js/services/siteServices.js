(function() {
	angular.module('flu.samples')
		.factory('siteFactory', siteFactory)
		
	function siteFactory() {
		//Variables
		var sites = readSites();
		var currentSiteId = 0;
		
		//Exposed Methods
		var factory = {
			getCurrentSiteId: getCurrentSiteId,
			getSites: getSites,
			getSiteIds: getSiteIds,
			setCurrentSiteId: setCurrentSiteId,
		};
		return factory;
		
		//Methods

		function getCurrentSiteId() { 
			return currentSiteId; 
		}
		
		function getSites() { 
			return sites; 
		}
		
		function getSiteIds() {
			var siteIds = {};
			for(var site = 0; site < sites.length; site++) {
				siteIds[sites[site].id] = site;
			}
			return siteIds;
		}
		
		function readSites() {
			//API: get the sites from the DB? [constant]
			var sites = [
				{
					id: 'np',
					resultId: 'n',
					//menu: 'NP Swab',
					menu: 'Nasopharyngeal Swab',
					name: 'Nasopharyngeal Swab',
					//info: '<p>Some info about Nasopharyngeal Swab</p>',
				},
				{
					id: 'blood',
					resultId: 's',
					//menu: 'Serum',
					menu: 'Blood (Serum)',
					name: 'Blood (Serum)',
					//info: '<p>Some info about Blood</p>',
				},
				{
					id: 'csf',
					resultId: 'sf',
					//menu: 'CSF',
					menu: 'Cerebrospinal Fluid (CSF)',
					name: 'Cerebrospinal Fluid (CSF)',
					//info: '<p>Some info about CSF</p>',
				},
				/*{
					id: 'urine',
					resultId: 'u',
					menu: 'Urine',
					name: 'Urine',
					info: '<p>Some info about Urine</p>',
				},*/
			];
			return sites;
		}
		
		function setCurrentSiteId(siteId) { 
			currentSiteId = siteId; 
		}
	}
})();