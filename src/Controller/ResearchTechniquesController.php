<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * ResearchTechniques Controller
 *
 * @property \App\Model\Table\ResearchTechniquesTable $ResearchTechniques
 */
class ResearchTechniquesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		//$this->autoRender = false;
		$query = $this->ResearchTechniques->find('all', ['order' => ['ResearchTechniques.order' => 'ASC']]);
		//$techniquesQuery = $this->Techniques->find('all');
		$rawTechniques = $query->all();
		$techniques = [];
		foreach($rawTechniques as $technique) {
			$techniques[$technique->id] = $technique;
		}
		$status = 'success';
		$this->log("Research Techniques Loaded", 'info');
		$this->set(compact('techniques', 'status'));
		$this->set('_serialize', ['techniques', 'status']);
	}
}
