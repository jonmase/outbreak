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
	
	
	/**
     * Index method
     *
     * @return void
     */
    public function index()
    {
		$user = $this->Auth->user();
		$conditions = ['lti_user_id' => $user['id']];
        $this->paginate = [
            'conditions' => $conditions,
			'order' => ['modified' => 'DESC'],
			'limit' => 10,
        ];
		$attempts = $this->paginate($this->Attempts);
		//pr($attempts->toArray());
        $this->set('attempts', $attempts);
		//$attemptsQuery = $this->Attempts->find('all', ['conditions' => $conditions]);
		//$attempts = $attemptsQuery->all();
		//$this->set(compact('attempts'));
        
		//$this->set('_serialize', ['attempts']);
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
        /*$attempt = $this->Attempts->get($id, [
            'contain' => ['LtiUsers', 'Schools', 'Assays', 'Notes', 'QuestionAnswers', 'QuestionScores', 'Reports', 'StandardAssays', 'TechniqueUsefulness']
        ]);
        $this->set('attempt', $attempt);
        $this->set('_serialize', ['attempt']);*/
		
		if(!$this->Attempts->checkUserAttempt($this->Auth->user('id'), $id)) {
			//$view = new View($this);
			//$Html = $view->loadHelper('Html');
			//$emailLink = $html->link('msdlt@medsci.ox.ac.uk', 'mailto:msdlt@medsci.ox.ac.uk');
			//$this->Flash->error(__('You do not have permission to view this attempt. If this is an error, please contact ' . $emailLink));
			$this->Flash->error(__('You do not have permission to view this attempt. If you think you should be able to, please contact msdlt@medsci.ox.ac.uk, giving your SSO username and this attempt ID: '. $id));
			return $this->redirect(['action' => 'index']);
		}
		
        $this->set('attemptId', $id);
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
		//pr($user);
		$attempt->lti_user_id = $user['id'];
		//pr($attempt);
		//$attempt->time = 48;
		//$attempt->money = 200;
		//$attempt->happiness = 3;
		
		if ($this->Attempts->save($attempt)) {
			//$this->Flash->success(__('The attempt has been saved.'));
			return $this->redirect(['action' => 'view', $attempt->id]);
		} else {
			//$view = new View($this);
			//$Html = $view->loadHelper('Html');
			//$emailLink = $html->link('msdlt@medsci.ox.ac.uk', 'mailto:msdlt@medsci.ox.ac.uk');
			//$this->Flash->error(__('The attempt could not be started. Please, try again. If problems persist, please contact ' . $emailLink));
			$this->Flash->error(__('You do not have permission to view this attempt. If this is an error, please contact msdlt@medsci.ox.ac.uk'));
			return $this->redirect(['action' => 'index']);
		}
    }
    /* public function add()
    {
        $attempt = $this->Attempts->newEntity();
        if ($this->request->is('post')) {
            $attempt = $this->Attempts->patchEntity($attempt, $this->request->data);
            if ($this->Attempts->save($attempt)) {
                $this->Flash->success(__('The attempt has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The attempt could not be saved. Please, try again.'));
            }
        }
        $ltiUsers = $this->Attempts->LtiUsers->find('list', ['limit' => 200]);
        $schools = $this->Attempts->Schools->find('list', ['limit' => 200]);
        $this->set(compact('attempt', 'ltiUsers', 'schools'));
        $this->set('_serialize', ['attempt']);
    }*/

    /**
     * Edit method
     *
     * @param string|null $id Attempt id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $attempt = $this->Attempts->get($id, [
            'contain' => ['Schools']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attempt = $this->Attempts->patchEntity($attempt, $this->request->data);
            if ($this->Attempts->save($attempt)) {
                $this->Flash->success(__('The attempt has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The attempt could not be saved. Please, try again.'));
            }
        }
        $ltiUsers = $this->Attempts->LtiUsers->find('list', ['limit' => 200]);
        $schools = $this->Attempts->Schools->find('list', ['limit' => 200]);
        $this->set(compact('attempt', 'ltiUsers', 'schools'));
        $this->set('_serialize', ['attempt']);
    }*/

    /**
     * Delete method
     *
     * @param string|null $id Attempt id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attempt = $this->Attempts->get($id);
        if ($this->Attempts->delete($attempt)) {
            $this->Flash->success(__('The attempt has been deleted.'));
        } else {
            $this->Flash->error(__('The attempt could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
