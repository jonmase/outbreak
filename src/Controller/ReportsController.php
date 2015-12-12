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
	public function load($attemptId = null) {
		if($attemptId && $this->Reports->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$reportsQuery = $this->Reports->find('all', [
				'conditions' => ['Reports.attempt_id' => $attemptId, 'Reports.revision' => 0],
				'order' => ['created' => 'DESC'],
				'contain' => 'ReportsSections',
				'fields' => ['id', 'attempt_id', 'revision', 'type', 'created', 'modified'],
			]);
			/*if($reportsQuery->isEmpty()) {
				
			}*/
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
			
			//pr($report);
			//pr($sections);
			//exit;
		}
		
		$this->set(compact('report', 'sections'));
		$this->set('_serialize', ['report', 'sections']);
		//pr($sites->toArray());
	}

	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$report = $this->request->data['report'];
			//$status = $this->request->data['status'];	//'revision', 'draft' or 'submitted'
			$type = $this->request->data['type'];	//'save', 'autosave', 'submit'
			
			if($attemptId && $this->Reports->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$reportQuery = $this->Reports->find('all', [
					'conditions' => ['Reports.attempt_id' => $attemptId, 'Reports.revision' => 0],
					'order' => ['created' => 'DESC'],
				]);
				$lastSavedReport = $reportQuery->first();
				if(!$reportQuery->isEmpty() && $lastSavedReport->type === 'submit') {
					//Report has already been submitted, so can't save over the top
					$this->set('message', 'Report already submitted');
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
					$connection->transactional(function () use ($reportData, $oldReportData) {
						if(!$this->Reports->save($reportData)) {
							$this->set('message', 'Report save error (new report)');
							return false;
						}
						if(!is_null($oldReportData) && !$this->Reports->save($oldReportData)) {
							$this->set('message', 'Report save error (revision report)');
							return false;
						}
						$this->set('message', 'success');
					});
				}
			}
			else {
				$this->set('message', 'Report save denied');
			}
		}
		else {
			$this->set('message', 'Report save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
