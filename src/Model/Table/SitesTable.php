<?php
namespace App\Model\Table;

use App\Model\Entity\Site;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sites Model
 *
 * @property \Cake\ORM\Association\HasMany $Samples
 */
class SitesTable extends Table
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

        $this->table('sites');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Samples', [
            'foreignKey' => 'site_id'
        ]);
        $this->hasMany('Assays', [
            'foreignKey' => 'site_id'
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
            ->requirePresence('menu', 'create')
            ->notEmpty('menu');

        $validator
            ->allowEmpty('name');

        $validator
            ->requirePresence('resultId', 'create')
            ->notEmpty('resultId');

        $validator
            ->add('order', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('order');

        return $validator;
    }
}
