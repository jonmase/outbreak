<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TechniqueUsefulness Controller
 *
 * @property \App\Model\Table\TechniqueUsefulnessTable $TechniqueUsefulness
 */
class TechniqueUsefulnessController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Techniques']
        ];
        $this->set('techniqueUsefulness', $this->paginate($this->TechniqueUsefulness));
        $this->set('_serialize', ['techniqueUsefulness']);
    }

    /**
     * View method
     *
     * @param string|null $id Technique Usefulnes id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $techniqueUsefulnes = $this->TechniqueUsefulness->get($id, [
            'contain' => ['Attempts', 'Techniques']
        ]);
        $this->set('techniqueUsefulnes', $techniqueUsefulnes);
        $this->set('_serialize', ['techniqueUsefulnes']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $techniqueUsefulnes = $this->TechniqueUsefulness->newEntity();
        if ($this->request->is('post')) {
            $techniqueUsefulnes = $this->TechniqueUsefulness->patchEntity($techniqueUsefulnes, $this->request->data);
            if ($this->TechniqueUsefulness->save($techniqueUsefulnes)) {
                $this->Flash->success(__('The technique usefulnes has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technique usefulnes could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->TechniqueUsefulness->Attempts->find('list', ['limit' => 200]);
        $techniques = $this->TechniqueUsefulness->Techniques->find('list', ['limit' => 200]);
        $this->set(compact('techniqueUsefulnes', 'attempts', 'techniques'));
        $this->set('_serialize', ['techniqueUsefulnes']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Technique Usefulnes id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $techniqueUsefulnes = $this->TechniqueUsefulness->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $techniqueUsefulnes = $this->TechniqueUsefulness->patchEntity($techniqueUsefulnes, $this->request->data);
            if ($this->TechniqueUsefulness->save($techniqueUsefulnes)) {
                $this->Flash->success(__('The technique usefulnes has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technique usefulnes could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->TechniqueUsefulness->Attempts->find('list', ['limit' => 200]);
        $techniques = $this->TechniqueUsefulness->Techniques->find('list', ['limit' => 200]);
        $this->set(compact('techniqueUsefulnes', 'attempts', 'techniques'));
        $this->set('_serialize', ['techniqueUsefulnes']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Technique Usefulnes id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $techniqueUsefulnes = $this->TechniqueUsefulness->get($id);
        if ($this->TechniqueUsefulness->delete($techniqueUsefulnes)) {
            $this->Flash->success(__('The technique usefulnes has been deleted.'));
        } else {
            $this->Flash->error(__('The technique usefulnes could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
