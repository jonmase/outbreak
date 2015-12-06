<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
