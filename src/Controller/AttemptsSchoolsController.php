<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AttemptsSchools Controller
 *
 * @property \App\Model\Table\AttemptsSchoolsTable $AttemptsSchools
 */
class AttemptsSchoolsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Schools']
        ];
        $this->set('attemptsSchools', $this->paginate($this->AttemptsSchools));
        $this->set('_serialize', ['attemptsSchools']);
    }

    /**
     * View method
     *
     * @param string|null $id Attempts School id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attemptsSchool = $this->AttemptsSchools->get($id, [
            'contain' => ['Attempts', 'Schools']
        ]);
        $this->set('attemptsSchool', $attemptsSchool);
        $this->set('_serialize', ['attemptsSchool']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attemptsSchool = $this->AttemptsSchools->newEntity();
        if ($this->request->is('post')) {
            $attemptsSchool = $this->AttemptsSchools->patchEntity($attemptsSchool, $this->request->data);
            if ($this->AttemptsSchools->save($attemptsSchool)) {
                $this->Flash->success(__('The attempts school has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The attempts school could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->AttemptsSchools->Attempts->find('list', ['limit' => 200]);
        $schools = $this->AttemptsSchools->Schools->find('list', ['limit' => 200]);
        $this->set(compact('attemptsSchool', 'attempts', 'schools'));
        $this->set('_serialize', ['attemptsSchool']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attempts School id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attemptsSchool = $this->AttemptsSchools->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attemptsSchool = $this->AttemptsSchools->patchEntity($attemptsSchool, $this->request->data);
            if ($this->AttemptsSchools->save($attemptsSchool)) {
                $this->Flash->success(__('The attempts school has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The attempts school could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->AttemptsSchools->Attempts->find('list', ['limit' => 200]);
        $schools = $this->AttemptsSchools->Schools->find('list', ['limit' => 200]);
        $this->set(compact('attemptsSchool', 'attempts', 'schools'));
        $this->set('_serialize', ['attemptsSchool']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Attempts School id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attemptsSchool = $this->AttemptsSchools->get($id);
        if ($this->AttemptsSchools->delete($attemptsSchool)) {
            $this->Flash->success(__('The attempts school has been deleted.'));
        } else {
            $this->Flash->error(__('The attempts school could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
