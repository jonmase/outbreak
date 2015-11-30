<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LtiUsers Controller
 *
 * @property \App\Model\Table\LtiUsersTable $LtiUsers
 */
class LtiUsersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LtiKeys']
        ];
        $this->set('ltiUsers', $this->paginate($this->LtiUsers));
        $this->set('_serialize', ['ltiUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id Lti User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ltiUser = $this->LtiUsers->get($id, [
            'contain' => ['LtiKeys', 'LtiUsers', 'Attempts']
        ]);
        $this->set('ltiUser', $ltiUser);
        $this->set('_serialize', ['ltiUser']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ltiUser = $this->LtiUsers->newEntity();
        if ($this->request->is('post')) {
            $ltiUser = $this->LtiUsers->patchEntity($ltiUser, $this->request->data);
            if ($this->LtiUsers->save($ltiUser)) {
                $this->Flash->success(__('The lti user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti user could not be saved. Please, try again.'));
            }
        }
        $ltiKeys = $this->LtiUsers->LtiKeys->find('list', ['limit' => 200]);
        $this->set(compact('ltiUser', 'ltiKeys'));
        $this->set('_serialize', ['ltiUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lti User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ltiUser = $this->LtiUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ltiUser = $this->LtiUsers->patchEntity($ltiUser, $this->request->data);
            if ($this->LtiUsers->save($ltiUser)) {
                $this->Flash->success(__('The lti user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti user could not be saved. Please, try again.'));
            }
        }
        $ltiKeys = $this->LtiUsers->LtiKeys->find('list', ['limit' => 200]);
        $this->set(compact('ltiUser', 'ltiKeys'));
        $this->set('_serialize', ['ltiUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lti User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ltiUser = $this->LtiUsers->get($id);
        if ($this->LtiUsers->delete($ltiUser)) {
            $this->Flash->success(__('The lti user has been deleted.'));
        } else {
            $this->Flash->error(__('The lti user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
