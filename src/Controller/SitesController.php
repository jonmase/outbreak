<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Sites Controller
 *
 * @property \App\Model\Table\SitesTable $Sites
 */
class SitesController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('load');
	}
	
	public function load() {
		$query = $this->Sites->find('all', [
			'order' => ['Sites.order' => 'ASC'],
		]);
		$rawSites = $query->all();
		$sites = [];
		foreach($rawSites as $site) {
			$sites[$site->id] = $site;
		}
		$this->set(compact('sites'));
		$this->set('_serialize', ['sites']);
		//pr($sites->toArray());
	}
}
