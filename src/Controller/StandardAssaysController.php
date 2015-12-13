<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StandardAssays Controller
 *
 * @property \App\Model\Table\StandardAssaysTable $StandardAssays
 */
class StandardAssaysController extends AppController
{
	public function load($attemptId = null) {
		if($attemptId && $this->StandardAssays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$assaysQuery = $this->StandardAssays->find('all', [
				'conditions' => ['attempt_id' => $attemptId],
			]);
			$assaysRaw = $assaysQuery->all();
			$standardAssays = [];
			foreach($assaysRaw as $assay) {
				//if(!isset($assays[$assay->technique_id])) { $assays[$assay->technique_id] = []; }
				//if(!isset($assays[$assay->technique_id][$assay->site_id])) { $assays[$assay->technique_id][$assay->site_id] = []; }
				//if(!isset($assays[$assay->technique_id][$assay->site_id][$assay->school_id])) { $assays[$assay->technique_id][$assay->site_id][$assay->school_id] = []; }
				//if(!isset($assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id])) { $assays[$assay->technique_id][$assay->site_id][$assay->school_id][$assay->child_id] = []; }
				//if(!isset($assays[$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id])) { $assays[$assay->site_id][$assay->school_id][$assay->child_id][$assay->sample_stage_id] = []; }

				$standardAssays[$assay->technique_id][$assay->standard_id] = 1;
			}
			$status = 'success';
			$this->log("Standard Assays Loaded. Attempt: " . $attemptId, 'info');
		}
		else {
			$status = 'denied';
			$this->log("Standard Assays Load denied. Attempt: " . $attemptId, 'info');
		}
		$this->set(compact('standardAssays', 'status'));
		$this->set('_serialize', ['standardAssays', 'status']);
	}
}
