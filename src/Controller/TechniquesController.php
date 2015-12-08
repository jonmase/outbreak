<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Techniques Controller
 *
 * @property \App\Model\Table\TechniquesTable $Techniques
 */
class TechniquesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Techniques->find('all', [
			'order' => ['Techniques.order' => 'ASC'],
			'contain' => 'TechniqueResults',
		]);
		$rawTechniques = $query->all();
		$techniques = [];
		foreach($rawTechniques as $technique) {
			$techniques[$technique->id] = $technique;
		}
		$this->set(compact('techniques'));
		$this->set('_serialize', ['techniques']);
		//pr($techniques->toArray());
	}

    /**
     * Index method
     *
     * @return void
     */
    /*public function index()
    {
        $this->set('techniques', $this->paginate($this->Techniques));
        $this->set('_serialize', ['techniques']);
    }*/

    /**
     * View method
     *
     * @param string|null $id Technique id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $technique = $this->Techniques->get($id, [
            'contain' => ['Assays', 'Notes', 'StandardAssays', 'TechniqueResults', 'TechniqueUsefulness']
        ]);
        $this->set('technique', $technique);
        $this->set('_serialize', ['technique']);
    }*/

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
   /* public function add()
    {
        $technique = $this->Techniques->newEntity();
        if ($this->request->is('post')) {
            $technique = $this->Techniques->patchEntity($technique, $this->request->data);
            if ($this->Techniques->save($technique)) {
                $this->Flash->success(__('The technique has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technique could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('technique'));
        $this->set('_serialize', ['technique']);
    }*/

    /**
     * Edit method
     *
     * @param string|null $id Technique id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $technique = $this->Techniques->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $technique = $this->Techniques->patchEntity($technique, $this->request->data);
            if ($this->Techniques->save($technique)) {
                $this->Flash->success(__('The technique has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technique could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('technique'));
        $this->set('_serialize', ['technique']);
    }*/

    /**
     * Delete method
     *
     * @param string|null $id Technique id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $technique = $this->Techniques->get($id);
        if ($this->Techniques->delete($technique)) {
            $this->Flash->success(__('The technique has been deleted.'));
        } else {
            $this->Flash->error(__('The technique could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
