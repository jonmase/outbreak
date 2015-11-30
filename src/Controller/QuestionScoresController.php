<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * QuestionScores Controller
 *
 * @property \App\Model\Table\QuestionScoresTable $QuestionScores
 */
class QuestionScoresController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'Questions']
        ];
        $this->set('questionScores', $this->paginate($this->QuestionScores));
        $this->set('_serialize', ['questionScores']);
    }

    /**
     * View method
     *
     * @param string|null $id Question Score id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $questionScore = $this->QuestionScores->get($id, [
            'contain' => ['Attempts', 'Questions']
        ]);
        $this->set('questionScore', $questionScore);
        $this->set('_serialize', ['questionScore']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $questionScore = $this->QuestionScores->newEntity();
        if ($this->request->is('post')) {
            $questionScore = $this->QuestionScores->patchEntity($questionScore, $this->request->data);
            if ($this->QuestionScores->save($questionScore)) {
                $this->Flash->success(__('The question score has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question score could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->QuestionScores->Attempts->find('list', ['limit' => 200]);
        $questions = $this->QuestionScores->Questions->find('list', ['limit' => 200]);
        $this->set(compact('questionScore', 'attempts', 'questions'));
        $this->set('_serialize', ['questionScore']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Question Score id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $questionScore = $this->QuestionScores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $questionScore = $this->QuestionScores->patchEntity($questionScore, $this->request->data);
            if ($this->QuestionScores->save($questionScore)) {
                $this->Flash->success(__('The question score has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question score could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->QuestionScores->Attempts->find('list', ['limit' => 200]);
        $questions = $this->QuestionScores->Questions->find('list', ['limit' => 200]);
        $this->set(compact('questionScore', 'attempts', 'questions'));
        $this->set('_serialize', ['questionScore']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Question Score id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $questionScore = $this->QuestionScores->get($id);
        if ($this->QuestionScores->delete($questionScore)) {
            $this->Flash->success(__('The question score has been deleted.'));
        } else {
            $this->Flash->error(__('The question score could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
