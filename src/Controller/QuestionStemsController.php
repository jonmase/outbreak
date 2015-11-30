<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * QuestionStems Controller
 *
 * @property \App\Model\Table\QuestionStemsTable $QuestionStems
 */
class QuestionStemsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Questions', 'QuestionOptions']
        ];
        $this->set('questionStems', $this->paginate($this->QuestionStems));
        $this->set('_serialize', ['questionStems']);
    }

    /**
     * View method
     *
     * @param string|null $id Question Stem id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $questionStem = $this->QuestionStems->get($id, [
            'contain' => ['Questions', 'QuestionOptions']
        ]);
        $this->set('questionStem', $questionStem);
        $this->set('_serialize', ['questionStem']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $questionStem = $this->QuestionStems->newEntity();
        if ($this->request->is('post')) {
            $questionStem = $this->QuestionStems->patchEntity($questionStem, $this->request->data);
            if ($this->QuestionStems->save($questionStem)) {
                $this->Flash->success(__('The question stem has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question stem could not be saved. Please, try again.'));
            }
        }
        $questions = $this->QuestionStems->Questions->find('list', ['limit' => 200]);
        $questionOptions = $this->QuestionStems->QuestionOptions->find('list', ['limit' => 200]);
        $this->set(compact('questionStem', 'questions', 'questionOptions'));
        $this->set('_serialize', ['questionStem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Question Stem id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $questionStem = $this->QuestionStems->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $questionStem = $this->QuestionStems->patchEntity($questionStem, $this->request->data);
            if ($this->QuestionStems->save($questionStem)) {
                $this->Flash->success(__('The question stem has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The question stem could not be saved. Please, try again.'));
            }
        }
        $questions = $this->QuestionStems->Questions->find('list', ['limit' => 200]);
        $questionOptions = $this->QuestionStems->QuestionOptions->find('list', ['limit' => 200]);
        $this->set(compact('questionStem', 'questions', 'questionOptions'));
        $this->set('_serialize', ['questionStem']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Question Stem id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $questionStem = $this->QuestionStems->get($id);
        if ($this->QuestionStems->delete($questionStem)) {
            $this->Flash->success(__('The question stem has been deleted.'));
        } else {
            $this->Flash->error(__('The question stem could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
