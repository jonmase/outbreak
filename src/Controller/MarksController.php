<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

/**
 * Marks Controller
 *
 * @property \App\Model\Table\MarksTable $Marks
 */
class MarksController extends AppController
{

    public function loadUsers()
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
			
			$users = $this->Marks->getUsersForMarking($ltiResourceId, $this->Auth->user('id'));
			$userCount = count($users);
			
			$status = 'success';
		}
		
		$this->set(compact('users', 'userCount', 'status'));
		$this->set('_serialize', ['users', 'userCount', 'status']);
    }
	
    public function loadUserAttempts($userId)
    {
		//Check user is an Instructor
		$session = $this->request->session();	//Set Session to variable
		if($session->read('User.role') !== "Instructor") {
			$status = 'denied';
			$this->infolog("User Attempts Load denied (not Instructor)");
		}
		else {
			//Get all of the user's who have attempts for this resource
			//Include learners (students) and instructors, but only show students initially
			//Get through attempts, and then post-process
			$ltiResourceId = $session->read('LtiResource.id');
			
			$attempts = $this->Marks->getUserAttempts($ltiResourceId, $userId);
			$status = 'success';
		}
		//pr($attempts); exit;
		
		$this->set(compact('attempts', 'status'));
		$this->set('_serialize', ['attempts', 'status']);
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
	
	public function save($type = 'save') {
		if($this->request->is('post')) {
			$session = $this->request->session();	//Set Session to variable
			
			$user = $this->Marks->LtiUsers->get($this->request->data['userId']);
			
			//Make sure we have a user ID and the marker is an instructor
			if(!empty($user) && $session->read('User.role') === "Instructor") {
				$data = $this->request->data['data'];
				$ltiResourceId = $session->read('LtiResource.id');
				$markerId = $this->Auth->user('id');
				
				$marker = $this->Marks->LtiUsers->get($markerId);
				$this->set('marker', $marker);
				
				$this->infolog("Mark Save attempted. User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId . "; Marker: " . $markerId . "; data: " . serialize($data));
				
				//Get the latest mark for this resource and user
				$markQuery = $this->Marks->find('all', [
					'conditions' => ['Marks.lti_resource_id' => $ltiResourceId, 'Marks.lti_user_id' => $user->id, 'Marks.revision' => 0],
					'order' => ['modified' => 'DESC'],
				]);
				$lastMark = $markQuery->first();
		
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
						$markData->lti_user_id = $user->id;
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
					$connection->transactional(function () use ($type, $markData, $oldMarkData, $user, $ltiResourceId) {
						if(!$this->Marks->save($markData)) {
							$this->set('status', 'failed');
							$this->infolog("Mark Save failed (new mark). User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId);
							return false;
						}
						$this->set('marked_on', $markData->modified);
						if(!is_null($oldMarkData) && !$this->Marks->save($oldMarkData)) {
							$this->set('status', 'failed');
							$this->infolog("Mark Save failed (old mark). User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId);
							return false;
						}
						//If we are saving a failure, reopen the reports for this user's attempts
						if($type === 'save' && $markData->mark === 'Fail') {
							//Get the attempts for this user where the report has been submitted
							$attemptsQuery = $this->Marks->LtiResources->Attempts->find('all', [
								'conditions' => ['lti_resource_id' => $ltiResourceId, 'lti_user_id' => $user->id, 'report' => 1],
								'contain' => [
									'Reports' => function ($q) {
									   return $q
											->select(['id', 'type', 'attempt_id', 'revision', 'modified'])
											->where(['Reports.type' => 'submit', 'Reports.revision' => 0]);
									},
								],
							]);
							$attempts = $attemptsQuery->all();

							foreach($attempts as $attempt) {
								list($status, $logMessage) = $this->Marks->LtiResources->Attempts->Reports->reopen($attempt->id);
								$this->infolog($logMessage);
								if($status === 'failed') {
									$this->infolog("Mark Save failed (error reopening reports). User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId);
									return false;
								}
							}
						}
						
						$this->set('status', 'success');
						
						$this->infolog("Mark Save succeeded. User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId);
						
						//Send email to user notifiying that their attempt has been marked
						/*if($type === 'save') {
							$email = new Email('smtp');
							$email->from(['msdlt@medsci.ox.ac.uk' => 'Kenny Moore'])
								//->to('jon.mason@medsci.ox.ac.uk')	
								->to($user->lti_lis_person_contact_email_primary)
								->subject('Viral Outbreak iCase Report Marked')
								->emailFormat('html')
								->send('<div style="font-family: Verdana, Tahoma, sans-serif; font-size: 12px;"><p>Dear student,</p><p>Your Viral Outbreak iCase report has been marked. To view your mark, please <a href="https://weblearn.ox.ac.uk/access/basiclti/site/8dd25ab4-a0ca-4e16-0073-d2a9667b58ce/content:122">go to the iCase</a> (<a href="https://weblearn.ox.ac.uk/access/basiclti/site/8dd25ab4-a0ca-4e16-0073-d2a9667b58ce/content:122">https://weblearn.ox.ac.uk/access/basiclti/site/8dd25ab4-a0ca-4e16-0073-d2a9667b58ce/content:122</a>).</p><p>If you have not passed, you will need to read the marker\'s comments and deal with any issues raised, then resubmit your report.</p><p>If you have not yet looked at the extension material in the "Grant Funded Research" section of the iCase, we recommend that you do so.</p><p>If you have not given your feedback on this iCase, we would be very grateful if you could do so, here: <a href="https://learntech.imsu.ox.ac.uk/feedback/showsurvey.php?surveyInstanceID=501">https://learntech.imsu.ox.ac.uk/feedback/showsurvey.php?surveyInstanceID=501</a>.</p><p>Best wishes,</p><p>Kenny Moore</p></div>');
						}*/
					});
				}
				else {
					$this->set('status', 'locked');
					$this->infolog("Mark Save locked. User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId . "; Marker: " . $markerId . "; Locked By: " . $lastMark['marker_id']);
				}
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Mark Save denied. User: " . $user->id . "; Lti Resource ID: " . $ltiResourceId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Mark Save not POST ");
		}

		$this->set('_serialize', ['status', 'marker', 'marked_on']);
	}

	public function download() {
		$session = $this->request->session();	//Set Session to variable
		if($session->read('User.role') !== "Instructor") {
			$this->redirect(['controller' => 'attempts', 'action' => 'index']);
		}
		$ltiResourceId = $session->read('LtiResource.id');
		
		$users = $this->Marks->getUsersForMarking($ltiResourceId, $this->Auth->user('id'));
		$userStartedCount = count($users);
		$usersSubmittedCount = 0;
		$usersMarkedCount = 0;

		foreach($users as $user) {
			if($user['submissions'] > 0) {
				$usersSubmittedCount++;
			}
			if(!empty($user->marks) && $user->marks->mark) {
				$usersMarkedCount++;
			}
		}
		
		$this->set(compact('users', 'userStartedCount', 'usersSubmittedCount', 'usersMarkedCount'));
		$this->viewBuilder()->layout('ajax');
	}
}
