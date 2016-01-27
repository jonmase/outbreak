<div ng-app="flu.marking" class="col-xs-12">
	<h2 class="page-title">Viral Outbreak - Marking</h2>
	<div ng-controller="MarkingController as markingCtrl">
		<div ng-show="markingCtrl.status === 'loading'">
			<i class="fa fa-3x fa-circle-o-notch fa-spin"></i>&nbsp;
			<span style="vertical-align: text-bottom; font-size: 1.8em;">Loading attempts for marking, please wait...</span>
		</div>
		
		<div ng-if="markingCtrl.status !== 'loading'" ng-show="markingCtrl.status === 'index'" ng-cloak> 
			<div class="filters row">
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
			<table class="table">
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
					<tr ng-repeat="(userIndex, user) in markingCtrl.users | filter: { most_recent_role: markingCtrl.roleToShow.value } | submittedFilter: markingCtrl.statusToShow ">
						<td>{{user.lti_displayid}}</td>
						<td>{{user.lti_lis_person_name_full}}</td>
						<td>{{user.most_recent_role}}</td>
						<td>{{user.attempts_count}}</td>
						<td>{{user.submissions}}</td>
						<td>{{user.last_submit | date: "d MMM yy 'at' H:mm" }}</td>
						<td></td>
						<td></td>
						<td class="actions" style="font-size: 140%; padding: 4px 8px;">
							<?php /*
							<?= $this->Html->link(__('Mark'), ['action' => 'add', $ltiResourceId]) ?>
							<?= $this->Html->link(__('Hide'), ['action' => 'hide']) ?>
							<button type="button" class="btn btn-primary" ng-click="ErrorModalCtrl.confirm()"><i class="fa fa-check fa-2x"></i></button>
							<button type="button" class="btn btn-primary" ng-click="ErrorModalCtrl.confirm()"><i class="fa fa-eye-slash fa-2x"></i></button>
							<button type="button" class="btn btn-primary" ng-click="ErrorModalCtrl.confirm()"><i class="fa fa-eye fa-2x"></i></button>
							 */?>
							<a href="" ng-click="markingCtrl.markUser(userIndex)" title="Mark"><i class="fa fa-check"></i></a>
							<a href="" ng-click="markingCtrl.hideUser(userIndex)" title="Hide"><i class="fa fa-eye-slash"></i></a>
							<a href="" ng-click="markingCtrl.showUser(userIndex)" title="Show"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div ng-show="markingCtrl.status === 'mark'" ng-cloak>
			<button type="button" class="btn btn-primary" ng-click="markingCtrl.status = 'index'"><i class="fa fa-arrow-left"></i>&nbsp; Back to List</button>
			<table class="table" id="marking-info-table">
				<tbody>
					<tr>
						<th>Username:</th><td>{{markingCtrl.currentUser.lti_displayid}}</td>
						<th>Starts:</th><td>{{markingCtrl.currentUser.attempts_count}}</td>
						<th>Mark:</th><td>{{}}</td>
					</tr>
					<tr>
						<th>Name:</th><td>{{markingCtrl.currentUser.lti_lis_person_name_full}}</td>
						<th>Submissions:</th><td>{{markingCtrl.currentUser.submissions}}</td>
						<th>Marked By:</th><td>{{}}</td>
					</tr>
					<tr>
						<th>Role:</th><td>{{markingCtrl.currentUser.most_recent_role}}</td>
						<th>Last Submission:</th><td>{{markingCtrl.currentUser.last_submit | date: "d MMM yy 'at' H:mm"}}</td>
						<th>Marked on</th><td>{{}}</td>
					</tr>
				</tbody>
			</table>
			
						
			<!--dl>			
				<dt>Username:</dt><dd>{{markingCtrl.currentUser.lti_displayid}}</dd>
				<dt>Name:</dt><dd>{{markingCtrl.currentUser.lti_lis_person_name_full}}</dd>
				<dt>Role:</dt><dd>{{markingCtrl.currentUser.most_recent_role}}</dd>
				<dt>Starts:</dt><dd>{{markingCtrl.currentUser.attempts_count}}</dd>
				<dt>Submissions:</dt><dd>{{markingCtrl.currentUser.submissions}}</dd>
				<dt>Last Submission:</dt><dd>{{markingCtrl.currentUser.last_submit | date: "d MMM yy 'at' H:mm" }}</dd>
				<dt>Mark:</dt><dd>{{}}</dd>
				<dt>Marked By:</dt><dd>{{}}</dd>
			</dl-->
		</div>
	</div>
</div>

<?= $this->Html->script('lib/angular.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-resource.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ui-bootstrap-tpls-0.14.3.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ckeditor/ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ng-ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-bootstrap-checkbox.js', ['block' => true]) ?>
<?= $this->Html->scriptBlock('var URL_MODIFIER = "";', ['block' => true]) ?>
<?= $this->Html->script('markingapp.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/markingController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/errorModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/sectionServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/progressServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/lockServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/techniqueServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/siteServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/schoolServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/sampleServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/assayServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/markingServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/modalServices.js', ['block' => true]) ?>
<?= $this->Html->script('filters.js', ['block' => true]) ?>
