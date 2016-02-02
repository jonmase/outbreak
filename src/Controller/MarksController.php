<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Marks Controller
 *
 * @property \App\Model\Table\MarksTable $Marks
 */
class MarksController extends AppController
{

    public function load()
    {
		//Check user is an Instructor
		$session = $this->request->session();	//Set Session to variable
		if($session->read('User.role') !== "Instructor") {
			$status = 'denied';
			$this->infolog("Markable Users Load denied (not Instructor)");
		}
		else {
			//Get all of the user's who have attempts for this resource
			//Include learners (students) and instructors, but only show students initially
			//Get through attempts, and then post-process
			$ltiResourceId = $session->read('LtiResource.id');
			$ltiResourceId = 5;
			
			$attemptsQuery = $this->Marks->LtiResources->Attempts->find('all', [
				'conditions' => ['lti_resource_id' => $ltiResourceId],
				'order' => ['Attempts.modified' => 'DESC'],
				'contain' => [
					'LtiUsers', 
					'Samples',
					'Assays',
					'StandardAssays',
					'Reports' => function ($q) {
					   return $q
							->select(['id', 'type', 'attempt_id', 'modified'])
							->where(['Reports.type' => 'submit'])
							->contain(['ReportsSections' => ['Sections']])
							->order(['Reports.modified' => 'DESC']);
					},
				],
			]);
			$attempts = $attemptsQuery->all();
			//pr($attempts); exit;
			
			$users = [];
			$userIdsInUsersArray = [];
			foreach($attempts as $attempt) {
				$attemptUserId = $attempt['lti_user_id'];
				
				//Create an array for this user, if there isn't one already
				if(!isset($userIdsInUsersArray[$attemptUserId])) {
					$index = count($users);
					$userIdsInUsersArray[$attemptUserId] = $index;
					$users[$index] = $attempt['lti_user'];
					$users[$index]['attempts'] = [];	//Create array for attempts
					$users[$index]['attempts_count'] = 0;
					$users[$index]['submissions'] = 0;
					$users[$index]['last_submit'] = null;
					$users[$index]['most_recent_role'] = $attempt['user_role']==="Instructor"?"Demonstrator":"Student";	//Get the user's most recent role
				}
				else {
					$index = $userIdsInUsersArray[$attemptUserId];
				}
				unset($attempt['lti_user']);	//Delete the user details from the attempt
				
				//Process basic user, attempt and submission info/counts
				$users[$index]['attempts'][] = $attempt;	//Add the attempt to the attempts array for the user
				$users[$index]['attempts_count']++; //Count the attempt
				if(!empty($attempt['reports'])) {
					$users[$index]['submissions']++;
					if(!$users[$index]['last_submit'] || $attempt['reports'][0]['modified'] > $users[$index]['last_submit']) {
						$users[$index]['last_submit'] = $attempt['reports'][0]['modified'];
					}
				}
				
				//Process samples
				$samples = [];
				$sampleCounts = [
					'total' => 0,
				];
				foreach($attempt['samples'] as $sample) {
					$samples[$sample->site_id][$sample->school_id][$sample->child_id][$sample->sample_stage_id] = 1;
					if(!isset($sampleCounts[$sample->site_id])) {
						$sampleCounts[$sample->site_id] = [
							'total' => 0,
							'schools' => [],
						];
					}
					if(!isset($sampleCounts[$sample->site_id]['schools'][$sample->school_id])) {
						$sampleCounts[$sample->site_id]['schools'][$sample->school_id] = 0;
					}
					$sampleCounts['total']++;
					$sampleCounts[$sample->site_id]['total']++;
					$sampleCounts[$sample->site_id]['schools'][$sample->school_id]++;

				}
				$attempt['samples'] = $samples;
				$attempt['sampleCounts'] = $sampleCounts;
				
				//Process assays
				$assays = [];
				$assayCounts = [
					'total' => 0,
				];
				foreach($attempt['assays'] as $assay) {
					$assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id] = 1;
					
					if(!isset($assayCounts[$assay->technique_id])) {
						$assayCounts[$assay->technique_id] = [
							'total' => 0,
							'sites' => [],
						];
					}
					if(!isset($assayCounts[$assay->technique_id]['sites'][$assay->site_id])) {
						$assayCounts[$assay->technique_id]['sites'][$assay->site_id] = [
							'total' => 0,
							'schools' => [],
						];
					}
					if(!isset($assayCounts[$assay->technique_id]['sites'][$assay->site_id]['schools'][$assay->school_id])) {
						$assayCounts[$assay->technique_id]['sites'][$assay->site_id]['schools'][$assay->school_id] = 0;
					}
					$assayCounts['total']++;
					$assayCounts[$assay->technique_id]['total']++;
					$assayCounts[$assay->technique_id]['sites'][$assay->site_id]['total']++;
					$assayCounts[$assay->technique_id]['sites'][$assay->site_id]['schools'][$assay->school_id]++;
				}
				
				//Process standards assays
				$standardAssays = [];
				$standardAssayCounts = [];
				foreach($attempt['standard_assays'] as $standardAssay) {
					$standardAssays[$standardAssay->technique_id][$standardAssay->standard_id] = 1;
					
					if(!isset($standardAssayCounts[$standardAssay->technique_id])) {
						$standardAssayCounts[$standardAssay->technique_id] = 0;
					}
					if(!isset($assayCounts[$standardAssay->technique_id])) {
						$assayCounts[$standardAssay->technique_id] = [
							'total' => 0,
						];
					}
					$assayCounts['total']++;	//Increment the total assay count (for idenitfying whether to show Assays section)
					$assayCounts[$standardAssay->technique_id]['total']++;	//Increment the total assay count for this technique (for idenitfying whether to show the technique section)
					$standardAssayCounts[$standardAssay->technique_id]++;
				}
				unset($attempt['standard_assays']);
				$attempt['assays'] = $assays;
				$attempt['assayCounts'] = $assayCounts;
				$attempt['standardAssays'] = $standardAssays;
				$attempt['standardAssayCounts'] = $standardAssayCounts;
			}
			//pr($users); exit;
			
			//Get all the marks
			$marksQuery = $this->Marks->find('all', [
				'conditions' => ['lti_resource_id' => $ltiResourceId, 'revision' => 0],
				'order' => ['Marks.created' => 'DESC'],
				'contain' => ['Marker', 'Locker'],
			]);
			$marks = $marksQuery->all();
			//pr($ltiResourceId);
			//pr($marks);
			
			foreach($marks as $mark) {
				//$user['marks'] = ['mark' => null];
				//pr($mark);
				//Should never have more than one result for a particular user, but just check that we haven't already got this user
				$userIndex = $userIdsInUsersArray[$mark['lti_user_id']];
				if(empty($users[$userIndex]['marks'])) {
					//If user is locked but it is either too long ago or by this user, then unlock them
					if($mark->locked && (!$mark->locked->wasWithinLast('1 hour') || $mark->locker_id === $this->Auth->user('id'))) {
						$mark->locked = null;
						$mark->locker_id = null;
						$mark->locker = null;
					}
					
					//If user has been marked, set the 'marked' property to true
					if($mark->mark) {
						$users[$userIndex]['marked'] = true;
					}
					else {
						$users[$userIndex]['marked'] = false;
					}
					$users[$userIndex]['editing'] = false;	//Set 'editing' property to false for all users, as they cannot be being edited when the data is loaded
					
					
					$users[$userIndex]['marks'] = $mark;
				}
			}

			//pr($users); 
			//exit;
			
			$status = 'success';
		}
		
		$this->set(compact('users', 'status'));
		$this->set('_serialize', ['users', 'status']);
    }
	
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
		$session = $this->request->session();	//Set Session to variable
		if($session->read('User.role') !== "Instructor") {
			$this->redirect(['controller' => 'attempts', 'action' => 'index']);
		}
        //pr($session->read());
		$ltiResourceId = $session->read('LtiResource.id');
		$myUserId = $this->Auth->user('id');
		$this->set(compact('ltiResourceId', 'myUserId'));
		$this->viewBuilder()->layout('angular');
	}
	
	/*public function lock() {
		//Create new mark record with blank mark comments etc, but with marker_id and checked_out set
		//This gets deleted if the marker cancels the marking
		if($this->request->is('post')) {
			$userId = $this->request->data['userId'];
			$ltiResourceId = $session->read('LtiResource.id');
			$markerId = $this->Auth->user('id');
			
			$this->infolog("Mark Lock setting attempted. User: " . $userId . "; Lti Resource ID: " . $ltiResourceId . "; Marker: " . $markerId . "; Lock: " . $lock);

			
	}*/
	
	public function save($type = 'save') {
		if($this->request->is('post')) {
			$session = $this->request->session();	//Set Session to variable
			//pr($this->request->data);
			
			$userId = $this->request->data['userId'];
			$data = $this->request->data['data'];
			$ltiResourceId = $session->read('LtiResource.id');
			$markerId = $this->Auth->user('id');
			

			$this->infolog("Mark Save attempted. User: " . $userId . "; Lti Resource ID: " . $ltiResourceId . "; Marker: " . $markerId . "; data: " . serialize($data));
			
			//Get the latest mark for this resource and user
			$markQuery = $this->Marks->find('all', [
				'conditions' => ['Marks.lti_resource_id' => $ltiResourceId, 'Marks.lti_user_id' => $userId, 'Marks.revision' => 0],
				'order' => ['modified' => 'DESC'],
			]);
			$lastMark = $markQuery->first();
			//pr($lastMark);
		
			//Make sure we have a user ID and the marker is an instructor
			if($userId && $session->read('User.role') === "Instructor") {
				//Make sure the last mark is either not locked, locked by this marker, or locked > 1 hour ago
				if(!$lastMark['locked'] || $lastMark['locker_id'] === $markerId || !$lastMark['locked']->wasWithinLast('1 hour')) {
					if($type === 'save' && !$markQuery->isEmpty()) {
						//Change old version to a revision
						$oldMarkData = $lastMark;
						$oldMarkData->revision = true;
					}
					else {
						$oldMarkData = null;
					}
					
					if($type === 'save' || ($type === 'lock' && $markQuery->isEmpty())) {
						$markData = $this->Marks->newEntity();
						$markData->lti_resource_id = $ltiResourceId;
						$markData->lti_user_id = $userId;
						$markData->revision = false;
					}
					
					if($type === 'save') {
						$markData->mark = $data['mark'];
						$markData->comment = $data['comment'];
						$markData->marker_id = $markerId;
						$markData->locker_id = null;
						$markData->locked = null;
					}
					if($type === 'lock') {
						if(!$markQuery->isEmpty()) {	//Set the id to that of the last (and still current mark)
							$markData = $lastMark;
						}
						if(!$data) {
							$markData->locker_id = null;
							$markData->locked = null;
						}
						else {
							//Set the locker ID and the time it was locked
							$markData->locker_id = $markerId;
							$markData->locked = date('Y-m-d H:i:s');
						}
					}
					
					//pr($markData);
					//pr($oldMarkData);
					//exit;
					$connection = ConnectionManager::get('default');
					$connection->transactional(function () use ($type, $markData, $oldMarkData, $userId, $ltiResourceId) {
						if(!$this->Marks->save($markData)) {
							$this->set('status', 'failed');
							$this->infolog("Mark Save failed (new mark). User: " . $userId . "; Lti Resource ID: " . $ltiResourceId);
							return false;
						}
						if(!is_null($oldMarkData) && !$this->Marks->save($oldMarkData)) {
							$this->set('status', 'failed');
							$this->infolog("Mark Save failed (old mark). User: " . $userId . "; Lti Resource ID: " . $ltiResourceId);
							return false;
						}
						$this->set('status', 'success');
						$this->infolog("Mark Save succeeded. User: " . $userId . "; Lti Resource ID: " . $ltiResourceId);
					});
				}
				else {
					$this->set('status', 'locked');
					$this->infolog("Mark Save locked. User: " . $userId . "; Lti Resource ID: " . $ltiResourceId . "; Marker: " . $markerId . "; Locked By: " . $lastMark['marker_id']);
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Mark Save denied. User: " . $userId . "; Lti Resource ID: " . $ltiResourceId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Mark Save not POST ");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}

    /**
     * View method
     *
     * @param string|null $id Mark id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $mark = $this->Marks->get($id, [
            'contain' => ['LtiResources', 'LtiUsers']
        ]);
        $this->set('mark', $mark);
        $this->set('_serialize', ['mark']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $mark = $this->Marks->newEntity();
        if ($this->request->is('post')) {
            $mark = $this->Marks->patchEntity($mark, $this->request->data);
            if ($this->Marks->save($mark)) {
                $this->Flash->success(__('The mark has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mark could not be saved. Please, try again.'));
            }
        }
        $ltiResources = $this->Marks->LtiResources->find('list', ['limit' => 200]);
        $ltiUsers = $this->Marks->LtiUsers->find('list', ['limit' => 200]);
        $this->set(compact('mark', 'ltiResources', 'ltiUsers'));
        $this->set('_serialize', ['mark']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Mark id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $mark = $this->Marks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mark = $this->Marks->patchEntity($mark, $this->request->data);
            if ($this->Marks->save($mark)) {
                $this->Flash->success(__('The mark has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The mark could not be saved. Please, try again.'));
            }
        }
        $ltiResources = $this->Marks->LtiResources->find('list', ['limit' => 200]);
        $ltiUsers = $this->Marks->LtiUsers->find('list', ['limit' => 200]);
        $this->set(compact('mark', 'ltiResources', 'ltiUsers'));
        $this->set('_serialize', ['mark']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Mark id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mark = $this->Marks->get($id);
        if ($this->Marks->delete($mark)) {
            $this->Flash->success(__('The mark has been deleted.'));
        } else {
            $this->Flash->error(__('The mark could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/
}
