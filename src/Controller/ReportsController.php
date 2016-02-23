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

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 */
class ReportsController extends AppController
{
	public function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->Reports->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$reportsQuery = $this->Reports->find('all', [
				'conditions' => ['Reports.attempt_id' => $attemptId, 'Reports.revision' => 0],
				'order' => ['created' => 'DESC'],
				'contain' => 'ReportsSections',
				'fields' => ['id', 'attempt_id', 'revision', 'type', 'created', 'modified'],
			]);

			$report = $reportsQuery->first();
			if(!empty($report)) {
				$reports_sections = [];
				foreach($report->reports_sections as $section) {
					$reports_sections[$section->section_id] = $section->text;
				}
				$report->reports_sections = $reports_sections;
			}
			
			$sectionsQuery = $this->Reports->ReportsSections->Sections->find('all');
			$rawSections = $sectionsQuery->all();
			$sections = [];
			
			foreach($rawSections as $section) {
				$sections[$section->id] = $section;
			}
			
			$status = 'success';
			$this->infolog("Report Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Report Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('report', 'sections', 'status'));
		$this->set('_serialize', ['report', 'sections', 'status']);
	}

	public function reopen() {
		if($this->request->is('post')) {
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$this->infolog("Report Reopen attempted. Attempt: " . $attemptId);
			
			if($attemptId && $token && $this->Reports->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
				list($status, $logMessage) = $this->Reports->reopen($attemptId);
				
				$this->set('status', $status);
				$this->infolog($logMessage);
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Report Reopen denied. Attempt: " . $attemptId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Report Reopen not POST ");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}

	public function save() {
		if($this->request->is('post')) {
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$report = $this->request->data['report'];
			$type = $this->request->data['type'];	//'save', 'autosave', 'submit'
			$this->infolog("Report Save attempted. Attempt: " . $attemptId . "; Type: " . $type . "; Report: " . serialize($report));
			
			if($attemptId && $token && $this->Reports->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
				$reportQuery = $this->Reports->find('all', [
					'conditions' => ['Reports.attempt_id' => $attemptId, 'Reports.revision' => 0],
					'order' => ['created' => 'DESC'],
				]);
				$lastSavedReport = $reportQuery->first();
				if(!$reportQuery->isEmpty() && $lastSavedReport->type === 'submit') {
					//Report has already been submitted, so can't save over the top
					$this->set('status', 'Report already submitted');
					$this->infolog("Report Save rejected - report already submitted. Attempt: " . $attemptId . "; Type: " . $type);
				}
				else {
					$sectionsQuery = $this->Reports->ReportsSections->Sections->find('all');
					$sections = $sectionsQuery->all();
					
					if(!$reportQuery->isEmpty()) {
						//Change old version to a revision
						$oldReportData = $lastSavedReport;
						$oldReportData->revision = true;
					}
					else {
						$oldReportData = null;
					}
					
					$reportData = $this->Reports->newEntity();
					$reportData->attempt_id = $attemptId;
					$reportData->revision = false;
					$reportData->type = $type;
					$reportData->serialised = serialize($report);
					
					$sectionsData = [];
					foreach($sections as $section) {
						$sectionData = $this->Reports->ReportsSections->newEntity();
						$sectionData->section_id = $section->id;
						$sectionData->text = $report[$section->id];
						$sectionsData[] = $sectionData;
					}
					$reportData->reports_sections = $sectionsData;
					
					$connection = ConnectionManager::get('default');
					$connection->transactional(function () use ($reportData, $oldReportData, $attemptId, $type) {
						if(!$this->Reports->save($reportData)) {
							$this->set('status', 'failed');
							$this->infolog("Report Save failed (new report). Attempt: " . $attemptId . "; Type: " . $type);
							return false;
						}
						if(!is_null($oldReportData) && !$this->Reports->save($oldReportData)) {
							$this->set('status', 'failed');
							$this->infolog("Report Save failed (old report). Attempt: " . $attemptId . "; Type: " . $type);
							return false;
						}
						
						//If this is a submission, and the user has already been marked, then this is a resubmission, so notify the marker
						if($type === 'submit') {
							$userId = $this->Auth->user('id');
							$session = $this->request->session();	//Set Session to variable
							$ltiResourceId = $session->read('LtiResource.id');
							
							$markQuery = $this->Reports->Attempts->LtiResources->Marks->find('all', [
								'conditions' => ['Marks.lti_resource_id' => $ltiResourceId, 'Marks.lti_user_id' => $userId, 'Marks.mark' === 'Fail', 'Marks.revision' => 0],
								'order' => ['Marks.modified' => 'DESC'],
								'contain' => ['Marker', 'LtiUsers'],
							]);
							
							if(!$markQuery->isEmpty()) {
								$mark = $markQuery->first();
								$message = '<div style="font-family: Verdana, Tahoma, sans-serif; font-size: 12px;"><p>Dear ' . $mark->marker->lti_lis_person_name_given . ',</p><p>' . $mark->lti_user->lti_lis_person_name_full . ' (' . $mark->lti_user->lti_displayid . '), whose Viral Outbreak iCase report you marked as a fail, has resubmitted their report for remarking.</p><p>Please <a href="https://weblearn.ox.ac.uk/access/basiclti/site/8dd25ab4-a0ca-4e16-0073-d2a9667b58ce/content:122">go to the iCase</a> (<a href="https://weblearn.ox.ac.uk/access/basiclti/site/8dd25ab4-a0ca-4e16-0073-d2a9667b58ce/content:122">https://weblearn.ox.ac.uk/access/basiclti/site/8dd25ab4-a0ca-4e16-0073-d2a9667b58ce/content:122</a>) and remark this student. You can filter the marks to show only those that failed to make it easier to find this student.</p><p>Thank you!</p></div>';
								
								$email = new Email('smtp');
								$email->from(['msdlt@medsci.ox.ac.uk' => 'Viral Outbreak iCase Admin'])
									//->to('jon.mason@medsci.ox.ac.uk')	
									->to($mark->marker->lti_lis_person_contact_email_primary)
									->subject('Report Resubmitted for Remarking')
									->emailFormat('html')
									->send($message);
							}
						}

						$this->set('status', 'success');
						$this->infolog("Report Save succeeded. Attempt: " . $attemptId . "; Type: " . $type);
					});
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Report Save denied. Attempt: " . $attemptId . "; Type: " . $type);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Report Save not POST ");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
