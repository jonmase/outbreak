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
	public function load($attemptId = null) {
		if($attemptId && $this->TechniqueUsefulness->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$usefulQuery = $this->TechniqueUsefulness->find('list', ['conditions' => ['attempt_id' => $attemptId], 'order' => ['technique_id' => 'ASC'], 'keyField' => 'technique_id', 'valueField' => 'useful']);
			$usefulness = $usefulQuery->toArray();
			
			//$techniqueQuery = $this->TechniqueUsefulness->Techniques->find('list', ['conditions' => ['lab_only' => 0], 'order' => ['id' => 'ASC'], 'valueField' => 'code']);
			//$revisionTechniques = $techniqueQuery->toArray();
			
			/*$usefulness = [];
			foreach($revisionTechniques as $id => $code) {
				if(isset($rawUsefulness[$id])) {
					$usefulness[$id] = $rawUsefulness[$id];
				}
				else {
					$usefulness[$id] = null;
				}
			}*/
			//pr($revisionTechniques);
			//pr($usefulness);
			
			$this->set(compact('usefulness'));
			$this->set('_serialize', ['usefulness']);
			//pr($techniques->toArray());
		}
		else {
			pr('denied');
		}
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$techniqueId = $this->request->data['techniqueId'];
			$usefulness = $this->request->data['usefulness'];
			
			if($attemptId && $techniqueId && $this->TechniqueUsefulness->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
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
					$this->set('message', 'Useful techniques save succeeded');
				} else {
					$this->set('message', 'Useful techniques save failed');
				}
			}
			else {
				$this->set('message', 'Useful techniques save denied');
			}
		}
		else {
			$this->set('message', 'Useful techniques save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
