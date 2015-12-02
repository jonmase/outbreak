(function() {
	angular.module('flu.samples')
		.controller('tooLateModalController', tooLateModalController);

	tooLateModalController.$inject = ['$uibModalInstance', 'reportFactory', 'lockFactory'];
	
	function tooLateModalController($uibModalInstance, reportFactory, lockFactory) {
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
			
			reportFactory.save();
			reportFactory.submit();
			
			lockFactory.setComplete('report');
			$uibModalInstance.close();
		}
		
		function cancel() {
			$uibModalInstance.dismiss('cancel');
		}
	}
})();