<?php
namespace App\Controller;

use App\Controller\AppController;

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
					'LtiUsers' => ['Marks' => ['MarksGiven']], 
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
		$this->set(compact('ltiResourceId'));
		$this->viewBuilder()->layout('angular');
	}

    /**
     * View method
     *
     * @param string|null $id Mark id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
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
    public function add()
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
    public function edit($id = null)
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
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mark = $this->Marks->get($id);
        if ($this->Marks->delete($mark)) {
            $this->Flash->success(__('The mark has been deleted.'));
        } else {
            $this->Flash->error(__('The mark could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
