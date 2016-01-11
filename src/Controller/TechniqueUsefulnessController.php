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
			$this->infolog("Technique Usefulness Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Technique Usefulness Load denied. Attempt: " . $attemptId);
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
			$this->infolog("Useful Techniques Save attempted. Attempt: " . $attemptId . "; Technique: " . $techniqueId . "; Usefulness: " . $usefulness);
			
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
					$this->infolog("Useful Techniques Save succeeded. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
				} else {
					$this->set('status', 'failed');
					$this->infolog("Useful Techniques Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Useful Techniques Save denied. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Useful Techniques Save not POST");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
