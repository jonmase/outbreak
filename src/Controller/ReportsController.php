<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

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
			//pr($report->reports_sections);
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
			//pr($this->request->data);
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
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$report = $this->request->data['report'];
			//$status = $this->request->data['status'];	//'revision', 'draft' or 'submitted'
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
						//$oldReportData = $this->Reports->newEntity();
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
					
					//pr($reportData);
					//pr($oldReportData);
					//exit;
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
