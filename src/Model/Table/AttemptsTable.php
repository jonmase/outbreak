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

use App\Model\Entity\Attempt;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attempts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtiUsers
 * @property \Cake\ORM\Association\HasMany $Assays
 * @property \Cake\ORM\Association\HasMany $Notes
 * @property \Cake\ORM\Association\HasMany $QuestionAnswers
 * @property \Cake\ORM\Association\HasMany $QuestionScores
 * @property \Cake\ORM\Association\HasMany $Reports
 * @property \Cake\ORM\Association\HasMany $StandardAssays
 * @property \Cake\ORM\Association\HasMany $TechniqueUsefulness
 * @property \Cake\ORM\Association\BelongsToMany $Schools
 */
class AttemptsTable extends Table
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

        $this->table('attempts');
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
        $this->hasMany('Assays', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('AttemptsSchools', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('Notes', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('QuestionAnswers', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('QuestionScores', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('Samples', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('StandardAssays', [
            'foreignKey' => 'attempt_id'
        ]);
        $this->hasMany('TechniqueUsefulness', [
            'foreignKey' => 'attempt_id'
        ]);
        /*$this->belongsToMany('Schools', [
            'foreignKey' => 'attempt_id',
            'targetForeignKey' => 'school_id',
            'joinTable' => 'attempts_schools'
        ]);*/
		
		$this->progressFields = array('start', 'alert', 'revision', 'questions', 'sampling', 'lab', 'hidentified', 'nidentified', 'report', 'research');
		$this->resourceFields = array('money', 'time');
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
            ->add('start', 'valid', ['rule' => 'boolean'])
            ->requirePresence('start', 'create')
            ->notEmpty('start');

        $validator
            ->add('alert', 'valid', ['rule' => 'boolean'])
            ->requirePresence('alert', 'create')
            ->notEmpty('alert');

        $validator
            ->add('revision', 'valid', ['rule' => 'boolean'])
            ->requirePresence('revision', 'create')
            ->notEmpty('revision');

        $validator
            ->add('questions', 'valid', ['rule' => 'boolean'])
            ->requirePresence('questions', 'create')
            ->notEmpty('questions');

        $validator
            ->add('sampling', 'valid', ['rule' => 'boolean'])
            ->requirePresence('sampling', 'create')
            ->notEmpty('sampling');

        $validator
            ->add('lab', 'valid', ['rule' => 'boolean'])
            ->requirePresence('lab', 'create')
            ->notEmpty('lab');

        $validator
            ->add('hidentified', 'valid', ['rule' => 'boolean'])
            ->requirePresence('hidentified', 'create')
            ->notEmpty('hidentified');

        $validator
            ->add('nidentified', 'valid', ['rule' => 'boolean'])
            ->requirePresence('nidentified', 'create')
            ->notEmpty('nidentified');

        $validator
            ->add('report', 'valid', ['rule' => 'boolean'])
            ->requirePresence('report', 'create')
            ->notEmpty('report');

        $validator
            ->add('research', 'valid', ['rule' => 'boolean'])
            ->requirePresence('research', 'create')
            ->notEmpty('research');

        $validator
            ->add('time', 'valid', ['rule' => 'numeric'])
            ->requirePresence('time', 'create')
            ->notEmpty('time');

        $validator
            ->add('money', 'valid', ['rule' => 'numeric'])
            ->requirePresence('money', 'create')
            ->notEmpty('money');

        $validator
            ->add('happiness', 'valid', ['rule' => 'numeric'])
            ->requirePresence('happiness', 'create')
            ->notEmpty('happiness');

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
        return $rules;
    }
	
	public function checkUserAttempt($userId, $attemptId, $token = null) {
		$attempt = $this->get($attemptId, ['fields' => ['id', 'lti_user_id', 'token']]);
		$userCheck = $attempt->lti_user_id === $userId;
		if($token) {
			$tokenCheck = $attempt->token === $token;
			return $userCheck && $tokenCheck;
		}
		else {
			return $userCheck;
		}
	}
}
