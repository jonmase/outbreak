<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TechniqueResults Controller
 *
 * @property \App\Model\Table\TechniqueResultsTable $TechniqueResults
 */
class TechniqueResultsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Techniques']
        ];
        $this->set('techniqueResults', $this->paginate($this->TechniqueResults));
        $this->set('_serialize', ['techniqueResults']);
    }

    /**
     * View method
     *
     * @param string|null $id Technique Result id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $techniqueResult = $this->TechniqueResults->get($id, [
            'contain' => ['Techniques']
        ]);
        $this->set('techniqueResult', $techniqueResult);
        $this->set('_serialize', ['techniqueResult']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $techniqueResult = $this->TechniqueResults->newEntity();
        if ($this->request->is('post')) {
            $techniqueResult = $this->TechniqueResults->patchEntity($techniqueResult, $this->request->data);
            if ($this->TechniqueResults->save($techniqueResult)) {
                $this->Flash->success(__('The technique result has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technique result could not be saved. Please, try again.'));
            }
        }
        $techniques = $this->TechniqueResults->Techniques->find('list', ['limit' => 200]);
        $this->set(compact('techniqueResult', 'techniques'));
        $this->set('_serialize', ['techniqueResult']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Technique Result id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $techniqueResult = $this->TechniqueResults->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $techniqueResult = $this->TechniqueResults->patchEntity($techniqueResult, $this->request->data);
            if ($this->TechniqueResults->save($techniqueResult)) {
                $this->Flash->success(__('The technique result has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technique result could not be saved. Please, try again.'));
            }
        }
        $techniques = $this->TechniqueResults->Techniques->find('list', ['limit' => 200]);
        $this->set(compact('techniqueResult', 'techniques'));
        $this->set('_serialize', ['techniqueResult']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Technique Result id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $techniqueResult = $this->TechniqueResults->get($id);
        if ($this->TechniqueResults->delete($techniqueResult)) {
            $this->Flash->success(__('The technique result has been deleted.'));
        } else {
            $this->Flash->error(__('The technique result could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
