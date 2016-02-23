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

use App\Model\Entity\School;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Schools Model
 *
 * @property \Cake\ORM\Association\HasMany $Children
 * @property \Cake\ORM\Association\HasMany $Samples
 * @property \Cake\ORM\Association\BelongsToMany $Attempts
 */
class SchoolsTable extends Table
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

        $this->table('schools');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Assays', [
            'foreignKey' => 'school_id'
        ]);
        $this->hasMany('AttemptsSchools', [
            'foreignKey' => 'school_id'
        ]);
        $this->hasMany('Children', [
            'foreignKey' => 'school_id',
 			'sort' => ['Children.order' => 'ASC'],
        ]);
        $this->hasMany('Samples', [
            'foreignKey' => 'school_id'
        ]);
        /*$this->belongsToMany('Attempts', [
            'foreignKey' => 'school_id',
            'targetForeignKey' => 'attempt_id',
            'joinTable' => 'attempts_schools'
        ]);*/
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
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('details');

        $validator
            ->add('acute', 'valid', ['rule' => 'boolean'])
            ->requirePresence('acute', 'create')
            ->notEmpty('acute');

        $validator
            ->add('convalescent', 'valid', ['rule' => 'boolean'])
            ->requirePresence('convalescent', 'create')
            ->notEmpty('convalescent');

        $validator
            ->add('order', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('order');

        return $validator;
    }
}
