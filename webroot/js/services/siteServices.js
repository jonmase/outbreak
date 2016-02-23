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
		.factory('siteFactory', siteFactory)
		
	siteFactory.$inject = ['$resource', '$q'];

	function siteFactory($resource, $q) {
		//Variables
		//var sites = readSites();
		var sites;
		var currentSiteIndex = 1;
		
		//Exposed Methods
		var factory = {
			getCurrentSiteIndex: getCurrentSiteIndex,
			getSites: getSites,
			getSiteIds: getSiteIds,
			loadSites: loadSites,
			setCurrentSiteIndex: setCurrentSiteIndex,
		};
		return factory;
		
		//Methods

		function getCurrentSiteIndex() { 
			return currentSiteIndex; 
		}
		
		function getSites() { 
			return sites; 
		}
		
		function getSiteIds() {
			var siteIds = {};
			/*for(var siteIndex = 0; siteIndex < sites.length; siteIndex++) {
				siteIds[sites[siteIndex].code] = sites[siteIndex].id;
			}*/
			angular.forEach(sites, function(site, id) {
				siteIds[site.code] = id;
			});
			return siteIds;
		}
		
		function loadSites() {
			var deferred = $q.defer();
			var SitesCall = $resource(URL_MODIFIER + 'sites/load.json', {});
			SitesCall.get({},
				function(result) {
					if(typeof(result.status) !== "undefined" && result.status === 'success') {
						sites = result.sites;
						deferred.resolve('Sites loaded');
					}
					else {
						deferred.reject('Sites load failed (' + result.status + ")");
					}
				},
				function(result) {
					deferred.reject('Sites load error (' + result.status + ')');
				}
			);
			return deferred.promise;
		}
		
		/*function readSites() {
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
				//{
				//	id: 'urine',
				//	resultId: 'u',
				//	menu: 'Urine',
				//	name: 'Urine',
				//	info: '<p>Some info about Urine</p>',
				//},
			];
			return sites;
		}*/
		
		function setCurrentSiteIndex(siteIndex) { 
			currentSiteIndex = siteIndex; 
		}
	}
})();