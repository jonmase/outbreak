<?php
namespace App\Model\Table;

use App\Model\Entity\Sample;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Samples Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Attempts
 * @property \Cake\ORM\Association\BelongsTo $Sites
 * @property \Cake\ORM\Association\BelongsTo $Schools
 * @property \Cake\ORM\Association\BelongsTo $Children
 * @property \Cake\ORM\Association\BelongsTo $SampleStages
 * @property \Cake\ORM\Association\HasMany $Assays
 */
class SamplesTable extends Table
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

        $this->table('samples');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Attempts', [
            'foreignKey' => 'attempt_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Sites', [
            'foreignKey' => 'site_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Children', [
            'foreignKey' => 'child_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SampleStages', [
            'foreignKey' => 'sample_stage_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Assays', [
            'foreignKey' => 'sample_id'
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
            ->add('before_submit', 'valid', ['rule' => 'boolean'])
            ->requirePresence('before_submit', 'create')
            ->notEmpty('before_submit');

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
        $rules->add($rules->existsIn(['site_id'], 'Sites'));
        $rules->add($rules->existsIn(['school_id'], 'Schools'));
        $rules->add($rules->existsIn(['child_id'], 'Children'));
        $rules->add($rules->existsIn(['sample_stage_id'], 'SampleStages'));
        return $rules;
    }
}
