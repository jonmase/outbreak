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
	public function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->Notes->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
			$this->infolog("Notes Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Notes Load denied. Attempt: " . $attemptId);
		}
		
		$this->set(compact('notes', 'status'));
		$this->set('_serialize', ['notes', 'status']);
	}

	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$techniqueId = $this->request->data['techniqueId'];
			$note = $this->request->data['note'];
			$this->infolog("Note Save attempted. Attempt: " . $attemptId . "; Technique: " . $techniqueId . "; Note: " . serialize($note));
			
			if($attemptId && $token && $techniqueId && $this->Notes->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
					$this->infolog("Note Save succeeded. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
				} else {
					$this->set('status', 'failed');
					$this->infolog("Note Save failed. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Note Save denied. Attempt: " . $attemptId . "; Technique: " . $techniqueId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Note Save not POST");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
