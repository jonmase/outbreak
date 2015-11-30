<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LtiContexts Controller
 *
 * @property \App\Model\Table\LtiContextsTable $LtiContexts
 */
class LtiContextsController extends AppController
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
        $this->set('ltiContexts', $this->paginate($this->LtiContexts));
        $this->set('_serialize', ['ltiContexts']);
    }

    /**
     * View method
     *
     * @param string|null $id Lti Context id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ltiContext = $this->LtiContexts->get($id, [
            'contain' => ['LtiKeys', 'LtiContexts']
        ]);
        $this->set('ltiContext', $ltiContext);
        $this->set('_serialize', ['ltiContext']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ltiContext = $this->LtiContexts->newEntity();
        if ($this->request->is('post')) {
            $ltiContext = $this->LtiContexts->patchEntity($ltiContext, $this->request->data);
            if ($this->LtiContexts->save($ltiContext)) {
                $this->Flash->success(__('The lti context has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti context could not be saved. Please, try again.'));
            }
        }
        $ltiKeys = $this->LtiContexts->LtiKeys->find('list', ['limit' => 200]);
        $this->set(compact('ltiContext', 'ltiKeys'));
        $this->set('_serialize', ['ltiContext']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lti Context id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ltiContext = $this->LtiContexts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ltiContext = $this->LtiContexts->patchEntity($ltiContext, $this->request->data);
            if ($this->LtiContexts->save($ltiContext)) {
                $this->Flash->success(__('The lti context has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti context could not be saved. Please, try again.'));
            }
        }
        $ltiKeys = $this->LtiContexts->LtiKeys->find('list', ['limit' => 200]);
        $this->set(compact('ltiContext', 'ltiKeys'));
        $this->set('_serialize', ['ltiContext']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lti Context id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ltiContext = $this->LtiContexts->get($id);
        if ($this->LtiContexts->delete($ltiContext)) {
            $this->Flash->success(__('The lti context has been deleted.'));
        } else {
            $this->Flash->error(__('The lti context could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
