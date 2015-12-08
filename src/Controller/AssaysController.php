<?php
namespace App\Controller;

use App\Controller\AppController;

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
