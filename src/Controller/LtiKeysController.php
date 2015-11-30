<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LtiKeys Controller
 *
 * @property \App\Model\Table\LtiKeysTable $LtiKeys
 */
class LtiKeysController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('ltiKeys', $this->paginate($this->LtiKeys));
        $this->set('_serialize', ['ltiKeys']);
    }

    /**
     * View method
     *
     * @param string|null $id Lti Key id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ltiKey = $this->LtiKeys->get($id, [
            'contain' => ['LtiContexts', 'LtiResources', 'LtiUsers']
        ]);
        $this->set('ltiKey', $ltiKey);
        $this->set('_serialize', ['ltiKey']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ltiKey = $this->LtiKeys->newEntity();
        if ($this->request->is('post')) {
            $ltiKey = $this->LtiKeys->patchEntity($ltiKey, $this->request->data);
            if ($this->LtiKeys->save($ltiKey)) {
                $this->Flash->success(__('The lti key has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti key could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ltiKey'));
        $this->set('_serialize', ['ltiKey']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lti Key id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ltiKey = $this->LtiKeys->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ltiKey = $this->LtiKeys->patchEntity($ltiKey, $this->request->data);
            if ($this->LtiKeys->save($ltiKey)) {
                $this->Flash->success(__('The lti key has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The lti key could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ltiKey'));
        $this->set('_serialize', ['ltiKey']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lti Key id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ltiKey = $this->LtiKeys->get($id);
        if ($this->LtiKeys->delete($ltiKey)) {
            $this->Flash->success(__('The lti key has been deleted.'));
        } else {
            $this->Flash->error(__('The lti key could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
