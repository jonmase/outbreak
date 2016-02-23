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
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 */
class StandardsController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Standards->find('all', [
			'order' => ['Standards.order' => 'ASC'],
			//'contain' => ['QuestionStems' => ['QuestionOptions'], 'QuestionOptions'],
		]);
		$rawStandards = $query->all();
		$standards = [];
		foreach($rawStandards as $standard) {
			$standards[$standard->id] = $standard;
		}
		$status = 'success';
		$this->infolog("Standards Loaded");
		$this->set(compact('standards', 'status'));
		$this->set('_serialize', ['standards', 'status']);
	}
}
