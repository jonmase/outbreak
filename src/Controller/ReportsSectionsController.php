<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReportsSections Controller
 *
 * @property \App\Model\Table\ReportsSectionsTable $ReportsSections
 */
class ReportsSectionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('reportsSections', $this->paginate($this->ReportsSections));
        $this->set('_serialize', ['reportsSections']);
    }

    /**
     * View method
     *
     * @param string|null $id Reports Section id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reportsSection = $this->ReportsSections->get($id, [
            'contain' => []
        ]);
        $this->set('reportsSection', $reportsSection);
        $this->set('_serialize', ['reportsSection']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reportsSection = $this->ReportsSections->newEntity();
        if ($this->request->is('post')) {
            $reportsSection = $this->ReportsSections->patchEntity($reportsSection, $this->request->data);
            if ($this->ReportsSections->save($reportsSection)) {
                $this->Flash->success(__('The reports section has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The reports section could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('reportsSection'));
        $this->set('_serialize', ['reportsSection']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reports Section id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reportsSection = $this->ReportsSections->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reportsSection = $this->ReportsSections->patchEntity($reportsSection, $this->request->data);
            if ($this->ReportsSections->save($reportsSection)) {
                $this->Flash->success(__('The reports section has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The reports section could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('reportsSection'));
        $this->set('_serialize', ['reportsSection']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Reports Section id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reportsSection = $this->ReportsSections->get($id);
        if ($this->ReportsSections->delete($reportsSection)) {
            $this->Flash->success(__('The reports section has been deleted.'));
        } else {
            $this->Flash->error(__('The reports section could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
