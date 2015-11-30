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

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Techniques', 'Sites', 'Schools', 'Children', 'SampleStages']
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
    public function view($id = null)
    {
        $assay = $this->Assays->get($id, [
            'contain' => ['Attempts', 'Techniques', 'Sites', 'Schools', 'Children', 'SampleStages']
        ]);
        $this->set('assay', $assay);
        $this->set('_serialize', ['assay']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
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
        $sites = $this->Assays->Sites->find('list', ['limit' => 200]);
        $schools = $this->Assays->Schools->find('list', ['limit' => 200]);
        $children = $this->Assays->Children->find('list', ['limit' => 200]);
        $sampleStages = $this->Assays->SampleStages->find('list', ['limit' => 200]);
        $this->set(compact('assay', 'attempts', 'techniques', 'sites', 'schools', 'children', 'sampleStages'));
        $this->set('_serialize', ['assay']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Assay id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
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
        $sites = $this->Assays->Sites->find('list', ['limit' => 200]);
        $schools = $this->Assays->Schools->find('list', ['limit' => 200]);
        $children = $this->Assays->Children->find('list', ['limit' => 200]);
        $sampleStages = $this->Assays->SampleStages->find('list', ['limit' => 200]);
        $this->set(compact('assay', 'attempts', 'techniques', 'sites', 'schools', 'children', 'sampleStages'));
        $this->set('_serialize', ['assay']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Assay id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $assay = $this->Assays->get($id);
        if ($this->Assays->delete($assay)) {
            $this->Flash->success(__('The assay has been deleted.'));
        } else {
            $this->Flash->error(__('The assay could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
