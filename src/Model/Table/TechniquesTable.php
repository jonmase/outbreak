<?php
namespace App\Model\Table;

use App\Model\Entity\Technique;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Techniques Model
 *
 * @property \Cake\ORM\Association\HasMany $Assays
 * @property \Cake\ORM\Association\HasMany $Notes
 * @property \Cake\ORM\Association\HasMany $StandardAssays
 * @property \Cake\ORM\Association\HasMany $TechniqueResults
 * @property \Cake\ORM\Association\HasMany $TechniqueUsefulness
 */
class TechniquesTable extends Table
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

        $this->table('techniques');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Assays', [
            'foreignKey' => 'technique_id'
        ]);
        $this->hasMany('Notes', [
            'foreignKey' => 'technique_id'
        ]);
        $this->hasMany('StandardAssays', [
            'foreignKey' => 'technique_id'
        ]);
        $this->hasMany('TechniqueResults', [
            'foreignKey' => 'technique_id'
        ]);
        $this->hasMany('TechniqueUsefulness', [
            'foreignKey' => 'technique_id'
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
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->allowEmpty('menu');

        $validator
            ->allowEmpty('name');

        $validator
            ->allowEmpty('video');

        $validator
            ->allowEmpty('content');

        $validator
            ->add('revision_only', 'valid', ['rule' => 'boolean'])
            ->requirePresence('revision_only', 'create')
            ->notEmpty('revision_only');

        $validator
            ->add('lab_only', 'valid', ['rule' => 'boolean'])
            ->requirePresence('lab_only', 'create')
            ->notEmpty('lab_only');

        $validator
            ->add('order', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('order');

        $validator
            ->add('time', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('time');

        $validator
            ->add('money', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('money');

        return $validator;
    }
}
