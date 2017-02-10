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
	angular.module('flu')
		.factory('failedRequestInterceptor', failedRequestInterceptor);

	failedRequestInterceptor.$inject = ['$injector', '$q', 'modalFactory'];
		
	function failedRequestInterceptor($injector, $q, modalFactory) {
		var failedRequestHandler = {
			responseError: function(rejection) {
                var $uibModal = $injector.get('$uibModal'); //Inject the uibModal - causes circular dependency if try to do this the normal way
				$uibModal.open(modalFactory.getErrorModalOptions());    //Open the error modal
                return $q.reject(rejection);    //Reject the request
			}
		};
		return failedRequestHandler;
	}
})();