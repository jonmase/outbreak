<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Assays Controller
 *
 * @property \App\Model\Table\AssaysTable $Assays
 */
class AssaysController extends AppController
{
	public function load($attemptId = null) {
		if($attemptId && $this->Assays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$assaysQuery = $this->Assays->find('all', [
				'conditions' => ['attempt_id' => $attemptId],
			]);
			$assaysRaw = $assaysQuery->all();
			$assays = [];
			foreach($assaysRaw as $assay) {
				//if(!isset($assays[$assay->technique_id])) { $assays[$assay->technique_id] = []; }
				//if(!isset($assays[$assay->technique_id][$assay->site_id])) { $assays[$assay->technique_id][$assay->site_id] = []; }
				//if(!isset($assays[$assay->technique_id][$assay->site_id][$assay->school_id])) { $assays[$assay->technique_id][$assay->site_id][$assay->school_id] = []; }
				//if(!isset($assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id])) { $assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id] = []; }
				//if(!isset($assays[$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id])) { $assays[$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id] = []; }

				$assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id] = 1;
			}
			$status = 'success';
			$this->log("Assays Loaded. Attempt: " . $attemptId, 'info');
		}
		else {
			$status = 'denied';
			$this->log("Assays Load denied. Attempt: " . $attemptId, 'info');
		}
		$this->set(compact('assays', 'status'));
		$this->set('_serialize', ['assays', 'status']);
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$techniqueId = $this->request->data['techniqueId'];
			$rawAssays = $this->request->data['assays'];
			$rawStandardAssays = $this->request->data['standardAssays'];
			$money = $this->request->data['money'];
			$time = $this->request->data['time'];
			$this->log("Assays Save attempted. Attempt: " . $attemptId . "; Technique: " . $techniqueId . "; Money: " . $money . "; Time: " . $time . "; Assays: " . serialize($rawAssays) . "; Standard Assays: " . serialize($rawStandardAssays), 'info');
			
			if($attemptId && $techniqueId && $this->Assays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$attemptData = $this->Assays->Attempts->get($attemptId);

				$assays = [];
				foreach($rawAssays as $siteId => $schools) {
					foreach($schools as $schoolId => $children) {
						foreach($children as $childId => $types) {
							foreach($types as $typeId => $value) {
								if($value) {
									$assay = [
										'attempt_id' => $attemptId,
										'technique_id' => $techniqueId,
										'site_id' => $siteId,
										'school_id' => $schoolId,
										'child_id' => $childId,
										'sample_stage_id' => $typeId,
										'before_submit' => !$attemptData->report	//Is the assay being performed before the report has been submitted?
									];
									array_push($assays, $assay);
								}
							}
						}
					}
				}
				//Note: Always create new entries - should never already be saved entries
				$assaysData = $this->Assays->newEntities($assays);
				
				$standardAssays = [];
				foreach($rawStandardAssays as $standardId => $value) {
					if($value) {
						$assay = [
							'attempt_id' => $attemptId,
							'technique_id' => $techniqueId,
							'standard_id' => $standardId,
							'before_submit' => !$attemptData->report	//Is the assay being performed before the report has been submitted?
						];
						array_push($standardAssays, $assay);
					}
				}
				//Note: Always create new entries - should never already be saved entries
				$standardAssaysData = $this->Assays->Attempts->StandardAssays->newEntities($standardAssays);

				if(!is_null($money) || !is_null($time)) {
					if(!is_null($money)) {
						$attemptData->money = $money;
					}
					if(!is_null($time)) {
						$attemptData->time = $time;
					}
				}
				else {
					$attemptData = null;
				}
				
				//pr($assaysData);
				//pr($attemptData);
				//exit;
				$connection = ConnectionManager::get('default');
				//$this->QuestionAnswers->connection()->transactional(function () use ($answers) {
				$connection->transactional(function () use ($assaysData, $standardAssaysData, $attemptData, $attemptId, $techniqueId) {
					foreach ($assaysData as $assay) {
						//pr($sample);
						if(!$this->Assays->save($assay)) {
							$this->set('status', 'failed');
							$this->log("Assays Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
							return false;
						}
					}
					foreach ($standardAssaysData as $assay) {
						//pr($sample);
						if(!$this->Assays->Attempts->StandardAssays->save($assay)) {
							$this->set('status', 'failed');
							$this->log("Assays Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
							return false;
						}
					}
					if(!is_null($attemptData) && !$this->Assays->Attempts->save($attemptData)) {
						$this->set('status', 'failed');
						$this->log("Assays Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
						return false;
					}
					$this->set('status', 'success');
					$this->log("Assays Save succeeded. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
				});
			}
			else {
				$this->set('status', 'denied');
				$this->log("Assays Save denied. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->log("Assays Save not POST", 'info');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
