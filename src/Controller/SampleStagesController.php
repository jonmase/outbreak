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
use Cake\Event\Event;

/**
 * SamplesStages Controller
 *
 * @property \App\Model\Table\SamplesStagesTable $SamplesStages
 */
class SampleStagesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->SampleStages->find('all', [
			'order' => ['SampleStages.order' => 'ASC'],
		]);
		$rawStages = $query->all();
		$stages = [];
		foreach($rawStages as $stage) {
			$stages[$stage->id] = $stage;
		}
		$status = 'success';
		$this->infolog("Samples Stages Loaded");
		$this->set(compact('stages', 'status'));
		$this->set('_serialize', ['stages', 'status']);
	}
}
