(function() {
	angular.module('flu.filters', [])
		.filter('unsafe', ['$sce', unsafe])
		.filter('assayedSampleFilter', assayedSampleFilter)
		.filter('lineBreaksFilter', lineBreaksFilter)
		.filter('subobjectFilter', subobjectFilter);

	function unsafe($sce) { 
		return $sce.trustAsHtml; 
	}

	function assayedSampleFilter() { 
		return function(samples, samplesPerformed, type) {
			if (!samples) return samples;
			var result = {};	//Set up object for results
			angular.forEach(samples, function(sample, sampleId) {	//Loop through samples
				if((type === false && samplesPerformed[sampleId] === 1) || (type !== false && samplesPerformed[sampleId][type] === 1)) {
					result[sampleId] = sample;
				}
			});
			return result;
		}
	}

	function lineBreaksFilter() { 
		return function(input) {
			if (!input) return input;
			var result = input.replace(/\n/g, '<br />');
			return result;
		}
	}

	//Custom filter for filtering objects by second level value
	//Usage: (key, value) in object | subobjectFilter: { searchkey: searchvalue}
	function subobjectFilter() {
		return function(input, search) {
			//If one or both of input and search are not defined, return the input
			if (!input) return input;
			if (!search) return input;
			var result = {};	//Set up object for results
			angular.forEach(input, function(subobject, reference) {	//Loop through subobject
				var matches = 1;	//Assume it matches
				angular.forEach(search, function(searchValue, searchKey) {
					//If subobject does not have the search key defined, it doesn't match
					if(typeof(subobject[searchKey]) === "undefined") {
						matches = 0;
						return false;	//Move on
					}
					//Otherwise, if value of search key in subobject ('actual') does not match searchvalue ('expected'), it doesn't match
					else {
						var actual = subobject[searchKey];
						var expected = searchValue;
						if(actual !== expected) {
							matches = 0;
							return false;	//Move on
						}
					}
				});
				//If it matches, add the subobject to the result object
				if(matches) {
					result[reference] = subobject;
				}
			});
			return result;
		}
	}
})();