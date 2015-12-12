<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Techniques Controller
 *
 * @property \App\Model\Table\TechniquesTable $Techniques
 */
class TechniquesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Techniques->find('all', [
			'order' => ['Techniques.order' => 'ASC'],
			'contain' => 'TechniqueResults',
		]);
		$rawTechniques = $query->all();
		$techniques = [];
		foreach($rawTechniques as $technique) {
			$techniques[$technique->id] = $technique;
		}
		$this->set(compact('techniques'));
		$this->set('_serialize', ['techniques']);
		//pr($techniques->toArray());
	}
}
