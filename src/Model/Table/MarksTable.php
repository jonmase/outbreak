<?php
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
	
	public function getUsers($ltiResourceId) {
		$attemptsQuery = $this->LtiResources->Attempts->find('all', [
			'conditions' => ['lti_resource_id' => $ltiResourceId],
			'order' => ['LtiUsers.lti_displayid' => 'ASC'],
			'contain' => [
				'LtiUsers', 
				'Samples',
				'Assays',
				'StandardAssays',
				'Reports' => function ($q) {
				   return $q
						->select(['id', 'type', 'attempt_id', 'modified'])
						->where(['Reports.type' => 'submit', 'Reports.revision' => 0])
						->contain(['ReportsSections' => ['Sections']])
						->order(['Reports.modified' => 'DESC']);
				},
			],
		]);
		$attempts = $attemptsQuery->all();
		//pr($attempts->toArray()); exit;
		
		$techniquesQuery = $this->LtiResources->Attempts->Assays->Techniques->find('all', [
			'conditions' => ['lab' => 1],
			'fields' => ['id', 'code', 'time', 'money'],
		]);
		$techniquesRaw = $techniquesQuery->toArray();
		$techniques = [];
		foreach($techniquesRaw as $technique) {
			$techniques[$technique['id']] = $technique;
		}
		
		$users = [];
		//$userIdsInUsersArray = [];
		foreach($attempts as $attempt) {
			$userId = $attempt['lti_user_id'];
			
			//Create an array for this user, if there isn't one already
			if(!isset($users[$userId])) {
				//$index = count($users);
				//$userIdsInUsersArray[$userId] = $index;
				$users[$userId] = $attempt['lti_user'];
				$users[$userId]['attempts'] = [];	//Create array for attempts
				$users[$userId]['attempts_count'] = 0;
				$users[$userId]['submissions'] = 0;
				$users[$userId]['last_submit'] = null;
				$users[$userId]['most_recent_role'] = $attempt['user_role']==="Instructor"?"Demonstrator":"Student";	//Get the user's most recent role
			}
			//else {
			//	$index = $userIdsInUsersArray[$userId];
			//}
			unset($attempt['lti_user']);	//Delete the user details from the attempt
			
			//Process basic user, attempt and submission info/counts
			$users[$userId]['attempts'][] = $attempt;	//Add the attempt to the attempts array for the user
			$users[$userId]['attempts_count']++; //Count the attempt
			if(!empty($attempt['reports'])) {
				$users[$userId]['submissions']++;
				if(!$users[$userId]['last_submit'] || $attempt['reports'][0]['modified'] > $users[$userId]['last_submit']) {
					$users[$userId]['last_submit'] = $attempt['reports'][0]['modified'];
				}
				$attempt['hidden'] = false;
			}
			else {
				$attempt['hidden'] = true;
			}
			$attempt['reportHidden'] = false;
			
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
			$attempt['timeSpent'] = 0;
			$attempt['moneySpent'] = 0;
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
				$attempt['moneySpent'] += $techniques[$assay->technique_id]['money'];
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
				$attempt['moneySpent'] += $techniques[$standardAssay->technique_id]['money'];
			}
			unset($attempt['standard_assays']);
			$attempt['assays'] = $assays;
			$attempt['assayCounts'] = $assayCounts;
			$attempt['standardAssays'] = $standardAssays;
			$attempt['standardAssayCounts'] = $standardAssayCounts;
			$attempt['assaysHidden'] = false;
			
			$attempt['timeSpent'] = 48 - $attempt['time'];
		}
		//pr($users); exit;
		
		//Get all the marks
		$marksQuery = $this->find('all', [
			'conditions' => ['lti_resource_id' => $ltiResourceId, 'revision' => 0],
			'order' => ['Marks.created' => 'DESC'],
			'contain' => ['Marker', 'Locker'],
		]);
		$marks = $marksQuery->all();
		//pr($ltiResourceId);
		//pr($marks->toArray());
		
		foreach($marks as $mark) {
			//$user['marks'] = ['mark' => null];
			//pr($mark);
			//Should never have more than one result for a particular user, but just check that we haven't already got this user
			$userId = $mark['lti_user_id'];
			if(empty($users[$userId]['marks'])) {
				//If user is locked but it is either too long ago or by this user, then unlock them
				if($mark->locked && (!$mark->locked->wasWithinLast('1 hour') || $mark->locker_id === $this->Auth->user('id'))) {
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
}
