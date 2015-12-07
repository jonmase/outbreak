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
				if(!isset($samples[$sample->site_id])) { $samples[$sample->site_id] = []; }
				if(!isset($samples[$sample->site_id][$sample->school_id])) { $samples[$sample->site_id][$sample->school_id] = []; }
				if(!isset($samples[$sample->site_id][$sample->school_id][$sample->child_id])) { $samples[$sample->site_id][$sample->school_id][$sample->child_id] = []; }
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

    /**
     * Index method
     *
     * @return void
     */
    /*public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Sites', 'Schools', 'Children', 'SampleStages']
        ];
        $this->set('samples', $this->paginate($this->Samples));
        $this->set('_serialize', ['samples']);
    }

    /**
     * View method
     *
     * @param string|null $id Sample id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $sample = $this->Samples->get($id, [
            'contain' => ['Attempts', 'Sites', 'Schools', 'Children', 'SampleStages', 'Assays']
        ]);
        $this->set('sample', $sample);
        $this->set('_serialize', ['sample']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $sample = $this->Samples->newEntity();
        if ($this->request->is('post')) {
            $sample = $this->Samples->patchEntity($sample, $this->request->data);
            if ($this->Samples->save($sample)) {
                $this->Flash->success(__('The sample has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sample could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Samples->Attempts->find('list', ['limit' => 200]);
        $sites = $this->Samples->Sites->find('list', ['limit' => 200]);
        $schools = $this->Samples->Schools->find('list', ['limit' => 200]);
        $children = $this->Samples->Children->find('list', ['limit' => 200]);
        $sampleStages = $this->Samples->SampleStages->find('list', ['limit' => 200]);
        $this->set(compact('sample', 'attempts', 'sites', 'schools', 'children', 'sampleStages'));
        $this->set('_serialize', ['sample']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sample id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $sample = $this->Samples->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sample = $this->Samples->patchEntity($sample, $this->request->data);
            if ($this->Samples->save($sample)) {
                $this->Flash->success(__('The sample has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sample could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Samples->Attempts->find('list', ['limit' => 200]);
        $sites = $this->Samples->Sites->find('list', ['limit' => 200]);
        $schools = $this->Samples->Schools->find('list', ['limit' => 200]);
        $children = $this->Samples->Children->find('list', ['limit' => 200]);
        $sampleStages = $this->Samples->SampleStages->find('list', ['limit' => 200]);
        $this->set(compact('sample', 'attempts', 'sites', 'schools', 'children', 'sampleStages'));
        $this->set('_serialize', ['sample']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sample id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sample = $this->Samples->get($id);
        if ($this->Samples->delete($sample)) {
            $this->Flash->success(__('The sample has been deleted.'));
        } else {
            $this->Flash->error(__('The sample could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
