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
}
