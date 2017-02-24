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

namespace App\Model\Table;

use App\Model\Entity\Mark;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Marks Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtiResources
 * @property \Cake\ORM\Association\BelongsTo $LtiUsers
 * @property \Cake\ORM\Association\BelongsTo $LtiUsers
 */
class MarksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('marks');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LtiResources', [
            'foreignKey' => 'lti_resource_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('LtiUsers', [
            'foreignKey' => 'lti_user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Marker', [
			'className' => 'LtiUsers',
            'foreignKey' => 'marker_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Locker', [
			'className' => 'LtiUsers',
            'foreignKey' => 'locker_id',
            'joinType' => 'LEFT'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('mark');

        $validator
            ->allowEmpty('mark_comment');

        $validator
            ->add('reivison', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('reivison');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['lti_resource_id'], 'LtiResources'));
        $rules->add($rules->existsIn(['lti_user_id'], 'LtiUsers'));
        $rules->add($rules->existsIn(['marker_id'], 'LtiUsers'));
        $rules->add($rules->existsIn(['locker_id'], 'LtiUsers'));
        return $rules;
    }
	
	public function getUsersForMarking($ltiResourceId, $loggedInUserId) {
		$attemptsQuery = $this->LtiResources->Attempts->find('all', [
			'conditions' => ['lti_resource_id' => $ltiResourceId, 'archived' => 0],
			'order' => ['LtiUsers.lti_displayid' => 'ASC'],
			'contain' => [
				'LtiUsers', 
				//'Samples',
				//'Assays',
				//'StandardAssays',
				'Reports' => function ($q) {
				   return $q
						->select(['id', 'type', 'attempt_id', 'modified', 'revision'])
						->where(['OR' => [['Reports.type' => 'submit'], ['Reports.type' => 'fail']], 'Reports.revision' => 0])
						//->contain(['ReportsSections' => ['Sections']])
						->order(['Reports.modified' => 'DESC']);
				},
			],
		]);
		$attempts = $attemptsQuery->all();
		
		$techniquesQuery = $this->LtiResources->Attempts->Assays->Techniques->find('all', [
			'conditions' => ['lab' => 1],
			'fields' => ['id', 'code', 'time', 'money'],
		]);
		$techniquesRaw = $techniquesQuery->toArray();
		$techniques = [];
		foreach($techniquesRaw as $technique) {
			$techniques[$technique['id']] = $technique;
		}
		
        //pr($attempts);
		$users = [];
		foreach($attempts as $attempt) {
			$userId = $attempt['lti_user_id'];
			
			//Create an array for this user, if there isn't one already
			if(!isset($users[$userId])) {
				$users[$userId] = $attempt['lti_user'];
				$users[$userId]['attempts'] = [];	//Create array for attempts
				$users[$userId]['attempts_count'] = 0;
				$users[$userId]['submissions'] = 0;
				$users[$userId]['last_submit'] = null;
				$users[$userId]['most_recent_role'] = $attempt['user_role']==="Instructor"?"Demonstrator":"Student";	//Get the user's most recent role
			}
			unset($attempt['lti_user']);	//Delete the user details from the attempt
			
			//Process basic user, attempt and submission info/counts
			$users[$userId]['attempts'][] = $attempt;	//Add the attempt to the attempts array for the user
			$users[$userId]['attempts_count']++; //Count the attempt
			if(!empty($attempt['reports'])) {
				$users[$userId]['submissions']++;
				if(!$users[$userId]['last_submit'] || $attempt['reports'][0]['modified'] > $users[$userId]['last_submit']) {
					$users[$userId]['last_submit'] = $attempt['reports'][0]['modified'];
				}
			}
		}
        //pr($users);
		
		//Get all the marks
		$marksQuery = $this->find('all', [
			'conditions' => ['lti_resource_id' => $ltiResourceId, 'revision' => 0, 'archived' => 0],
			'order' => ['Marks.created' => 'DESC'],
			'contain' => ['Marker', 'Locker'],
		]);
		$marks = $marksQuery->all();
		
		foreach($marks as $mark) {
			//Should never have more than one result for a particular user, but just check that we haven't already got this user
			$userId = $mark['lti_user_id'];
			if(!empty($users[$userId]) && empty($users[$userId]['marks'])) {
				//If user is locked but it is either too long ago or by this user, then unlock them
				if($mark->locked && (!$mark->locked->wasWithinLast('1 hour') || $mark->locker_id === $loggedInUserId)) {
					$mark->locked = null;
					$mark->locker_id = null;
					$mark->locker = null;
				}
				
				//If user has been marked, set the 'marked' property to true
				$users[$userId]['marks'] = $mark;
				if($mark->mark) {
					$users[$userId]['marked'] = true;
					
					//If user failed, check whether they have resubmitted since being failed
					$users[$userId]['resubmitted'] = false;
					if($mark->mark === 'Fail') {
						foreach($users[$userId]['attempts'] as $attempt) {
							//pr($attempt);
							if(!empty($attempt['reports'])) {
								if($attempt['reports'][0]['modified'] > $mark->created) {
									$users[$userId]['resubmitted'] = true;
								}
							}
						}
					}
				}
				else {
					$users[$userId]['marked'] = false;
				}
				$users[$userId]['editing'] = false;	//Set 'editing' property to false for all users, as they cannot be being edited when the data is loaded
				
			}
		}
		return $users;
	}
	
	public function getUserAttempts($ltiResourceId, $userId) {
		$attemptsQuery = $this->LtiResources->Attempts->find('all', [
			'conditions' => ['lti_resource_id' => $ltiResourceId, 'lti_user_id' => $userId, 'archived' => 0],
			//'order' => ['LtiUsers.lti_displayid' => 'ASC'],
			'contain' => [
				'Samples',
				'Assays',
				'StandardAssays',
				'Reports' => function ($q) {
				   return $q
						->select(['id', 'type', 'attempt_id', 'modified', 'revision'])
						->where(['OR' => [['Reports.type' => 'submit'], ['Reports.type' => 'fail']], 'Reports.revision' => 0])
						->contain(['ReportsSections' => ['Sections']])
						->order(['Reports.modified' => 'DESC']);
				},
			],
		]);
		$attempts = $attemptsQuery->all()->toArray();
		
		$techniquesQuery = $this->LtiResources->Attempts->Assays->Techniques->find('all', [
			'conditions' => ['lab' => 1],
			'fields' => ['id', 'code', 'time', 'money'],
		]);
		$techniquesRaw = $techniquesQuery->toArray();
		$techniques = [];
		foreach($techniquesRaw as $technique) {
			$techniques[$technique['id']] = $technique;
		}
		
		foreach($attempts as $attempt) {
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
			$attempt['samplesHidden'] = false;
			
			//Process assays
			$assays = [];
			$assayCounts = [
				'total' => 0,
			];
			//$attempt['timeSpent'] = 0;
			//$attempt['moneySpent'] = 0;
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
				//$attempt['timeSpent'] += $techniques[$assay->technique_id]['time'];
				//$attempt['moneySpent'] += $techniques[$assay->technique_id]['money'];
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
				//$attempt['timeSpent'] += $techniques[$standardAssay->technique_id]['time'];
				//$attempt['moneySpent'] += $techniques[$standardAssay->technique_id]['money'];
			}
			unset($attempt['standard_assays']);
			$attempt['assays'] = $assays;
			$attempt['assayCounts'] = $assayCounts;
			$attempt['standardAssays'] = $standardAssays;
			$attempt['standardAssayCounts'] = $standardAssayCounts;
			$attempt['assaysHidden'] = false;
			
			//$attempt['timeSpent'] = 48 - $attempt['time'];
			
			//Work out whether the attempt should be shown or not
			if(!empty($attempt['reports'])) {
				$attempt['hidden'] = false;
			}
			else {
				$attempt['hidden'] = true;
			}
			//Alsways show reports by default
			$attempt['reportHidden'] = false;
		}
		
		return $attempts;
	}

}
