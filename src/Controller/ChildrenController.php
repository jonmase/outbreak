<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Children Controller
 *
 * @property \App\Model\Table\ChildrenTable $Children
 */
class ChildrenController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Schools']
        ];
        $this->set('children', $this->paginate($this->Children));
        $this->set('_serialize', ['children']);
    }

    /**
     * View method
     *
     * @param string|null $id Child id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $child = $this->Children->get($id, [
            'contain' => ['Schools', 'Samples']
        ]);
        $this->set('child', $child);
        $this->set('_serialize', ['child']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $child = $this->Children->newEntity();
        if ($this->request->is('post')) {
            $child = $this->Children->patchEntity($child, $this->request->data);
            if ($this->Children->save($child)) {
                $this->Flash->success(__('The child has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The child could not be saved. Please, try again.'));
            }
        }
        $schools = $this->Children->Schools->find('list', ['limit' => 200]);
        $this->set(compact('child', 'schools'));
        $this->set('_serialize', ['child']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Child id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $child = $this->Children->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $child = $this->Children->patchEntity($child, $this->request->data);
            if ($this->Children->save($child)) {
                $this->Flash->success(__('The child has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The child could not be saved. Please, try again.'));
            }
        }
        $schools = $this->Children->Schools->find('list', ['limit' => 200]);
        $this->set(compact('child', 'schools'));
        $this->set('_serialize', ['child']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Child id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $child = $this->Children->get($id);
        if ($this->Children->delete($child)) {
            $this->Flash->success(__('The child has been deleted.'));
        } else {
            $this->Flash->error(__('The child could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
