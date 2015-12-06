<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Schools Controller
 *
 * @property \App\Model\Table\SchoolsTable $Schools
 */
class SchoolsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('schools', $this->paginate($this->Schools));
        $this->set('_serialize', ['schools']);
    }

    /**
     * View method
     *
     * @param string|null $id School id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $school = $this->Schools->get($id, [
            'contain' => ['Attempts', 'Children', 'Samples']
        ]);
        $this->set('school', $school);
        $this->set('_serialize', ['school']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $school = $this->Schools->newEntity();
        if ($this->request->is('post')) {
            $school = $this->Schools->patchEntity($school, $this->request->data);
            if ($this->Schools->save($school)) {
                $this->Flash->success(__('The school has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The school could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Schools->Attempts->find('list', ['limit' => 200]);
        $this->set(compact('school', 'attempts'));
        $this->set('_serialize', ['school']);
    }

    /**
     * Edit method
     *
     * @param string|null $id School id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $school = $this->Schools->get($id, [
            'contain' => ['Attempts']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $school = $this->Schools->patchEntity($school, $this->request->data);
            if ($this->Schools->save($school)) {
                $this->Flash->success(__('The school has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The school could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Schools->Attempts->find('list', ['limit' => 200]);
        $this->set(compact('school', 'attempts'));
        $this->set('_serialize', ['school']);
    }

    /**
     * Delete method
     *
     * @param string|null $id School id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $school = $this->Schools->get($id);
        if ($this->Schools->delete($school)) {
            $this->Flash->success(__('The school has been deleted.'));
        } else {
            $this->Flash->error(__('The school could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
