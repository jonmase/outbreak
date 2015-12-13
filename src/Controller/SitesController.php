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
		$status = 'success';
		$this->log("Sites Loaded", 'info');
		$this->set(compact('sites', 'status'));
		$this->set('_serialize', ['sites', 'status']);
	}
}
