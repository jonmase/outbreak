<?php
/**
    Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\Log\Log;
use Cake\Utility\Security;
use Cake\Network\Exception\ForbiddenException;

/**
 * Attempts Controller
 *
 * @property \App\Model\Table\AttemptsTable $Attempts
 */
class AttemptsController extends AppController
{
	public $helpers = ['Time'];
	
	public function loadProgress($attemptId = null, $token = null) {
		$userId = $this->Auth->user('id');
		if($attemptId && $token && $this->Attempts->checkUserAttempt($userId, $attemptId, $token)) {
			$progress = $this->Attempts->get($attemptId, ['fields' => $this->Attempts->progressFields]);
			$status = 'success';
			$this->infolog("Progress Load. Attempt: " . $attemptId . "; User: " . $userId);
		}
		else {
			$status = 'denied';
			$this->infolog("Progress Load denied. Attempt: " . $attemptId . "; User: " . $userId);
		}
		$this->set(compact('progress', 'status'));
		$this->set('_serialize', ['progress', 'status']);
	}
	
	public function saveProgress() {
		//Make sure the request it a POST
		if($this->request->is('post')) {
			//Get the POST data
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$sections = $this->request->data['sections'];
			$completed = $this->request->data['completed'];
			$this->infolog("Progress Save attempted. Attempt: " . $attemptId . "; Sections: " . serialize($sections) . "; Completed: " . $completed);
			
			if($attemptId && $token && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
				$attempt = $this->Attempts->get($attemptId);
				if(is_string($sections)) {
					$attempt->$sections = $completed;
				}
				else if(is_array($sections)) {
					foreach($sections as $section) {
						$attempt->$section = $completed;
					}
				}

				if ($this->Attempts->save($attempt)) {
					$this->set('status', 'success');
					$this->infolog("Progress Save succeeded. Attempt: " . $attemptId . "; Sections: " . serialize($sections));
				} else {
					$this->set('status', 'failed');
					$this->infolog("Progress Save failed. Attempt: " . $attemptId . "; Sections: " . serialize($sections));
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Progress Save denied. Attempt: " . $attemptId . "; Sections: " . serialize($sections));
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Progress Save not POST.");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
	
	public function loadResources($attemptId = null, $token = null) {
		if($attemptId && $token && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$resources = $this->Attempts->get($attemptId, ['fields' => $this->Attempts->resourceFields]);
			$status = 'success';
			$this->infolog("Resources Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Resources Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('resources', 'status'));
		$this->set('_serialize', ['resources', 'status']);
	}
	
	public function saveResources() {
		if($this->request->is('post')) {
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$money = $this->request->data['money'];
			$time = $this->request->data['time'];
			$this->infolog("Resources Save attempted. Attempt: " . $attemptId . "; Money: " . $money . "; Time: " . $time);
			
			if($attemptId && $token && (!is_null($money) || !is_null($time)) && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
				$attempt = $this->Attempts->get($attemptId);
				if(!is_null($money)) {
					$attempt->money = $money;
                    $attempt->beg_money_count += 1;
				}
				if(!is_null($time)) {
					$attempt->time = $time;
                    $attempt->beg_time_count += 1;
				}

				if ($this->Attempts->save($attempt)) {
					$this->set('status', 'success');
					$this->infolog("Resources Save succeeded. Attempt: " . $attemptId . "; Money: " . $money . "; Time: " . $time);
				} 
				else {
					$this->set('status', 'failed');
					$this->infolog("Resources Save failed. Attempt: " . $attemptId . "; Money: " . $money . "; Time: " . $time);
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Resources Save denied. Attempt: " . $attemptId . "; Money: " . $money . "; Time: " . $time);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Resources Save not POST");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
	
	public function loadHappiness($attemptId = null, $token = null) {
		if($attemptId && $token && $this->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$attemptHappiness = $this->Attempts->get($attemptId, ['fields' => ['happiness']]);
			$happiness = $attemptHappiness['happiness'];
			$status = 'success';
			$this->infolog("Happiness Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Happiness Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('happiness', 'status'));
		$this->set('_serialize', ['happiness', 'status']);
	}
		
	
	/**
     * Forbidden method
     *
     * @return void
     */
    public function forbidden()
    {
		throw new ForbiddenException('Access Denied');
    }

	/**
     * Index method
     *
     * @return void
     */
    public function index()
    {
		$session = $this->request->session();	//Set Session to variable
		$ltiResourceId = $session->read('LtiResource.id');
		$user = $this->Auth->user();
		$conditions = ['Attempts.lti_user_id' => $user['id'], 'Attempts.lti_resource_id' => $ltiResourceId, 'Attempts.archived' => 0];
        $this->paginate = [
            'conditions' => $conditions,
			'order' => ['modified' => 'DESC'],
			'limit' => 10,
        ];
		$attempts = $this->paginate($this->Attempts);
		//pr($session->read());
		$role = $session->read('User.role');
		
		$marksQuery = $this->Attempts->LtiUsers->Marks->find('all', [
			'conditions' => ['Marks.lti_resource_id' => $ltiResourceId, 'Marks.lti_user_id' => $user['id'], 'Marks.revision' => 0, 'Marks.archived' => 0],
			'order' => ['modified' => 'DESC'],
		]);
		$marks = $marksQuery->first();
		//pr($marks);

        $this->set(compact('attempts', 'role', 'marks'));
    }

    /**
     * View method
     *
     * @param string|null $id Attempt id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
		if(!$id) {
            return $this->redirect(['action' => 'index']);
		}
		
		if(!$this->Attempts->checkUserAttempt($this->Auth->user('id'), $id)) {
			//$view = new View($this);
			//$Html = $view->loadHelper('Html');
			//$emailLink = $html->link('msdlt@medsci.ox.ac.uk', 'mailto:msdlt@medsci.ox.ac.uk');
			//$this->Flash->error(__('You do not have permission to view this attempt. If this is an error, please contact ' . $emailLink));
			$this->Flash->error(__('You do not have permission to view this attempt. If you think you should be able to, please contact msdlt@medsci.ox.ac.uk, giving your SSO username and this attempt ID: '. $id));
			$this->infolog("Attempt Access Denied. Attempt: " . $id);
			return $this->redirect(['action' => 'index']);
		}
		
 		$token = Security::hash($id . $this->Auth->user('id') . $this->Auth->user('lti_user_id') . microtime(), 'sha1', true);
		$attempt = $this->Attempts->get($id, ['fields' => ['id']]);
		$attempt->token = $token;
		if (!$this->Attempts->save($attempt)) {
			$this->Flash->error(__('There was a problem accessing your attempt. Please contact msdlt@medsci.ox.ac.uk, giving your SSO username and this attempt ID: '. $id));
			$this->infolog("Attempt Access Failed (token save). Attempt: " . $id);
			return $this->redirect(['action' => 'index']);
		}
		
		$this->set('attemptId', $id);
		$this->set('attemptToken', $token);
		$this->infolog("Attempt Accessed. Attempt: $id; Token: $token");
		$this->viewBuilder()->layout('angular');
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$user = $this->Auth->user();
        $attempt = $this->Attempts->newEntity();
		$attempt->lti_user_id = $user['id'];
		
		//If unlocked is set to true in the session for this launch, set all the progress fields to complete
		$session = $this->request->session();	//Set Session to variable
		$unlocked = $session->read('LtiResource.unlocked');
		if($unlocked) {
			foreach($this->Attempts->progressFields as $field) {
				$attempt->$field = 1;
			}
		}
		$attempt->lti_resource_id = $session->read('LtiResource.id');
		$attempt->user_role = $session->read('User.role');
		
		if ($this->Attempts->save($attempt)) {
			$this->infolog("Attempt Started. Attempt: " . $attempt->id);
			return $this->redirect(['action' => 'view', $attempt->id]);
		} else {
			$this->infolog("Attempt Start Failed. Attempt: " . $attempt->id);
			$this->Flash->error(__('The attempt could not be started. Please try again. If the problem persists, please contact msdlt@medsci.ox.ac.uk'));
			return $this->redirect(['action' => 'index']);
		}
    }
}
