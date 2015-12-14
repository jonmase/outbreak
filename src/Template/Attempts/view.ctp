<?php $this->assign('title', ''); ?>

<div ng-controller="MainController as mainCtrl" style="width: 100%; height: 100%">
	<div ng-show="mainCtrl.loading" style="height: 100%; width: 100%; text-align: center; display: table; position: absolute;">
		<div style="display: table-cell; vertical-align: middle;">
			<span style="line-height: 45px; vertical-align: middle; display: inline-block">
				<i class="fa fa-3x fa-circle-o-notch fa-spin"></i>&nbsp;
				<span style="vertical-align: text-bottom; font-size: 1.8em;">Your attempt is loading. Sorry for the wait, it shouldn't take long...</span>
			</span>
		</div>
	</div>
	<div id="content" class="container-fluid" ng-hide="mainCtrl.loading">
		<div id="icon-bar">
			<icon-bar ng-show="currentSectionId !== 'home'"></icon-bar>
		</div>
		<div id="main" class="row" ng-class="{'no-menu': currentSectionId === 'home'}">
			<div ng-view class="col-xs-12"></div>
		</div>
	</div>
</div>

<?= $this->Html->script('lib/angular.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-route.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-sanitize.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-resource.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ui-bootstrap-tpls-0.14.3.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ckeditor/ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ng-ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-bootstrap-checkbox.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-helper-debounce.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-helper-dimensions.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-affix.js', ['block' => true]) ?>
<?= $this->Html->script('lib/jwplayer/jwplayer.js', ['block' => true]) ?>
<?= $this->Html->scriptBlock('jwplayer.key="jLpIu2sJ6Lc3h8UrTWBJam+8S/2elr+HB4I8QQ==";', ['block' => true]) ?>
<?= $this->Html->scriptBlock('var ATTEMPT_ID = ' . $attemptId . ';', ['block' => true]) ?>
<?= $this->Html->script('controllers/techniquesController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/fluAlertModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/techniqueServices.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/questionsController.js', ['block' => true]) ?>
<?= $this->Html->script('services/questionServices.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/samplesController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/samplesModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/tooLateModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/siteServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/schoolServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/sampleServices.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/labController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/labModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/beggingModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/researchAlertModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/assayServices.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/resultsController.js', ['block' => true]) ?>
<?= $this->Html->script('services/resultServices.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/reportController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/submitModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/submittedModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/reportServices.js', ['block' => true]) ?>
<?= $this->Html->script('app.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/mainController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/homeController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/introModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/outbreakAlertModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/feedbackController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/infoController.js', ['block' => true]) ?>
<?= $this->Html->script('services/sectionServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/progressServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/lockServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/mediaServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/modalServices.js', ['block' => true]) ?>
<?= $this->Html->script('directives.js', ['block' => true]) ?>
<?= $this->Html->script('filters.js', ['block' => true]) ?>

