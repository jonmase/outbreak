<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TechniqueUsefulness Controller
 *
 * @property \App\Model\Table\TechniqueUsefulnessTable $TechniqueUsefulness
 */
class TechniqueUsefulnessController extends AppController
{
	public function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->TechniqueUsefulness->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$usefulQuery = $this->TechniqueUsefulness->find('list', ['conditions' => ['attempt_id' => $attemptId], 'order' => ['technique_id' => 'ASC'], 'keyField' => 'technique_id', 'valueField' => 'useful']);
			$usefulness = $usefulQuery->toArray();
			
			$status = 'success';
			$this->log("Technique Usefulness Loaded. Attempt: " . $attemptId, 'info');
		}
		else {
			$status = 'denied';
			$this->log("Technique Usefulness Load denied. Attempt: " . $attemptId, 'info');
		}
		$this->set(compact('usefulness', 'status'));
		$this->set('_serialize', ['usefulness', 'status']);
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$techniqueId = $this->request->data['techniqueId'];
			$usefulness = $this->request->data['usefulness'];
			$this->log("Useful Techniques Save attempted. Attempt: " . $attemptId . "; Technique: " . $techniqueId . "; Usefulness: " . $usefulness, 'info');
			
			if($attemptId && $token && $techniqueId && $this->TechniqueUsefulness->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
				$usefulQuery = $this->TechniqueUsefulness->find('all', ['conditions' => ['attempt_id' => $attemptId, 'technique_id' => $techniqueId]]);
				$useful = $usefulQuery->first();
				if(empty($useful)) {
					//Create new record
					$useful = $this->TechniqueUsefulness->newEntity();
					$useful->attempt_id = $attemptId;
					$useful->technique_id = $techniqueId;
				}
				$useful->useful = $usefulness;

				if ($this->TechniqueUsefulness->save($useful)) {
					$this->set('status', 'success');
					$this->log("Useful Techniques Save succeeded. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
				} else {
					$this->set('status', 'failed');
					$this->log("Useful Techniques Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
				}
			}
			else {
				$this->set('status', 'denied');
				$this->log("Useful Techniques Save denied. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->log("Useful Techniques Save not POST", 'info');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
