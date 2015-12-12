<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Standards Controller
 *
 * @property \App\Model\Table\StandardsTable $Standards
 */
class StandardsController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Standards->find('all', [
			'order' => ['Standards.order' => 'ASC'],
			//'contain' => ['QuestionStems' => ['QuestionOptions'], 'QuestionOptions'],
		]);
		$rawStandards = $query->all();
		$standards = [];
		foreach($rawStandards as $standard) {
			$standards[$standard->id] = $standard;
		}
		$this->set(compact('standards'));
		$this->set('_serialize', ['standards']);
		//pr($questions->toArray());
	}
}
