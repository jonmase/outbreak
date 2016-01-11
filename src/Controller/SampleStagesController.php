<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * SamplesStages Controller
 *
 * @property \App\Model\Table\SamplesStagesTable $SamplesStages
 */
class SampleStagesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->SampleStages->find('all', [
			'order' => ['SampleStages.order' => 'ASC'],
		]);
		$rawStages = $query->all();
		$stages = [];
		foreach($rawStages as $stage) {
			$stages[$stage->id] = $stage;
		}
		$status = 'success';
		$this->infolog("Samples Stages Loaded");
		$this->set(compact('stages', 'status'));
		$this->set('_serialize', ['stages', 'status']);
	}
}
