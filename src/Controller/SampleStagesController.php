<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SampleStages Controller
 *
 * @property \App\Model\Table\SampleStagesTable $SampleStages
 */
class SampleStagesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('sampleStages', $this->paginate($this->SampleStages));
        $this->set('_serialize', ['sampleStages']);
    }

    /**
     * View method
     *
     * @param string|null $id Sample Stage id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sampleStage = $this->SampleStages->get($id, [
            'contain' => ['Assays']
        ]);
        $this->set('sampleStage', $sampleStage);
        $this->set('_serialize', ['sampleStage']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sampleStage = $this->SampleStages->newEntity();
        if ($this->request->is('post')) {
            $sampleStage = $this->SampleStages->patchEntity($sampleStage, $this->request->data);
            if ($this->SampleStages->save($sampleStage)) {
                $this->Flash->success(__('The sample stage has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sample stage could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('sampleStage'));
        $this->set('_serialize', ['sampleStage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sample Stage id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sampleStage = $this->SampleStages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sampleStage = $this->SampleStages->patchEntity($sampleStage, $this->request->data);
            if ($this->SampleStages->save($sampleStage)) {
                $this->Flash->success(__('The sample stage has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sample stage could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('sampleStage'));
        $this->set('_serialize', ['sampleStage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sample Stage id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sampleStage = $this->SampleStages->get($id);
        if ($this->SampleStages->delete($sampleStage)) {
            $this->Flash->success(__('The sample stage has been deleted.'));
        } else {
            $this->Flash->error(__('The sample stage could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
