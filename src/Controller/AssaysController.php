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

/**
 * Assays Controller
 *
 * @property \App\Model\Table\AssaysTable $Assays
 */
class AssaysController extends AppController
{
	public function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->Assays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$assaysQuery = $this->Assays->find('all', [
				'conditions' => ['attempt_id' => $attemptId],
			]);
			$assaysRaw = $assaysQuery->all();
			$assays = [];
			foreach($assaysRaw as $assay) {
				$assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id] = 1;
			}
			$status = 'success';
			$this->infolog("Assays Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Assays Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('assays', 'status'));
		$this->set('_serialize', ['assays', 'status']);
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$techniqueId = $this->request->data['techniqueId'];
			$rawAssays = $this->request->data['assays'];
			$rawStandardAssays = $this->request->data['standardAssays'];
			$money = $this->request->data['money'];
			$time = $this->request->data['time'];
			$this->infolog("Assays Save attempted. Attempt: " . $attemptId . "; Technique: " . $techniqueId . "; Money: " . $money . "; Time: " . $time . "; Assays: " . serialize($rawAssays) . "; Standard Assays: " . serialize($rawStandardAssays));
			
			if($attemptId && $token && $techniqueId && $this->Assays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
                        $moneyBefore = $attemptData->money;
                        $moneySpent = $moneyBefore - $money;
						$attemptData->money = $money;
						$attemptData->money_spent += $moneySpent;
					}
					if(!is_null($time)) {
                        $timeBefore = $attemptData->time;
                        $timeSpent = $timeBefore - $time;
						$attemptData->time = $time;
						$attemptData->time_spent += $timeSpent;
					}
				}
				else {
					$attemptData = null;
				}
				
				$connection = ConnectionManager::get('default');
				$connection->transactional(function () use ($assaysData, $standardAssaysData, $attemptData, $attemptId, $techniqueId) {
					foreach ($assaysData as $assay) {
						if(!$this->Assays->save($assay)) {
							$this->set('status', 'failed');
							$this->infolog("Assays Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
							return false;
						}
					}
					foreach ($standardAssaysData as $assay) {
						if(!$this->Assays->Attempts->StandardAssays->save($assay)) {
							$this->set('status', 'failed');
							$this->infolog("Assays Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
							return false;
						}
					}
					if(!is_null($attemptData) && !$this->Assays->Attempts->save($attemptData)) {
						$this->set('status', 'failed');
						$this->infolog("Assays Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
						return false;
					}
					$this->set('status', 'success');
					$this->infolog("Assays Save succeeded. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
				});
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Assays Save denied. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Assays Save not POST");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
