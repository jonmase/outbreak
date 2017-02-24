<?php 
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
?>

<div ng-app="flu" class="col-xs-12">
	<div ng-controller="MarkingController as markingCtrl">
		<h2 class="page-title">
			<div class="pull-right" ng-show="markingCtrl.status === 'index' || markingCtrl.status === 'loading'"><?= $this->Html->link('<i class="fa fa-arrow-left"></i>&nbsp; Back to Your Attempts', ['controller' => 'attempts', 'action' => 'index'], ['class' => 'btn btn-primary', 'role' => 'button', 'escape' => false]) ?></div>
			Viral Outbreak - Marking
		</h2>
		<div class="filters row" ng-show="markingCtrl.status === 'index' || markingCtrl.status === 'loading'">
			<div class="col-xs-6 col-sm-3">
				<label for="role_filter">Roles</label>
				<select class="form-control" id="role_filter" name="role_filter" ng-model="markingCtrl.roleToShow" ng-options="role as role.label for role in markingCtrl.rolesForFilter"></select>
			</div>
			<div class="col-xs-6 col-sm-3">
				<label for="status_filter">Submitted</label>
				<select class="form-control" id="status_filter" name="status_filter" ng-model="markingCtrl.submitStatusToShow" ng-options="submitted as submitted.label for submitted in markingCtrl.submitStatusesForFilter"></select>
			</div>
			<div class="col-xs-6 col-sm-3">
				<label for="status_filter">Mark</label>
				<select class="form-control" id="mark_filter" name="mark_filter" ng-model="markingCtrl.markToShow" ng-options="mark for mark in markingCtrl.markOptionsForFilter"></select>
			</div>
			<div class="col-xs-6 col-sm-3">
				<label for="role_filter">Order By</label>
				<select class="form-control" id="role_filter" name="role_filter" ng-model="markingCtrl.orderBy" ng-options="order.value as order.label for order in markingCtrl.orderOptions"></select>
			</div>
		</div>
		
		<div ng-show="markingCtrl.status === 'loading'">
			<i class="fa fa-3x fa-circle-o-notch fa-spin"></i>&nbsp;
			<span style="vertical-align: text-bottom; font-size: 1.8em;">Loading users for marking, please wait...</span>
		</div>

		<div ng-if="markingCtrl.status === 'index'" ng-cloak> 
			<div>
				Showing {{filteredUsers.length}} out of {{markingCtrl.userCount}} users.
				<div class="pull-right download-button"><?= $this->Html->link('<i class="fa fa-arrow-circle-down"></i>&nbsp; Download All', ['controller' => 'marks', 'action' => 'download'], ['class' => 'btn btn-primary', 'role' => 'button', 'escape' => false]) ?></div> &nbsp; 
				<!--div class="pull-right download-button"><?= $this->Html->link('<i class="fa fa-arrow-circle-o-down"></i>&nbsp; Download Filtered', ['controller' => 'marks', 'action' => 'download'], ['class' => 'btn btn-primary', 'role' => 'button', 'escape' => false]) ?></div-->
			</div>
			
			<table class="table">
				<thead>
					<tr>
						<th>Username</th>
						<th>Name</th>
						<th>Role</th>
						<th class="align-center">Starts</th>
						<th class="align-center">Submissions</th>
						<!--th>Last Submission</th-->
						<th>Mark</th>
						<th>Marked By</th>
						<th>Resubmitted</th>
						<th class="actions"><?= __('Actions') ?></th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="(userId, user) in filteredUsers = (markingCtrl.users | toArray | filter: { most_recent_role: markingCtrl.roleToShow.value } | submittedFilter: markingCtrl.submitStatusToShow | markFilter: markingCtrl.markToShow | orderBy: markingCtrl.orderBy)">
						<td>{{user.lti_displayid}}</td>
						<td>{{user.lti_lis_person_name_full}}</td>
						<td>{{user.most_recent_role}}</td>
						<td class="align-center">{{user.attempts_count}}</td>
						<td class="align-center">{{user.submissions}}</td>
						<!--td>{{user.last_submit | date: "d MMM yy 'at' H:mm" }}</td-->
						<td>{{user.marks.mark}}</td>
						<td>{{user.marks.marker.lti_lis_person_name_full}}</td>
						<td>{{user.resubmitted?"Yes":""}}</td>
						<td class="actions" style="font-size: 140%; padding: 4px 8px;">
							<!--a href="" ng-click="markingCtrl.markUser(user.id)" ng-attr-title="{{user.marks.mark?'Edit Mark':'Mark'}}" ng-show="!user.marks.locked" ng-class="{grey: user.marks.mark}"><i class="fa fa-check"></i></a>
							<i class="fa fa-lock grey not-allowed" title="Locked by {{user.marks.locker.lti_lis_person_name_full}}" ng-show="user.marks.locked"></i-->
							<button type="button" class="btn btn-success" ng-click="markingCtrl.markUser(user.id)" ng-show="!user.marks.locked && !user.marks.mark" title="Mark"><i class="fa fa-check"></i>&nbsp; Mark</button>
							<button type="button" class="btn btn-warning" ng-click="markingCtrl.markUser(user.id)" ng-show="!user.marks.locked && user.marks.mark" title="View/Edit Mark"><i class="fa fa-pencil-square-o"></i>&nbsp; View/Edit Mark</button>
							<button type="button" class="btn btn-default not-allowed" disabled title="Locked by {{user.marks.locker.lti_lis_person_name_full}}" ng-show="user.marks.locked"><i class="fa fa-lock"></i>&nbsp; Locked</button>
							<!--a href="" ng-click="markingCtrl.hideUser(user.id)" title="Hide"><i class="fa fa-eye-slash"></i></a>
							<a href="" ng-click="markingCtrl.showUser(user.id)" title="Show"><i class="fa fa-eye"></i></a-->
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div ng-if="markingCtrl.status === 'mark'" ng-cloak class="row">
			<div class="col-xs-12 col-md-4 col-lg-3">
				<div id="marking-info" role="complementary" data-offset-top="62" bs-affix>
					<button type="button" class="btn btn-primary" ng-click="markingCtrl.cancel()"><i class="fa fa-arrow-left"></i>&nbsp; Back to List</button>
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
							<tr>
								<th>Mark:</th>
								<td ng-show="markingCtrl.currentUser.editing">
									<select class="form-control" id="mark_select" name="mark_select" ng-model="markingCtrl.currentUser.marks.mark" ng-options="mark for mark in markingCtrl.markOptions">
										<option value="">Select...</option>
									</select>
								</td>
								<td ng-show="!markingCtrl.currentUser.editing">
									{{markingCtrl.currentUser.marks.mark}}
								</td>
							</tr>
							<tr>
								<th>Comments:</th>
								<td ng-show="markingCtrl.currentUser.editing">
									<textarea class="form-control" rows="8" id="mark_comments" name="mark_comments" style="resize: vertical" ng-model="markingCtrl.currentUser.marks.comment"></textarea>
								</td>
								<td ng-show="!markingCtrl.currentUser.editing">
									{{markingCtrl.currentUser.marks.comment}}
								</td>
							</tr>
							<tr ng-show="markingCtrl.currentUser.marked">
								<th><span ng-show="markingCtrl.currentUser.editing">Last </span>Marked By:</th>
								<td>{{markingCtrl.currentUser.marks.marker.lti_lis_person_name_full}}</td>
							</tr>
							<tr ng-show="markingCtrl.currentUser.marked">
								<th><span ng-show="markingCtrl.currentUser.editing">Last </span>Marked On:</th>
								<td>{{markingCtrl.currentUser.marks.created | date: "d MMM yy 'at' H:mm"}}</td>
							</tr>
							<tr>
								<th></th>
								<td class="marking-buttons">
									<div  ng-show="!markingCtrl.currentUser.marked || markingCtrl.currentUser.editing">
										<button type="button" class="btn btn-success" ng-click="markingCtrl.save()"><i class="fa fa-check"></i>&nbsp; Save Mark</button>
										<button type="button" class="btn btn-danger" ng-click="markingCtrl.cancel()"><i class="fa fa-times"></i>&nbsp; Cancel</button>
									</div>
									<button type="button" class="btn btn-warning" ng-show="markingCtrl.currentUser.marked && !markingCtrl.currentUser.editing" ng-click="markingCtrl.edit()"><i class="fa fa-check"></i>&nbsp; Edit Mark</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-8 col-lg-9">
				<div ng-repeat="attempt in markingCtrl.currentUser.attempts" class="panel" ng-class="{ 'panel-success':attempt.reports.length > 0, 'panel-default':attempt.reports.length === 0 }">
					<div class="panel-heading" ng-class="{ 'no-bottom-border': attempt.hidden }">
						<h3 class="panel-title">
							<div class="pull-right marking-showhide">
								<a href="" ng-click="attempt.hidden = false" ng-show="attempt.hidden"><i class="fa fa-chevron-down"></i> Show</a>
								<a href="" ng-click="attempt.hidden = true" ng-show="!attempt.hidden"><i class="fa fa-chevron-up"></i> Hide</a>
							</div>
							Attempt {{attempt.id}} - {{attempt.reports.length > 0?"Submitted on ":"Not Submitted"}}{{(attempt.reports.length > 0?attempt.reports[0].modified:null) | date: "d MMM yy 'at' H:mm"}}<!-- - Last modified on {{attempt.modified | date: "d MMM yy 'at' H:mm"}}-->
						</h3>
					</div>
					<div class="panel-body" ng-class="{ 'hidden': attempt.hidden }">
						<!-- Nothing -->
						<div ng-if="!attempt.sampling">
							No samples have been collected, assays performed or reports written for this attempt.
						</div>
					
						<!-- Samples -->
						<div class="row" ng-if="attempt.sampleCounts.total > 0">
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
								<h3 class="no-top-margin">
									Samples
									<span class="marking-showhide subsection-showhide">
										<a href="" ng-click="attempt.samplesHidden = false" ng-show="attempt.samplesHidden"><i class="fa fa-chevron-down"></i> Show</a>
										<a href="" ng-click="attempt.samplesHidden = true" ng-show="!attempt.samplesHidden"><i class="fa fa-chevron-up"></i> Hide</a>
									</span>
								</h3>
								<div ng-repeat="(siteIndex, site) in markingCtrl.sites" ng-if="attempt.sampleCounts[site.id].total > 0" ng-hide="attempt.samplesHidden">
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
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 smiley" ng-hide="attempt.samplesHidden">
								<img ng-src="img/smileys/smile-o.svg" ng-show="attempt.happiness == 3" />
								<img ng-src="img/smileys/meh-o.svg" ng-show="attempt.happiness == 2" />
								<img ng-src="img/smileys/frown-o.svg" ng-show="attempt.happiness == 1" />
								<img ng-src="img/smileys/cry.svg" ng-show="attempt.happiness == 0" />
							</div>
							<div class="col-xs-12">
								<hr class="marking-subsection" ng-hide="attempt.samplesHidden" />
							</div>
						</div>
						
						<!-- Assays -->
						<div class="row" ng-if="attempt.assayCounts.total > 0">
							<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
								<h3 class="no-top-margin">
									Assays
									<span class="marking-showhide subsection-showhide">
										<a href="" ng-click="attempt.assaysHidden = false" ng-show="attempt.assaysHidden"><i class="fa fa-chevron-down"></i> Show</a>
										<a href="" ng-click="attempt.assaysHidden = true" ng-show="!attempt.assaysHidden"><i class="fa fa-chevron-up"></i> Hide</a>
									</span>
								</h3>
								<div ng-repeat="(techniqueIndex, technique) in markingCtrl.techniques" ng-if="attempt.assayCounts[technique.id].total > 0" ng-hide="attempt.assaysHidden" class="marking-assays-technique">
									<h4>{{technique.menu}}</h5>
									<div ng-if="attempt.standardAssayCounts[technique.id] > 0" class="marking-assays-site">
										<h6 class="no-top-margin">Standards</h6>
										<span ng-repeat="(standardIndex, standard) in attempt.standardAssays[technique.id]" ng-if="standard">
											{{markingCtrl.standards[standardIndex].name}} &nbsp; &nbsp; 
										</span>
									</div>
									<div ng-repeat="(siteIndex, site) in markingCtrl.sites" ng-if="attempt.assayCounts[technique.id].sites[site.id].total > 0" class="marking-assays-site">
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
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" ng-hide="attempt.assaysHidden">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">Resources Used</h4>
									</div>
									<div class="panel-body">
										<p>Time: 
                                            <span ng-if="attempt.time_spent !== null">
                                                {{attempt.time_spent}} hours
                                                <span ng-if="attempt.beg_time_count !== null"> 
                                                    <span ng-if="attempt.beg_time_count == 0"> 
                                                        (did not beg)
                                                    </span>
                                                    <span ng-if="attempt.beg_time_count == 1"> 
                                                        (begged once)
                                                    </span>
                                                    <span ng-if="attempt.beg_time_count > 1"> 
                                                        (begged {{attempt.beg_time_count}} times)
                                                    </span>
                                                </span>
                                            </span>    
                                            <span ng-if="attempt.time_spent === null">
                                                Unknown
                                            </span>
                                        </p>
										<p class="no-margin">
                                            Money:
                                            <span ng-if="attempt.money_spent !== null">
                                                £{{attempt.money_spent}}
                                                <span ng-if="attempt.beg_money_count !== null"> 
                                                    <span ng-if="attempt.beg_money_count == 0"> 
                                                        (did not beg)
                                                    </span>
                                                    <span ng-if="attempt.beg_money_count == 1"> 
                                                        (begged once)
                                                    </span>
                                                    <span ng-if="attempt.beg_money_count > 1"> 
                                                        (begged {{attempt.beg_money_count}} times)
                                                    </span>
                                                </span>
                                            </span>    
                                            <span ng-if="attempt.money_spent === null">
                                                Unknown
                                            </span>
                                        </p>
									</div>
								</div>
							</div>
							<div class="col-xs-12">
								<hr class="marking-subsection" ng-hide="attempt.assaysHidden" />
							</div>
						</div>
						
						<!-- Reports -->
						<div class="row" ng-if="attempt.reports.length > 0">
							<div class="col-xs-12">
								<h3 class="no-top-margin">
									Report
									<span class="marking-showhide subsection-showhide">
										<a href="" ng-click="attempt.reportHidden = false" ng-show="attempt.reportHidden"><i class="fa fa-chevron-down"></i> Show</a>
										<a href="" ng-click="attempt.reportHidden = true" ng-show="!attempt.reportHidden"><i class="fa fa-chevron-up"></i> Hide</a>
									</span>
								</h3>
								<div ng-repeat="section in attempt.reports[0].reports_sections" ng-hide="attempt.reportHidden">
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
    
    <script type="text/ng-template" id="error-modal.html">
        <div class="modal-header">
            <h4 class="modal-title">Someting's gone wrong</h4>
        </div>
        <div class="modal-body">
            <p>Sorry about this, there seems to have been a problem. You may have lost connection, or your session may have timed out. It could also be caused by having multiple instances of this attempt open at once. Please check your internet connection, ensure you don't have this attempt open in multiple tabs, and then try refreshing the page. </p>
            <p>Unfortunately, this means that your most recent actions/inputs may be lost. This may include any unchecked questions, uncollected samples or assays that you haven't yet carried out. Reports are automatically saved every minute, but you may lose any text you have entered since the last save. Apologies for any inconvenience or annoyance this causes.</p>
            <p>If you continue to experience problems, <email-msdlt></email-msdlt></p>
        </div>
        <div class="modal-footer">
            <div>
                <button type="button" class="btn btn-primary" ng-click="ErrorModalCtrl.confirm()">Reload</button>
            </div>
        </div>
    </script>
    
    <script type="text/ng-template" id="marking-locked-modal.html">
        <div class="modal-header">
            <h4 class="modal-title">Locked</h4>
        </div>
        <div class="modal-body">
            <p>Sorry, this user has been locked for marking by someone else since you loaded the page. Please use the button below to refresh the list of users and update their current statuses.</p>
        </div>
        <div class="modal-footer">
            <div>
                <button type="button" class="btn btn-primary" ng-click="MarkingLockedModalCtrl.confirm()">Refresh</button>
            </div>
        </div>
    </script>
</div>

<?= $this->Html->script('lib/angular.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-resource.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ui-bootstrap-tpls-1.1.2.min.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ckeditor/ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/ng-ckeditor.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-bootstrap-checkbox.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-helper-debounce.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-helper-dimensions.js', ['block' => true]) ?>
<?= $this->Html->script('lib/angular-strap-affix.js', ['block' => true]) ?>
<?= $this->Html->scriptBlock('var MY_ID = ' . $myUserId . ';', ['block' => true]) ?>
<?= $this->Html->scriptBlock('var URL_MODIFIER = "";', ['block' => true]) ?>
<?= $this->Html->script('markingapp.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/markingController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/errorModalController.js', ['block' => true]) ?>
<?= $this->Html->script('controllers/markingLockedModalController.js', ['block' => true]) ?>
<?= $this->Html->script('services/sectionServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/progressServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/lockServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/techniqueServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/siteServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/schoolServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/sampleServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/assayServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/markingServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/interceptorServices.js', ['block' => true]) ?>
<?= $this->Html->script('services/modalServices.js', ['block' => true]) ?>
<?= $this->Html->script('filters.js', ['block' => true]) ?>
<?= $this->Html->script('directives.js', ['block' => true]) ?>
