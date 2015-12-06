<?php
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
