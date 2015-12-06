<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Samples Controller
 *
 * @property \App\Model\Table\SamplesTable $Samples
 */
class SamplesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
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
    public function view($id = null)
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
    public function add()
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
    public function edit($id = null)
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
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sample = $this->Samples->get($id);
        if ($this->Samples->delete($sample)) {
            $this->Flash->success(__('The sample has been deleted.'));
        } else {
            $this->Flash->error(__('The sample could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
