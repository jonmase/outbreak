(function() {
	angular.module('flu.report')
		.controller('SubmitModalController', SubmitModalController);

	SubmitModalController.$inject = ['$uibModalInstance', 'reportFactory', 'lockFactory'];
	
	function SubmitModalController($uibModalInstance, reportFactory, lockFactory) {
		var vm = this;
		
		//Bindable Members - values

		//Bindable Members - methods
		vm.confirm = confirm;
		vm.cancel = cancel;

		function confirm() {
			var editors = CKEDITOR.instances;	//Get all of the editors
			angular.forEach(editors, function(editor) {
				//editor.destroy();
				editor.setReadOnly();	//Set the editors to readOnly
			});
			
			reportFactory.submit();
			
			lockFactory.setComplete('report');
			$uibModalInstance.close();
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();