<?php
namespace App\Model\Table;

use App\Model\Entity\LtiContext;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LtiContexts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtiKeys
 * @property \Cake\ORM\Association\BelongsTo $LtiContexts
 * @property \Cake\ORM\Association\HasMany $LtiContexts
 */
class LtiContextsTable extends Table
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

        $this->table('lti_contexts');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LtiKeys', [
            'foreignKey' => 'lti_key_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('LtiContexts', [
            'foreignKey' => 'lti_context_id'
        ]);
        $this->hasMany('LtiContexts', [
            'foreignKey' => 'lti_context_id'
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
            ->allowEmpty('lti_context_label');

        $validator
            ->allowEmpty('lti_context_title');

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
        $rules->add($rules->existsIn(['lti_key_id'], 'LtiKeys'));
        $rules->add($rules->existsIn(['lti_context_id'], 'LtiContexts'));
        return $rules;
    }
}
