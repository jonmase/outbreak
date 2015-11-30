<?php
namespace App\Model\Table;

use App\Model\Entity\AttemptsSchool;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AttemptsSchools Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Attempts
 * @property \Cake\ORM\Association\BelongsTo $Schools
 */
class AttemptsSchoolsTable extends Table
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

        $this->table('attempts_schools');
        $this->displayField('attempt_id');
        $this->primaryKey(['attempt_id', 'school_id']);

        $this->belongsTo('Attempts', [
            'foreignKey' => 'attempt_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER'
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
            ->add('acuteDisabled', 'valid', ['rule' => 'boolean'])
            ->requirePresence('acuteDisabled', 'create')
            ->notEmpty('acuteDisabled');

        $validator
            ->add('convalescentDisabled', 'valid', ['rule' => 'boolean'])
            ->requirePresence('convalescentDisabled', 'create')
            ->notEmpty('convalescentDisabled');

        $validator
            ->add('returnTripOk', 'valid', ['rule' => 'boolean'])
            ->requirePresence('returnTripOk', 'create')
            ->notEmpty('returnTripOk');

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
        $rules->add($rules->existsIn(['attempt_id'], 'Attempts'));
        $rules->add($rules->existsIn(['school_id'], 'Schools'));
        return $rules;
    }
}
