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
 * Techniques Controller
 *
 * @property \App\Model\Table\TechniquesTable $Techniques
 */
class TechniquesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Techniques->find('all', [
			'order' => ['Techniques.order' => 'ASC'],
			'contain' => 'TechniqueResults',
		]);
		$rawTechniques = $query->all();
		$techniques = [];
		foreach($rawTechniques as $technique) {
			$techniques[$technique->id] = $technique;
		}
		$status = 'success';
		$this->infolog("Techniques Loaded");
		$this->set(compact('techniques', 'status'));
		$this->set('_serialize', ['techniques', 'status']);
	}
}
