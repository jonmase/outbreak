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
	
	public function load($attemptId = null, $token = null) {
		$contain = array('Children');
		//If there is an attemptId, get school-related info for this attempt
		$message = "Schools Loaded";
		if($attemptId && $token && $this->Schools->AttemptsSchools->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$contain['AttemptsSchools'] = function ($q) use ($attemptId) {
			   return $q
					->select(['id', 'attempt_id', 'school_id', 'acuteDisabled'])
					->where(['AttemptsSchools.attempt_id' => $attemptId]);
			};
			$message .= ". Attempt: " . $attemptId;
		}

		$query = $this->Schools->find('all', [
			'order' => ['Schools.order' => 'ASC'],
			'contain' => $contain,
		]);
		$rawSchools = $query->all();
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
		
		$status = 'success';
		$this->infolog($message);
		$this->set(compact('schools', 'status'));
		$this->set('_serialize', ['schools', 'status']);
	}

	public function tooLate() {
		if($this->request->is('post')) {
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$schoolId = $this->request->data['schoolId'];
			$this->infolog("Too Late Save attempted. Attempt: " . $attemptId . "; School: " . $schoolId);
			
			if($attemptId && $token && $this->Schools->AttemptsSchools->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
				if ($this->Schools->AttemptsSchools->save($attemptSchool)) {
					$this->set('status', 'success');
					$this->infolog("Too Late Save succeedded. Attempt: " . $attemptId . "; School: " . $schoolId);
				} else {
					$this->set('status', 'failed');
					$this->infolog("Too Late Save failed. Attempt: " . $attemptId . "; School: " . $schoolId);
				}
			}
			else {
				$this->set('status', 'denied');
					$this->infolog("Too Late Save denied. Attempt: " . $attemptId . "; School: " . $schoolId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Too Late Save not POST");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');		
	}
}
