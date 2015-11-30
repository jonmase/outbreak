<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Attempts Controller
 *
 * @property \App\Model\Table\AttemptsTable $Attempts
 */
class AttemptsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LtiUsers']
        ];
        $this->set('attempts', $this->paginate($this->Attempts));
        $this->set('_serialize', ['attempts']);
    }

    /**
     * View method
     *
     * @param string|null $id Attempt id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attempt = $this->Attempts->get($id, [
            'contain' => ['LtiUsers', 'Schools', 'Assays', 'Notes', 'QuestionAnswers', 'QuestionScores', 'Reports', 'StandardAssays', 'TechniqueUsefulness']
        ]);
        $this->set('attempt', $attempt);
        $this->set('_serialize', ['attempt']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attempt = $this->Attempts->newEntity();
        if ($this->request->is('post')) {
            $attempt = $this->Attempts->patchEntity($attempt, $this->request->data);
            if ($this->Attempts->save($attempt)) {
                $this->Flash->success(__('The attempt has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The attempt could not be saved. Please, try again.'));
            }
        }
        $ltiUsers = $this->Attempts->LtiUsers->find('list', ['limit' => 200]);
        $schools = $this->Attempts->Schools->find('list', ['limit' => 200]);
        $this->set(compact('attempt', 'ltiUsers', 'schools'));
        $this->set('_serialize', ['attempt']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attempt id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attempt = $this->Attempts->get($id, [
            'contain' => ['Schools']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attempt = $this->Attempts->patchEntity($attempt, $this->request->data);
            if ($this->Attempts->save($attempt)) {
                $this->Flash->success(__('The attempt has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The attempt could not be saved. Please, try again.'));
            }
        }
        $ltiUsers = $this->Attempts->LtiUsers->find('list', ['limit' => 200]);
        $schools = $this->Attempts->Schools->find('list', ['limit' => 200]);
        $this->set(compact('attempt', 'ltiUsers', 'schools'));
        $this->set('_serialize', ['attempt']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Attempt id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attempt = $this->Attempts->get($id);
        if ($this->Attempts->delete($attempt)) {
            $this->Flash->success(__('The attempt has been deleted.'));
        } else {
            $this->Flash->error(__('The attempt could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
