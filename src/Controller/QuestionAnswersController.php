<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * QuestionAnswers Controller
 *
 * @property \App\Model\Table\QuestionAnswersTable $QuestionAnswers
 */
class QuestionAnswersController extends AppController
{
	function load($attemptId = null) {
		if($attemptId && $this->QuestionAnswers->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
			$answersQuery = $this->QuestionAnswers->find('all', ['conditions' => ['attempt_id' => $attemptId], 'contain' => ['QuestionStems']]);
			$rawAnswers = $answersQuery->all()->toArray();
			$answers = [];
			foreach($rawAnswers as $answer) {
				if(empty($answers[$answer['question_stem']['question_id']])) {
					$answers[$answer['question_stem']['question_id']] = [];
				}
				$answers[$answer['question_stem']['question_id']][$answer['stem_id']] = $answer['question_option_id'];
			}
			$scores = $this->QuestionAnswers->Attempts->QuestionScores->find('list', ['conditions' => ['attempt_id' => $attemptId], 'keyField' => 'question_id', 'valueField' => 'score']);
			
			$responses = [
				'answers' => $answers,
				'scores' => $scores->toArray(),
			];
			
			$this->set(compact('responses'));
			$this->set('_serialize', ['responses']);
			//pr($responses);
		}
		else {
			pr('denied');
		}
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$questionId = $this->request->data['questionId'];
			$rawAnswers = $this->request->data['answers'];
			$score = $this->request->data['score'];
			
			if($attemptId && $questionId && !is_null($rawAnswers) && !is_null($score) && $this->QuestionAnswers->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId)) {
				$answers = [];
				foreach($rawAnswers as $stemId => $optionId) {
					$answer = [
						'attempt_id' => $attemptId,
						'stem_id' => $stemId,
						'question_option_id' => $optionId,
					];
					array_push($answers, $answer);
				}
				
				//Note: Always create new entry - should never already be saved entries
				$answersData = $this->QuestionAnswers->newEntities($answers);
				$scoreData = $this->QuestionAnswers->Attempts->QuestionScores->newEntity();
				
				$scoreData->attempt_id = $attemptId;
				$scoreData->question_id = $questionId;
				$scoreData->score = $score;
				
				//pr($answersData);
				//pr($scoreData);
				//exit;
				$connection = ConnectionManager::get('default');
				//$this->QuestionAnswers->connection()->transactional(function () use ($answers) {
				$connection->transactional(function () use ($answersData, $scoreData) {
					foreach ($answersData as $answer) {
						$this->QuestionAnswers->save($answer);
					}
					$this->QuestionAnswers->Attempts->QuestionScores->save($scoreData);
				});
				
				$this->set('message', 'success');
			}
			else {
				$this->set('message', 'Response save denied');
			}
		}
		else {
			$this->set('message', 'Response save not POST');
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
