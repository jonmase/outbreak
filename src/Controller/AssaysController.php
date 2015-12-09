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
			$this->set(compact('assays'));
			$this->set('_serialize', ['assays']);
			//pr($assays);
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
			$rawAssays = $this->request->data['assays'];
			$rawStandardAssays = $this->request->data['standardAssays'];
			$money = $this->request->data['money'];
			$time = $this->request->data['time'];
			
			if($attemptId && $techniqueId && $this->Assays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
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
										'before_submit' => 1
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
							'before_submit' => 1
						];
						array_push($standardAssays, $assay);
					}
				}
				//Note: Always create new entries - should never already be saved entries
				$standardAssaysData = $this->Assays->Attempts->StandardAssays->newEntities($standardAssays);

				if(!is_null($money) || !is_null($time)) {
					$attemptData = $this->Assays->Attempts->get($attemptId);
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
				$connection->transactional(function () use ($assaysData, $standardAssaysData, $attemptData) {
					foreach ($assaysData as $assay) {
						//pr($sample);
						if(!$this->Assays->save($assay)) {
							$this->set('message', 'Assay save error');
							return false;
						}
					}
					foreach ($standardAssaysData as $assay) {
						//pr($sample);
						if(!$this->Assays->Attempts->StandardAssays->save($assay)) {
							$this->set('message', 'Assay save error');
							return false;
						}
					}
					if(!is_null($attemptData) && !$this->Assays->Attempts->save($attemptData)) {
						$this->set('message', 'Assay save error');
						return false;
					}
					$this->set('message', 'success');
				});
			}
			else {
				$this->set('message', 'Assay save denied');
			}
		}
		else {
			$this->set('message', 'Assay save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}


    /**
     * Index method
     *
     * @return void
     */
    /*public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Techniques', 'Samples', 'Standards']
        ];
        $this->set('assays', $this->paginate($this->Assays));
        $this->set('_serialize', ['assays']);
    }

    /**
     * View method
     *
     * @param string|null $id Assay id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $assay = $this->Assays->get($id, [
            'contain' => ['Attempts', 'Techniques', 'Samples', 'Standards']
        ]);
        $this->set('assay', $assay);
        $this->set('_serialize', ['assay']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $assay = $this->Assays->newEntity();
        if ($this->request->is('post')) {
            $assay = $this->Assays->patchEntity($assay, $this->request->data);
            if ($this->Assays->save($assay)) {
                $this->Flash->success(__('The assay has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The assay could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Assays->Attempts->find('list', ['limit' => 200]);
        $techniques = $this->Assays->Techniques->find('list', ['limit' => 200]);
        $samples = $this->Assays->Samples->find('list', ['limit' => 200]);
        $standards = $this->Assays->Standards->find('list', ['limit' => 200]);
        $this->set(compact('assay', 'attempts', 'techniques', 'samples', 'standards'));
        $this->set('_serialize', ['assay']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Assay id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $assay = $this->Assays->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $assay = $this->Assays->patchEntity($assay, $this->request->data);
            if ($this->Assays->save($assay)) {
                $this->Flash->success(__('The assay has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The assay could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Assays->Attempts->find('list', ['limit' => 200]);
        $techniques = $this->Assays->Techniques->find('list', ['limit' => 200]);
        $samples = $this->Assays->Samples->find('list', ['limit' => 200]);
        $standards = $this->Assays->Standards->find('list', ['limit' => 200]);
        $this->set(compact('assay', 'attempts', 'techniques', 'samples', 'standards'));
        $this->set('_serialize', ['assay']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Assay id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $assay = $this->Assays->get($id);
        if ($this->Assays->delete($assay)) {
            $this->Flash->success(__('The assay has been deleted.'));
        } else {
            $this->Flash->error(__('The assay could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
