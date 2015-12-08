<?php
namespace App\Model\Table;

use App\Model\Entity\SampleStage;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SampleStages Model
 *
 * @property \Cake\ORM\Association\HasMany $Samples
 */
class SampleStagesTable extends Table
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

        $this->table('sample_stages');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Assays', [
            'foreignKey' => 'sample_stage_id'
        ]);
        $this->hasMany('Samples', [
            'foreignKey' => 'sample_stage_id'
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
            ->requirePresence('stage', 'create')
            ->notEmpty('stage');

        $validator
            ->add('order', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('order');

        return $validator;
    }
}
