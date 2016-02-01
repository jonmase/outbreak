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
							<a href="" ng-click="markingCtrl.markUser(userIndex)" title="Mark"><i class="fa fa-check"></i></a>
							<a href="" ng-click="markingCtrl.hideUser(userIndex)" title="Hide"><i class="fa fa-eye-slash"></i></a>
							<a href="" ng-click="markingCtrl.showUser(userIndex)" title="Show"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div ng-show="markingCtrl.status === 'mark'" ng-cloak class="row">
			<div class="col-xs-3">
				<div id="marking-info" role="complementary" data-offset-top="62" bs-affix>
					<button type="button" class="btn btn-primary" ng-click="markingCtrl.status = 'index'"><i class="fa fa-arrow-left"></i>&nbsp; Back to List</button>
					<table class="table" id="marking-info-table">
						<tbody>
							<tr>
								<th>Username:</th><td>{{markingCtrl.currentUser.lti_displayid}}</td>
							</tr>
							<tr>
								<th>Name:</th><td>{{markingCtrl.currentUser.lti_lis_person_name_full}}</td>
							</tr>
							<tr>
								<th>Role:</th><td>{{markingCtrl.currentUser.most_recent_role}}</td>
							</tr>
							<tr>
								<th>Starts:</th><td>{{markingCtrl.currentUser.attempts_count}}</td>
							</tr>
							<tr>
								<th>Submissions:</th><td>{{markingCtrl.currentUser.submissions}}</td>
							</tr>
							<tr>
								<th>Last Submission:</th><td>{{markingCtrl.currentUser.last_submit | date: "d MMM yy 'at' H:mm"}}</td>
							</tr>
							<!--tr>
								<th>Mark:</th><td>{{}}</td>
							</tr>
							<tr>
								<th>Marked By:</th><td>{{}}</td>
							</tr>
							<tr>
								<th>Marked On:</th><td>{{}}</td>
							</tr-->
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
			<div class="col-xs-9">
				<div ng-repeat="attempt in markingCtrl.currentUser.attempts" class="panel" ng-class="{ 'panel-success':attempt.report, 'panel-default':!attempt.report }">
					<div class="panel-heading">
						<h3 class="panel-title">Attempt {{attempt.id}} - {{attempt.report?"Submitted on ":"Not Submitted"}}{{(attempt.report?attempt.reports[0].modified:null) | date: "d MMM yy 'at' H:mm"}} - Last modified on {{attempt.modified | date: "d MMM yy 'at' H:mm"}}</h3>
					</div>
					<div class="panel-body">
						<!-- Samples -->
						<div class="row" ng-if="attempt.sampleCounts.total > 0">
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
								<h3 class="no-top-margin">Samples</h3>
								<div ng-repeat="(siteIndex, site) in markingCtrl.sites" ng-if="attempt.sampleCounts[site.id].total > 0">
									<h5 class="no-top-margin">{{site.name}}</h5>
									<div class="row">
										<div class="col-xs-12 col-md-6 sample-school" ng-repeat="(schoolIndex, school) in markingCtrl.schools" ng-if="attempt.sampleCounts[site.id].schools[school.id] > 0">
											<p>{{school.name}} ({{school.acute?"Still infected":"Convalescent"}})</p>
											<table>
												<tr>
													<th><!--Child--></th>
													<th ng-repeat="(typeIndex, type) in markingCtrl.types" ng-if="school[type.stage]" ng-class="'samples-' + type.stage">{{type.stage.charAt(0).toUpperCase() + type.stage.slice(1)}}</th>
												</tr>
												<tr ng-repeat="(childIndex, child) in school.children">
													<td>{{child.name}}</td>
													<td ng-repeat="(typeIndex, type) in markingCtrl.types" ng-if="school[type.stage]" class="align-center" ng-class="'samples-' + type.stage">
														<i class="fa fa-check" ng-if="attempt.samples[site.id][school.id][child.id][type.id]"></i>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 smiley">
								<img ng-src="img/smileys/smile-o.svg" ng-show="attempt.happiness == 3" />
								<img ng-src="img/smileys/meh-o.svg" ng-show="attempt.happiness == 2" />
								<img ng-src="img/smileys/frown-o.svg" ng-show="attempt.happiness == 1" />
								<img ng-src="img/smileys/cry.svg" ng-show="attempt.happiness == 0" />
							</div>
						</div>
						
						<!-- Assays -->
						<div class="row" ng-if="attempt.assayCounts.total > 0">
							<div class="col-xs-12">
								<h3>Assays</h3>
								<div ng-repeat="(techniqueIndex, technique) in markingCtrl.techniques" ng-if="attempt.assayCounts[technique.id].total > 0">
									<h4>{{technique.menu}}</h5>
									<div ng-if="attempt.standardAssayCounts[technique.id] > 0" style="margin-bottom: 10px">
										<h6 class="no-top-margin">Standards</h6>
										<span ng-repeat="(standardIndex, standard) in attempt.standardAssays[technique.id]" ng-if="standard">
											{{markingCtrl.standards[standardIndex].name}} &nbsp; &nbsp; 
										</span>
									</div>
									<div ng-repeat="(siteIndex, site) in markingCtrl.sites" ng-if="attempt.assayCounts[technique.id].sites[site.id].total > 0">
										<h6 class="no-top-margin">{{site.name}}</h6>
										<div class="row">
											<div class="col-xs-12 col-md-6 assays-school" ng-repeat="(schoolIndex, school) in markingCtrl.schools" ng-if="attempt.assayCounts[technique.id].sites[site.id].schools[school.id] > 0">
												<p>{{school.name}} ({{school.acute?"Still infected":"Convalescent"}})</p>
												<table>
													<tr>
														<th><!--Child--></th>
														<th ng-repeat="(typeIndex, type) in markingCtrl.types" ng-if="school[type.stage]" ng-class="'assays-' + type.stage">{{type.stage.charAt(0).toUpperCase() + type.stage.slice(1)}}</th>
													</tr>
													<tr ng-repeat="(childIndex, child) in school.children">
														<td>{{child.name}}</td>
														<td ng-repeat="(typeIndex, type) in markingCtrl.types" ng-if="school[type.stage]" class="align-center" ng-class="'assays-' + type.stage">
															<i class="fa fa-check" ng-if="attempt.assays[technique.id][site.id][school.id][child.id][type.id]"></i>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Reports -->
						<div class="row" ng-if="attempt.reports.length > 0">
							<div class="col-xs-12">
								<h3>Report</h3>
								<div ng-repeat="section in attempt.reports[0].reports_sections">
									<h4>{{section.section.label}}</h4>
									<div ng-bind-html="section.text | unsafe"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->Html->script('lib/angular.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-resource.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ui-bootstrap-tpls-0.14.3.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ckeditor/ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ng-ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-bootstrap-checkbox.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-helper-debounce.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-helper-dimensions.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-affix.js', ['block' => true]) ?>
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
