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

use App\Model\Entity\LtiResource;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LtiResources Model
 *
 * @property \Cake\ORM\Association\BelongsTo $LtiKeys
 * @property \Cake\ORM\Association\BelongsTo $LtiResourceLinks
 */
class LtiResourcesTable extends Table
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

        $this->table('lti_resources');
        $this->displayField('lti_resource_link_title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('LtiContexts', [
            'foreignKey' => 'lti_context_id',
            'joinType' => 'INNER'
        ]);
         $this->belongsTo('LtiKeys', [
            'foreignKey' => 'lti_key_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Attempts', [
            'foreignKey' => 'lti_resource_id'
        ]);
        $this->hasMany('Marks', [
            'foreignKey' => 'lti_resource_id'
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
            ->add('active', 'valid', ['rule' => 'boolean'])
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->allowEmpty('lti_resource_link_title');

        $validator
            ->allowEmpty('lti_resource_link_description');

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
        $rules->add($rules->existsIn(['lti_context_id'], 'LtiContexts'));
        $rules->add($rules->existsIn(['lti_key_id'], 'LtiKeys'));
        return $rules;
    }
}
