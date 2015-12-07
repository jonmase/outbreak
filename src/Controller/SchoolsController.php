<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Schools Controller
 *
 * @property \App\Model\Table\SchoolsTable $Schools
 */
class SchoolsController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load($attemptId = null) {
		$contain = array('Children');
		//If there is an attemptId, get school-related info for this attempt
		if($attemptId && $this->Schools->AttemptsSchools->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			//$contain[] = 'AttemptsSchools';
			$contain['AttemptsSchools'] = function ($q) use ($attemptId) {
			   return $q
					->select(['id', 'attempt_id', 'school_id', 'acuteDisabled'])
					->where(['AttemptsSchools.attempt_id' => $attemptId]);
			};
		}

		$query = $this->Schools->find('all', [
			'order' => ['Schools.order' => 'ASC'],
			'contain' => $contain,
		]);
		$rawSchools = $query->all();
		//pr($schools->toArray());
		$schools = [];
		foreach($rawSchools as $school) {
			if(!empty($school->attempts_schools)) {
				$school->acuteDisabled = $school->attempts_schools[0]->acuteDisabled;
			}
			else {
				$school->acuteDisabled = false;
			}
			if(isset($school->attempts_schools)) {
				unset($school->attempts_schools);
			}
			
			$rawChildren = $school->children;
			$school->children = [];
			foreach($rawChildren as $child) {
				$school->children[$child->id] = $child;
			}
			$schools[$school->id] = $school;
		}
		
		$this->set(compact('schools'));
		$this->set('_serialize', ['schools']);
		//pr($sites->toArray());
	}

	public function tooLate() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			
			if($attemptId && $this->Schools->AttemptsSchools->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$attemptSchoolQuery = $this->Schools->AttemptsSchools->find('all', ['conditions' => ['attempt_id' => $attemptId]]);
				$attemptSchool = $attemptSchoolQuery->first();
				$attemptSchool->acuteDisabled = 1;
				//pr($attempt);
				//exit;
				if ($this->Schools->AttemptsSchools->save($attemptSchool)) {
					$this->set('message', 'Too late save succeeded');
				} else {
					$this->set('message', 'Too late save failed');
				}
				//$this->Attempts->save($attempt);
				//pr($attempt);
			}
			else {
				$this->set('message', 'Too late save denied');
			}
		}
		else {
			$this->set('message', 'Too late save not POST');
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
        $this->set('schools', $this->paginate($this->Schools));
        $this->set('_serialize', ['schools']);
    }

    /**
     * View method
     *
     * @param string|null $id School id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $school = $this->Schools->get($id, [
            'contain' => ['Attempts', 'Children', 'Samples']
        ]);
        $this->set('school', $school);
        $this->set('_serialize', ['school']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $school = $this->Schools->newEntity();
        if ($this->request->is('post')) {
            $school = $this->Schools->patchEntity($school, $this->request->data);
            if ($this->Schools->save($school)) {
                $this->Flash->success(__('The school has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The school could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Schools->Attempts->find('list', ['limit' => 200]);
        $this->set(compact('school', 'attempts'));
        $this->set('_serialize', ['school']);
    }

    /**
     * Edit method
     *
     * @param string|null $id School id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $school = $this->Schools->get($id, [
            'contain' => ['Attempts']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $school = $this->Schools->patchEntity($school, $this->request->data);
            if ($this->Schools->save($school)) {
                $this->Flash->success(__('The school has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The school could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->Schools->Attempts->find('list', ['limit' => 200]);
        $this->set(compact('school', 'attempts'));
        $this->set('_serialize', ['school']);
    }

    /**
     * Delete method
     *
     * @param string|null $id School id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $school = $this->Schools->get($id);
        if ($this->Schools->delete($school)) {
            $this->Flash->success(__('The school has been deleted.'));
        } else {
            $this->Flash->error(__('The school could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
