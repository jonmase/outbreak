<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * QuestionAnswers Controller
 *
 * @property \App\Model\Table\QuestionAnswersTable $QuestionAnswers
 */
class QuestionAnswersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Attempts', 'QuestionStems', 'QuestionOptions']
        ];
        $this->set('questionAnswers', $this->paginate($this->QuestionAnswers));
        $this->set('_serialize', ['questionAnswers']);
    }

    /**
     * View method
     *
     * @param string|null $id Question Answer id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $questionAnswer = $this->QuestionAnswers->get($id, [
            'contain' => ['Attempts', 'QuestionStems', 'QuestionOptions']
        ]);
        $this->set('questionAnswer', $questionAnswer);
        $this->set('_serialize', ['questionAnswer']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $questionAnswer = $this->QuestionAnswers->newEntity();
        if ($this->request->is('post')) {
            $questionAnswer = $this->QuestionAnswers->patchEntity($questionAnswer, $this->request->data);
            if ($this->QuestionAnswers->save($questionAnswer)) {
                $this->Flash->success(__('The question answer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question answer could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->QuestionAnswers->Attempts->find('list', ['limit' => 200]);
        $questionStems = $this->QuestionAnswers->QuestionStems->find('list', ['limit' => 200]);
        $questionOptions = $this->QuestionAnswers->QuestionOptions->find('list', ['limit' => 200]);
        $this->set(compact('questionAnswer', 'attempts', 'questionStems', 'questionOptions'));
        $this->set('_serialize', ['questionAnswer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Question Answer id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $questionAnswer = $this->QuestionAnswers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $questionAnswer = $this->QuestionAnswers->patchEntity($questionAnswer, $this->request->data);
            if ($this->QuestionAnswers->save($questionAnswer)) {
                $this->Flash->success(__('The question answer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question answer could not be saved. Please, try again.'));
            }
        }
        $attempts = $this->QuestionAnswers->Attempts->find('list', ['limit' => 200]);
        $questionStems = $this->QuestionAnswers->QuestionStems->find('list', ['limit' => 200]);
        $questionOptions = $this->QuestionAnswers->QuestionOptions->find('list', ['limit' => 200]);
        $this->set(compact('questionAnswer', 'attempts', 'questionStems', 'questionOptions'));
        $this->set('_serialize', ['questionAnswer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Question Answer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $questionAnswer = $this->QuestionAnswers->get($id);
        if ($this->QuestionAnswers->delete($questionAnswer)) {
            $this->Flash->success(__('The question answer has been deleted.'));
        } else {
            $this->Flash->error(__('The question answer could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
