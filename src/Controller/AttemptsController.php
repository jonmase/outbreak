<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
/**
 * Attempts Controller
 *
 * @property \App\Model\Table\AttemptsTable $Attempts
 */
class AttemptsController extends AppController
{
	public $helpers = ['Time'];
	
	/*public funciton load($attemptId = null) {
		if($attemptId && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
	
	}*/
	
	public function loadProgress($attemptId = null) {
		if($attemptId && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$progress = $this->Attempts->get($attemptId, ['fields' => ['start', 'alert', 'revision', 'questions', 'sampling', 'lab', 'hidentified', 'nidentified', 'report', 'research']]);
			$this->set(compact('progress'));
			$this->set('_serialize', ['progress']);
			//pr($attempt);
		}
		else {
			pr('denied');
		}
	}
	
	public function saveProgress() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$sections = $this->request->data['sections'];
			$completed = $this->request->data['completed'];
			
			if($attemptId && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$attempt = $this->Attempts->get($attemptId);
				if(is_string($sections)) {
					$attempt->$sections = $completed;
				}
				else if(is_array($sections)) {
					foreach($sections as $section) {
						$attempt->$section = $completed;
					}
				}
				//pr($attempt);
				//exit;
				if ($this->Attempts->save($attempt)) {
					$this->set('message', 'Progress save succeeded');
				} else {
					$this->set('message', 'Progress save failed');
				}
				//$this->Attempts->save($attempt);
				//pr($attempt);
			}
			else {
				$this->set('message', 'Progress save denied');
			}
		}
		else {
			$this->set('message', 'Progress save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
	
	public function loadResources($attemptId = null) {
		if($attemptId && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$resources = $this->Attempts->get($attemptId, ['fields' => ['money', 'time']]);
			$this->set(compact('resources'));
			$this->set('_serialize', ['resources']);
			//pr($resources);
		}
		else {
			pr('denied');
		}
	}
	
	public function saveResources() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$money = $this->request->data['money'];
			$time = $this->request->data['time'];
			
			if($attemptId && (!is_null($money) || !is_null($time)) && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$attempt = $this->Attempts->get($attemptId);
				if(!is_null($money)) {
					$attempt->money = $money;
				}
				if(!is_null($time)) {
					$attempt->time = $time;
				}
				//pr($attempt);
				//exit;
				if ($this->Attempts->save($attempt)) {
					$this->set('message', 'success');
				} else {
					$this->set('message', 'Resources save failed');
				}
				//$this->Attempts->save($attempt);
				//pr($attempt);
			}
			else {
				$this->set('message', 'Resources save denied');
			}
		}
		else {
			$this->set('message', 'Resources save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
	
	public function loadHappiness($attemptId = null) {
		if($attemptId && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$attemptHappiness = $this->Attempts->get($attemptId, ['fields' => ['happiness']]);
			$happiness = $attemptHappiness['happiness'];
			$this->set(compact('happiness'));
			$this->set('_serialize', ['happiness']);
			//pr($happiness);
		}
		else {
			pr('denied');
		}
	}
}
