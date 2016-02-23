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
