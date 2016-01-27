<div ng-app="flu.marking" class="col-xs-12">
	<h2 class="page-title">Viral Outbreak - Marking</h2>
	<div ng-controller="MarkingController as markingCtrl">
		<div ng-hide="!markingCtrl.loading">
			<i class="fa fa-3x fa-circle-o-notch fa-spin"></i>&nbsp;
			<span style="vertical-align: text-bottom; font-size: 1.8em;">Loading attempts for marking, please wait...</span>
		</div>
		<div class="filters row" ng-if="!markingCtrl.loading">
			<div class="col-xs-6 col-sm-3">
				<label for="role_filter">Roles</label>
				<select class="form-control" id="role_filter" name="role_filter" ng-model="markingCtrl.roleToShow" ng-options="role as role.label for role in markingCtrl.rolesForFilter"></select>
			</div>
			<div class="col-xs-6 col-sm-3">
				<label for="status_filter">Submitted</label>
				<select class="form-control" id="status_filter" name="status_filter" ng-model="markingCtrl.statusToShow" ng-options="status as status.label for status in markingCtrl.statusesForFilter"></select>
			</div>
		</div>

		
		{{}}
		{{}}
		<table class="table" ng-if="!markingCtrl.loading">
			<thead>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Role</th>
					<th>Starts</th>
					<th>Submissions</th>
					<th>Last Submission</th>
					<th>Mark</th>
					<th>Marked By</th>
					<th class="actions"><?= __('Actions') ?></th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="user in markingCtrl.users | filter: { most_recent_role: markingCtrl.roleToShow.value } | submittedFilter: markingCtrl.statusToShow ">
					<td>{{user.lti_displayid}}</td>
					<td>{{user.lti_lis_person_name_full}}</td>
					<td>{{user.most_recent_role}}</td>
					<td>{{user.attempts_count}}</td>
					<td>{{user.submissions}}</td>
					<td>{{user.last_submit | date: "d MMM yy 'at' H:mm" }}</td>
					<td></td>
					<td></td>
					<td class="actions">
						<?= $this->Html->link(__('Mark'), ['action' => 'edit']) ?>
						<?= $this->Html->link(__('Hide'), ['action' => 'hide']) ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?= $this->Html->script('lib/angular.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-resource.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ui-bootstrap-tpls-0.14.3.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ckeditor/ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ng-ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-bootstrap-checkbox.js', ['block' => true]) ?>
<?= $this->Html->script('markingapp.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/markingController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/errorModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/markingServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/sampleServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/assayServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/modalServices.js', ['block' => true]) ?>
<?= $this->Html->script('filters.js', ['block' => true]) ?>
