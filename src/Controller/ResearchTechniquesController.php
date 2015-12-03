<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * ResearchTechniques Controller
 *
 * @property \App\Model\Table\ResearchTechniquesTable $ResearchTechniques
 */
class ResearchTechniquesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		//$this->autoRender = false;
		$query = $this->ResearchTechniques->find('all');
		//$techniquesQuery = $this->Techniques->find('all');
		$techniques = $query->all();
		$this->set(compact('techniques'));
		$this->set('_serialize', ['techniques']);
		//pr($techniques->toArray());
	}

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('researchTechniques', $this->paginate($this->ResearchTechniques));
        $this->set('_serialize', ['researchTechniques']);
    }

    /**
     * View method
     *
     * @param string|null $id Research Technique id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $researchTechnique = $this->ResearchTechniques->get($id, [
            'contain' => []
        ]);
        $this->set('researchTechnique', $researchTechnique);
        $this->set('_serialize', ['researchTechnique']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $researchTechnique = $this->ResearchTechniques->newEntity();
        if ($this->request->is('post')) {
            $researchTechnique = $this->ResearchTechniques->patchEntity($researchTechnique, $this->request->data);
            if ($this->ResearchTechniques->save($researchTechnique)) {
                $this->Flash->success(__('The research technique has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The research technique could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('researchTechnique'));
        $this->set('_serialize', ['researchTechnique']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Research Technique id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $researchTechnique = $this->ResearchTechniques->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $researchTechnique = $this->ResearchTechniques->patchEntity($researchTechnique, $this->request->data);
            if ($this->ResearchTechniques->save($researchTechnique)) {
                $this->Flash->success(__('The research technique has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The research technique could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('researchTechnique'));
        $this->set('_serialize', ['researchTechnique']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Research Technique id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $researchTechnique = $this->ResearchTechniques->get($id);
        if ($this->ResearchTechniques->delete($researchTechnique)) {
            $this->Flash->success(__('The research technique has been deleted.'));
        } else {
            $this->Flash->error(__('The research technique could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
