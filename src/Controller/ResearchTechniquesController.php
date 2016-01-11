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
			if(substr($technique->video, 0, 1) === 'a') {
				$technique->video = unserialize($technique->video);
			}
			$techniques[$technique->id] = $technique;
		}
		//exit;
		$status = 'success';
		$this->infolog("Research Techniques Loaded");
		$this->set(compact('techniques', 'status'));
		$this->set('_serialize', ['techniques', 'status']);
	}
}
