<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 */
class StandardsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('standards', $this->paginate($this->Standards));
        $this->set('_serialize', ['standards']);
    }

    /**
     * View method
     *
     * @param string|null $id Standard id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $standard = $this->Standards->get($id, [
            'contain' => ['StandardAssays']
        ]);
        $this->set('standard', $standard);
        $this->set('_serialize', ['standard']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $standard = $this->Standards->newEntity();
        if ($this->request->is('post')) {
            $standard = $this->Standards->patchEntity($standard, $this->request->data);
            if ($this->Standards->save($standard)) {
                $this->Flash->success(__('The standard has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The standard could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('standard'));
        $this->set('_serialize', ['standard']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Standard id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $standard = $this->Standards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $standard = $this->Standards->patchEntity($standard, $this->request->data);
            if ($this->Standards->save($standard)) {
                $this->Flash->success(__('The standard has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The standard could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('standard'));
        $this->set('_serialize', ['standard']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Standard id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $standard = $this->Standards->get($id);
        if ($this->Standards->delete($standard)) {
            $this->Flash->success(__('The standard has been deleted.'));
        } else {
            $this->Flash->error(__('The standard could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
