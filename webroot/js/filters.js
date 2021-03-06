(function() {
	angular.module('flu.filters', [])
		.filter('assayedSampleFilter', assayedSampleFilter)
		.filter('lineBreaksFilter', lineBreaksFilter)
		.filter('markFilter', markFilter)
		.filter('submittedFilter', submittedFilter)
		.filter('subobjectFilter', subobjectFilter)
		.filter('toArray', toArray)
		.filter('unsafe', ['$sce', unsafe]);

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

	//Custom filter for filtering attempts on the mark
	function markFilter() {
		return function(users, markToShow) { 
			users = users.filter(function(user){
				if(markToShow === 'All') {
					return true;
				}
				if(markToShow === 'Unmarked') {
					return typeof(user.marks) === "undefined" || !user.marks.mark;
				}
				if(markToShow === 'Marked') {
					return typeof(user.marks) !== "undefined" && user.marks.mark;
				}
				else {
					return typeof(user.marks) !== "undefined" && user.marks.mark === markToShow;
				}
			});

			return users;
		};
	}

	//Custom filter for filtering attempts on whether they have been submitted
	function submittedFilter() {
		return function(users, statusToShow) { 
			users = users.filter(function(user){
				if(statusToShow.value === 1) {
					return user.submissions > 0;
				}
				else if(statusToShow.value === 0) {
					return user.submissions === 0;
				}
				else {
					return true;
				}
			});

			return users;
		};
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
	
	/**
	 * toArray filter
	 * from https://github.com/petebacondarwin/angular-toArrayFilter
	 */
	function toArray() {
		return function (obj, addKey) {
			if (!angular.isObject(obj)) return obj;
			if ( addKey === false ) {
				return Object.keys(obj).map(function(key) {
					return obj[key];
				});
			} else {
				return Object.keys(obj).map(function (key) {
					var value = obj[key];
					return angular.isObject(value) ?
						Object.defineProperty(value, '$key', { enumerable: false, value: key}) :
						{ $key: key, $value: value };
				});
			}
		};
	}
	
	function unsafe($sce) { 
		return $sce.trustAsHtml; 
	}
})();