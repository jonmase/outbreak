<?php
/**
	Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		
		$this->loadComponent('Auth', [
			/*'loginAction' => [
				'controller' => 'Pages',
				'action' => 'display',
				'denied'
			],*/
			'loginAction' => [
				'controller' => 'Attempts',
				'action' => 'forbidden',
				//'denied'
			],
			'loginRedirect' => [
				'controller' => 'Attempts',
				'action' => 'index'
			],
			'logoutRedirect' => [
				'controller' => 'Pages',
				'action' => 'display',
				'loggedout'
			]
		]);
    }

	public function beforeFilter(Event $event) {
		$maintenance = 0;
        if($maintenance && $this->name !== 'Pages') {
			$this->redirect('/maintenance');
		}
	}
	
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
	
	public function infolog($message = null) {
		$this->log($_SERVER['REMOTE_ADDR'] . ". User ID: " . $this->Auth->user('id') . "; Username: " . $this->Auth->user('lti_displayid') . ". " . $message, 'info');
	}
}
