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

use App\Model\Entity\LtiKey;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LtiKeys Model
 *
 * @property \Cake\ORM\Association\HasMany $LtiContexts
 * @property \Cake\ORM\Association\HasMany $LtiResources
 * @property \Cake\ORM\Association\HasMany $LtiUsers
 */
class LtiKeysTable extends Table
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

        $this->table('lti_keys');
        $this->displayField('oauth_consumer_key');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('LtiContexts', [
            'foreignKey' => 'lti_key_id'
        ]);
        $this->hasMany('LtiResources', [
            'foreignKey' => 'lti_key_id'
        ]);
        $this->hasMany('LtiUsers', [
            'foreignKey' => 'lti_key_id'
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
            ->requirePresence('oauth_consumer_key', 'create')
            ->notEmpty('oauth_consumer_key');

        $validator
            ->requirePresence('secret', 'create')
            ->notEmpty('secret');

        $validator
            ->allowEmpty('description');

        return $validator;
    }
}
