<?php
namespace App\Model\Table;

use App\Model\Entity\LtiUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LtiUsers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtiKeys
 * @property \Cake\ORM\Association\BelongsTo $LtiUsers
 * @property \Cake\ORM\Association\HasMany $Attempts
 * @property \Cake\ORM\Association\HasMany $LtiUsers
 */
class LtiUsersTable extends Table
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

        $this->table('lti_users');
        $this->displayField('lti_displayid');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LtiKeys', [
            'foreignKey' => 'lti_key_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Attempts', [
            'foreignKey' => 'lti_user_id'
        ]);
        $this->hasMany('Marks', [
            'foreignKey' => 'lti_user_id'
        ]);
        $this->hasMany('Marker', [
			'className' => 'LtiUsers',
            'foreignKey' => 'marker_id'
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
            ->allowEmpty('lti_eid');

        $validator
            ->allowEmpty('lti_displayid');

        $validator
            ->allowEmpty('lti_roles');

        $validator
            ->allowEmpty('lti_sakai_role');

        $validator
            ->allowEmpty('lti_lis_person_contact_email_primary');

        $validator
            ->allowEmpty('lti_lis_person_name_family');

        $validator
            ->allowEmpty('lti_lis_person_name_full');

        $validator
            ->allowEmpty('lti_lis_person_name_given');

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
        return $rules;
    }
}
