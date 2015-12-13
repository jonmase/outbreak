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
			$status = 'success';
			$this->log("Notes Loaded. Attempt: " . $attemptId, 'info');
		}
		else {
			$status = 'denied';
			$this->log("Notes Load denied. Attempt: " . $attemptId, 'info');
		}
		
		$this->set(compact('notes', 'status'));
		$this->set('_serialize', ['notes', 'status']);
	}

	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$techniqueId = $this->request->data['techniqueId'];
			$note = $this->request->data['note'];
			$this->log("Note Save attempted. Attempt: " . $attemptId . "; Technique: " . $techniqueId . "; Note: " . serialize($note), 'info');
			
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
					$this->set('status', 'success');
					$this->log("Note Save succeeded. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
				} else {
					$this->set('status', 'failed');
					$this->log("Note Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
				}
			}
			else {
				$this->set('status', 'denied');
				$this->log("Note Save denied. Attempt: " . $attemptId . "; Technique: " . $techniqueId, 'info');
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->log("Note Save not POST", 'info');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
