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
 * Questions Controller
 *
 * @property \App\Model\Table\QuestionsTable $Questions
 */
class QuestionsController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Questions->find('all', [
			'order' => ['Questions.order' => 'ASC'],
			'contain' => ['QuestionStems' => ['QuestionOptions'], 'QuestionOptions'],
		]);
		$rawQuestions = $query->all();
		$questions = [];
		foreach($rawQuestions as $question) {
			$questions[$question->id] = $question;
		}
		$status = 'success';
		$this->infolog("Questions Loaded");
		$this->set(compact('questions', 'status'));
		$this->set('_serialize', ['questions', 'status']);
	}
}
