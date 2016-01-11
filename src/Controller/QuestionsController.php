<?php
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
