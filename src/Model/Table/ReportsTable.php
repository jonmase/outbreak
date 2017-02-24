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

use App\Model\Entity\Report;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reports Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Attempts
 * @property \Cake\ORM\Association\BelongsToMany $Sections
 */
class ReportsTable extends Table
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

        $this->table('reports');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Attempts', [
            'foreignKey' => 'attempt_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReportsSections', [
            'foreignKey' => 'report_id',
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
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->allowEmpty('serialised');

        $validator
            ->add('revision_parent', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('revision_parent');

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
        return $rules;
    }
	
	public function reopen($attemptId = null, $type = null) {
		$reportQuery = $this->find('all', [
			'conditions' => ['Reports.attempt_id' => $attemptId, 'Reports.type' => 'submit', 'Reports.revision' => 0],
			'order' => ['created' => 'DESC'],
		]);
		$lastSavedReport = $reportQuery->first();
		if($reportQuery->isEmpty()) {
			$status = "Report not submitted, so can't reopen";
			$logMessage = "Report Reopen rejected - report not submitted. Attempt: " . $attemptId;
		}
		else {
			$reportData = $lastSavedReport;
			
			$reportData->attempt_id = $attemptId;
			$reportData->revision = false;
            
            if($type) {
                $reportData->type = $type;
            }
            else {
                $reportData->type = 'reopen';
            }
			
			if(!$this->save($reportData)) {
				$status = 'failed';
				$logMessage = "Report Reopen failed. Attempt: " . $attemptId;
				return false;
			}
			$status = 'success';
			$logMessage = "Report Reopen succeeded. Attempt: " . $attemptId;
		}
		return array($status, $logMessage);
	}
}
