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
}
