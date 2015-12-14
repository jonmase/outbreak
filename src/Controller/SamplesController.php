<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

/**
 * Samples Controller
 *
 * @property \App\Model\Table\SamplesTable $Samples
 */
class SamplesController extends AppController
{
	public function load($attemptId = null) {
		if($attemptId && $this->Samples->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$samplesQuery = $this->Samples->find('all', [
				'conditions' => ['attempt_id' => $attemptId],
			]);
			$samplesRaw = $samplesQuery->all();
			$samples = [];
			foreach($samplesRaw as $sample) {
				//if(!isset($samples[$sample->site_id])) { $samples[$sample->site_id] = []; }
				//if(!isset($samples[$sample->site_id][$sample->school_id])) { $samples[$sample->site_id][$sample->school_id] = []; }
				//if(!isset($samples[$sample->site_id][$sample->school_id][$sample->child_id])) { $samples[$sample->site_id][$sample->school_id][$sample->child_id] = []; }
				//if(!isset($samples[$sample->site_id][$sample->school_id][$sample->child_id][$sample->sample_stage_id])) { $samples[$sample->site_id][$sample->school_id][$sample->child_id][$sample->sample_stage_id] = []; }

				$samples[$sample->site_id][$sample->school_id][$sample->child_id][$sample->sample_stage_id] = 1;
			}
			
			$status = 'success';
			$this->log("Samples Loaded. Attempt: " . $attemptId, 'info');
			//pr($resources);
		}
		else {
			$status = 'denied';
			$this->log("Samples Load denied. Attempt: " . $attemptId, 'info');
		}
		$this->set(compact('samples', 'status'));
		$this->set('_serialize', ['samples', 'status']);
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$rawSamples = $this->request->data['samples'];
			$happiness = $this->request->data['happiness'];
			$this->log("Samples Save attempted. Attempt: " . $attemptId . "; Happiness: " . $happiness . "; Samples: " . serialize($rawSamples), 'info');
			
			if($attemptId && $rawSamples && $this->Samples->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$attempt = $this->Samples->Attempts->get($attemptId);	//Get attempt, for saving happiness and identifying whether report has been submitted

				$samples = [];
				foreach($rawSamples as $siteId => $schools) {
					foreach($schools as $schoolId => $children) {
						foreach($children as $childId => $types) {
							foreach($types as $typeId => $value) {
								if($value) {
									$sample = [
										'attempt_id' => $attemptId,
										'site_id' => $siteId,
										'school_id' => $schoolId,
										'child_id' => $childId,
										'sample_stage_id' => $typeId,
										'before_submit' => !$attempt->report	//Are these samples being collected before the report has been submitted?
									];
									array_push($samples, $sample);
								}
							}
						}
					}
				}
				
				//Note: Always create new entry - should never already be saved entries
				$samplesData = $this->Samples->newEntities($samples);
				
				if(!is_null($happiness)) {
					$attempt->happiness = $happiness;
					$attempt->sampling = true;
				}
				else {
					$attempt = null;
				}
				
				//pr($samplesData);
				//pr($attempt);
				//exit;
				$connection = ConnectionManager::get('default');
				//$this->QuestionAnswers->connection()->transactional(function () use ($answers) {
				$connection->transactional(function () use ($samplesData, $attempt, $attemptId) {
					foreach ($samplesData as $sample) {
						//pr($sample);
						if(!$this->Samples->save($sample)) {
							$this->set('status', 'failed');
							$this->log("Samples Save failed Attempt: " . $attemptId, 'info');
							return false;
						}
					}
					if(!is_null($attempt) && !$this->Samples->Attempts->save($attempt)) {
						$this->set('status', 'failed');
						$this->log("Samples Save failed Attempt: " . $attemptId, 'info');
						return false;
					}
					$this->set('status', 'success');
					$this->log("Samples Save succeeded. Attempt: " . $attemptId, 'info');
				});
			}
			else {
				$this->set('status', 'denied');
				$this->log("Samples Save denied. Attempt: " . $attemptId, 'info');
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->log("Samples Save not POST ", 'info');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
