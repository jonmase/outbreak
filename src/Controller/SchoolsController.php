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
		//pr($rawSchools->toArray());
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
			$schoolId = $this->request->data['schoolId'];
			
			if($attemptId && $this->Schools->AttemptsSchools->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$attemptSchoolQuery = $this->Schools->AttemptsSchools->find('all', ['conditions' => ['attempt_id' => $attemptId]]);
				if($attemptSchoolQuery->isEmpty()) {
					$attemptSchool = $this->Schools->AttemptsSchools->newEntity();
					$attemptSchool->attempt_id = $attemptId;
					$attemptSchool->school_id = $schoolId;
				}
				else {
					$attemptSchool = $attemptSchoolQuery->first();
				}
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
}
