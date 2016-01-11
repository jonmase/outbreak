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
	function load($attemptId = null, $token = null) {
		if($attemptId && $token && $this->QuestionAnswers->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
			
			$status = 'success';
			$this->infolog("Responses Loaded. Attempt: " . $attemptId);
		}
		else {
			$status = 'denied';
			$this->infolog("Responses Load denied. Attempt: " . $attemptId);
		}
		$this->set(compact('responses', 'status'));
		$this->set('_serialize', ['responses', 'status']);
	}
	
	public function save() {
		if($this->request->is('post')) {
			//pr($this->request->data);
			$attemptId = $this->request->data['attemptId'];
			$token = $this->request->data['token'];
			$questionId = $this->request->data['questionId'];
			$rawAnswers = $this->request->data['answers'];
			$score = $this->request->data['score'];
			$this->infolog("Response Save attempted. Attempt: " . $attemptId . "; Question: " . $questionId . "; Score: " . $score . "; Answers: " . serialize($rawAnswers));
			
			if($attemptId && $token && $questionId && !is_null($rawAnswers) && !is_null($score) && $this->QuestionAnswers->Attempts->checkUserAttempt($this->Auth->user('id'), $attemptId, $token)) {
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
				$connection->transactional(function () use ($answersData, $scoreData, $attemptId, $questionId) {
					foreach ($answersData as $answer) {
						if(!$this->QuestionAnswers->save($answer)) {
							$this->set('status', 'failed');
							$this->infolog("Response Save failed. Attempt: " . $attemptId . "; Question: " . $questionId);
							return false;
						}
					}
					if(!$this->QuestionAnswers->Attempts->QuestionScores->save($scoreData)) {
						$this->set('status', 'failed');
						$this->infolog("Response Save failed. Attempt: " . $attemptId . "; Question: " . $questionId);
						return false;
					}
					$this->set('status', 'success');
					$this->infolog("Response Save succeeded. Attempt: " . $attemptId . "; Question: " . $questionId);
				});
				
			}
			else {
				$this->set('status', 'denied');
				$this->infolog("Response Save denied. Attempt: " . $attemptId . "; Question: " . $questionId);
			}
		}
		else {
			$this->set('status', 'notpost');
			$this->infolog("Response Save not POST ");
		}
		$this->viewBuilder()->layout('ajax');
		$this->render('/Element/ajaxmessage');
	}
}
