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
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

/**
 * Samples Controller
 *
 * @property \App\Model\Table\SamplesTable $Samples
 */
class SamplesController extends AppController
{
	public function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->Samples->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$samplesQuery = $this->Samples->find('all', [
				'conditions' => ['attempt_id' => $attemptId],
			]);
			$samplesRaw = $samplesQuery->all();
			$samples = [];
			foreach($samplesRaw as $sample) {
				$samples[$sample->site_id][$sample->school_id][$sample->child_id][$sample->sample_stage_id] = 1;
			}
			
			$status = 'success';
			$this->infolog("Samples Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Samples Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('samples', 'status'));
		$this->set('_serialize', ['samples', 'status']);
	}
	
	public function save() {
		if($this->request->is('post')) {
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$rawSamples = $this->request->data['samples'];
			$happiness = $this->request->data['happiness'];
			$this->infolog("Samples Save attempted. Attempt: " . $attemptId . "; Happiness: " . $happiness . "; Samples: " . serialize($rawSamples));
			
			if($attemptId && $token && $rawSamples && $this->Samples->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
				
				$connection = ConnectionManager::get('default');
				$connection->transactional(function () use ($samplesData, $attempt, $attemptId) {
					foreach ($samplesData as $sample) {
						if(!$this->Samples->save($sample)) {
							$this->set('status', 'failed');
							$this->infolog("Samples Save failed Attempt: " . $attemptId);
							return false;
						}
					}
					if(!is_null($attempt) && !$this->Samples->Attempts->save($attempt)) {
						$this->set('status', 'failed');
						$this->infolog("Samples Save failed Attempt: " . $attemptId);
						return false;
					}
					$this->set('status', 'success');
					$this->infolog("Samples Save succeeded. Attempt: " . $attemptId);
				});
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Samples Save denied. Attempt: " . $attemptId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Samples Save not POST ");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
