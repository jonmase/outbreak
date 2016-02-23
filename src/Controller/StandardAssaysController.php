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

namespace App\Controller;

use App\Controller\AppController;

/**
 * StandardAssays Controller
 *
 * @property \App\Model\Table\StandardAssaysTable $StandardAssays
 */
class StandardAssaysController extends AppController
{
	public function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->StandardAssays->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
			$assaysQuery = $this->StandardAssays->find('all', [
				'conditions' => ['attempt_id' => $attemptId],
			]);
			$assaysRaw = $assaysQuery->all();
			$standardAssays = [];
			foreach($assaysRaw as $assay) {
				$standardAssays[$assay->technique_id][$assay->standard_id] = 1;
			}
			$status = 'success';
			$this->infolog("Standard Assays Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Standard Assays Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('standardAssays', 'status'));
		$this->set('_serialize', ['standardAssays', 'status']);
	}
}
