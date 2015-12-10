<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Notes Controller
 *
 * @property \App\Model\Table\NotesTable $Notes
 */
class NotesController extends AppController
{
	public function load($attemptId = null) {
		if($attemptId && $this->Notes->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$query = $this->Notes->find('all', [
				'conditions' => ['Notes.attempt_id' => $attemptId],
				'order' => ['Notes.technique_id' => 'ASC'],
			]);
			$rawNotes = $query->all();
			//pr($rawNotes->toArray());
			$notes = [];
			
			foreach($rawNotes as $note) {
				$notes[$note->technique_id] = $note;
			}
		}
		
		$this->set(compact('notes'));
		$this->set('_serialize', ['notes']);
		//pr($sites->toArray());
	}

	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$techniqueId = $this->request->data['techniqueId'];
			$note = $this->request->data['note'];
			
			if($attemptId && $techniqueId && $this->Notes->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$noteQuery = $this->Notes->find('all', ['conditions' => ['attempt_id' => $attemptId, 'technique_id' => $techniqueId]]);
				if($noteQuery->isEmpty()) {
					$noteData = $this->Notes->newEntity();
					$noteData->attempt_id = $attemptId;
					$noteData->technique_id = $techniqueId;
				}
				else {
					$noteData = $noteQuery->first();
				}
				$noteData->note = $note;
				//pr(noteData);
				//exit;
				if ($this->Notes->save($noteData)) {
					$this->set('message', 'success');
				} else {
					$this->set('message', 'Note save failed');
				}
				//$this->Attempts->save($attempt);
				//pr($attempt);
			}
			else {
				$this->set('message', 'Note save denied');
			}
		}
		else {
			$this->set('message', 'Note save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
	
    /**
     * Index method
     *
     * @return void
     */
    /*public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Techniques']
        ];
        $this->set('notes', $this->paginate($this->Notes));
        $this->set('_serialize', ['notes']);
    }

    /**
     * View method
     *
     * @param string|null $id Note id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $note = $this->Notes->get($id, [
            'contain' => ['Attempts', 'Techniques']
        ]);
        $this->set('note', $note);
        $this->set('_serialize', ['note']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $note = $this->Notes->newEntity();
        if ($this->request->is('post')) {
            $note = $this->Notes->patchEntity($note, $this->request->data);
            if ($this->Notes->save($note)) {
                $this->Flash->success(__('The note has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The note could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Notes->Attempts->find('list', ['limit' => 200]);
        $techniques = $this->Notes->Techniques->find('list', ['limit' => 200]);
        $this->set(compact('note', 'attempts', 'techniques'));
        $this->set('_serialize', ['note']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Note id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $note = $this->Notes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $note = $this->Notes->patchEntity($note, $this->request->data);
            if ($this->Notes->save($note)) {
                $this->Flash->success(__('The note has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The note could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Notes->Attempts->find('list', ['limit' => 200]);
        $techniques = $this->Notes->Techniques->find('list', ['limit' => 200]);
        $this->set(compact('note', 'attempts', 'techniques'));
        $this->set('_serialize', ['note']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Note id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $note = $this->Notes->get($id);
        if ($this->Notes->delete($note)) {
            $this->Flash->success(__('The note has been deleted.'));
        } else {
            $this->Flash->error(__('The note could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
