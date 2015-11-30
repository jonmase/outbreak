<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * QuestionOptions Controller
 *
 * @property \App\Model\Table\QuestionOptionsTable $QuestionOptions
 */
class QuestionOptionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Questions']
        ];
        $this->set('questionOptions', $this->paginate($this->QuestionOptions));
        $this->set('_serialize', ['questionOptions']);
    }

    /**
     * View method
     *
     * @param string|null $id Question Option id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $questionOption = $this->QuestionOptions->get($id, [
            'contain' => ['Questions', 'QuestionAnswers', 'QuestionStems']
        ]);
        $this->set('questionOption', $questionOption);
        $this->set('_serialize', ['questionOption']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $questionOption = $this->QuestionOptions->newEntity();
        if ($this->request->is('post')) {
            $questionOption = $this->QuestionOptions->patchEntity($questionOption, $this->request->data);
            if ($this->QuestionOptions->save($questionOption)) {
                $this->Flash->success(__('The question option has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question option could not be saved. Please, try again.'));
            }
        }
        $questions = $this->QuestionOptions->Questions->find('list', ['limit' => 200]);
        $this->set(compact('questionOption', 'questions'));
        $this->set('_serialize', ['questionOption']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Question Option id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $questionOption = $this->QuestionOptions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $questionOption = $this->QuestionOptions->patchEntity($questionOption, $this->request->data);
            if ($this->QuestionOptions->save($questionOption)) {
                $this->Flash->success(__('The question option has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question option could not be saved. Please, try again.'));
            }
        }
        $questions = $this->QuestionOptions->Questions->find('list', ['limit' => 200]);
        $this->set(compact('questionOption', 'questions'));
        $this->set('_serialize', ['questionOption']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Question Option id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $questionOption = $this->QuestionOptions->get($id);
        if ($this->QuestionOptions->delete($questionOption)) {
            $this->Flash->success(__('The question option has been deleted.'));
        } else {
            $this->Flash->error(__('The question option could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
