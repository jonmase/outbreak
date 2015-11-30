<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LtiResources Controller
 *
 * @property \App\Model\Table\LtiResourcesTable $LtiResources
 */
class LtiResourcesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LtiKeys', 'LtiResourceLinks']
        ];
        $this->set('ltiResources', $this->paginate($this->LtiResources));
        $this->set('_serialize', ['ltiResources']);
    }

    /**
     * View method
     *
     * @param string|null $id Lti Resource id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ltiResource = $this->LtiResources->get($id, [
            'contain' => ['LtiKeys', 'LtiResourceLinks']
        ]);
        $this->set('ltiResource', $ltiResource);
        $this->set('_serialize', ['ltiResource']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ltiResource = $this->LtiResources->newEntity();
        if ($this->request->is('post')) {
            $ltiResource = $this->LtiResources->patchEntity($ltiResource, $this->request->data);
            if ($this->LtiResources->save($ltiResource)) {
                $this->Flash->success(__('The lti resource has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti resource could not be saved. Please, try again.'));
            }
        }
        $ltiKeys = $this->LtiResources->LtiKeys->find('list', ['limit' => 200]);
        $ltiResourceLinks = $this->LtiResources->LtiResourceLinks->find('list', ['limit' => 200]);
        $this->set(compact('ltiResource', 'ltiKeys', 'ltiResourceLinks'));
        $this->set('_serialize', ['ltiResource']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lti Resource id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ltiResource = $this->LtiResources->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ltiResource = $this->LtiResources->patchEntity($ltiResource, $this->request->data);
            if ($this->LtiResources->save($ltiResource)) {
                $this->Flash->success(__('The lti resource has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti resource could not be saved. Please, try again.'));
            }
        }
        $ltiKeys = $this->LtiResources->LtiKeys->find('list', ['limit' => 200]);
        $ltiResourceLinks = $this->LtiResources->LtiResourceLinks->find('list', ['limit' => 200]);
        $this->set(compact('ltiResource', 'ltiKeys', 'ltiResourceLinks'));
        $this->set('_serialize', ['ltiResource']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lti Resource id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ltiResource = $this->LtiResources->get($id);
        if ($this->LtiResources->delete($ltiResource)) {
            $this->Flash->success(__('The lti resource has been deleted.'));
        } else {
            $this->Flash->error(__('The lti resource could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
