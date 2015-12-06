<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * SamplesStages Controller
 *
 * @property \App\Model\Table\SamplesStagesTable $SamplesStages
 */
class SampleStagesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->SampleStages->find('all', [
			'order' => ['SampleStages.order' => 'ASC'],
		]);
		$stages = $query->all();
		$this->set(compact('stages'));
		$this->set('_serialize', ['stages']);
		//pr($sites->toArray());
	}

    /**
     * Index method
     *
     * @return void
     */
    /*public function index()
    {
        $this->set('samplesStages', $this->paginate($this->SamplesStages));
        $this->set('_serialize', ['samplesStages']);
    }

    /**
     * View method
     *
     * @param string|null $id Samples Stage id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $samplesStage = $this->SamplesStages->get($id, [
            'contain' => []
        ]);
        $this->set('samplesStage', $samplesStage);
        $this->set('_serialize', ['samplesStage']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $samplesStage = $this->SamplesStages->newEntity();
        if ($this->request->is('post')) {
            $samplesStage = $this->SamplesStages->patchEntity($samplesStage, $this->request->data);
            if ($this->SamplesStages->save($samplesStage)) {
                $this->Flash->success(__('The samples stage has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The samples stage could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('samplesStage'));
        $this->set('_serialize', ['samplesStage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Samples Stage id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $samplesStage = $this->SamplesStages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $samplesStage = $this->SamplesStages->patchEntity($samplesStage, $this->request->data);
            if ($this->SamplesStages->save($samplesStage)) {
                $this->Flash->success(__('The samples stage has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The samples stage could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('samplesStage'));
        $this->set('_serialize', ['samplesStage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Samples Stage id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
   /* public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $samplesStage = $this->SamplesStages->get($id);
        if ($this->SamplesStages->delete($samplesStage)) {
            $this->Flash->success(__('The samples stage has been deleted.'));
        } else {
            $this->Flash->error(__('The samples stage could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
