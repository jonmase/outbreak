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
			$this->set(compact('samples'));
			$this->set('_serialize', ['samples']);
			//pr($resources);
		}
		else {
			pr('denied');
		}
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$rawSamples = $this->request->data['samples'];
			$happiness = $this->request->data['happiness'];
			
			if($attemptId && $rawSamples && $this->Samples->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
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
										'before_submit' => 1
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
					$attempt = $this->Samples->Attempts->get($attemptId);
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
				$connection->transactional(function () use ($samplesData, $attempt) {
					foreach ($samplesData as $sample) {
						//pr($sample);
						if(!$this->Samples->save($sample)) {
							$this->set('message', 'Sample save error');
							return false;
						}
					}
					if(!is_null($attempt) && !$this->Samples->Attempts->save($attempt)) {
						$this->set('message', 'Sample save error');
						return false;
					}
					$this->set('message', 'success');
				});
			}
			else {
				$this->set('message', 'Samples save denied');
			}
		}
		else {
			$this->set('message', 'Samples save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
