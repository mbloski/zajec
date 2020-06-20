<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Discord', [
            'token' => Configure::read('discord.token'),
        ]);

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Muffin/OAuth2.OAuth' => [
                    'providers' => [
                        'discord' => [
                            'className' => 'Wohali\OAuth2\Client\Provider\Discord',
                            'options' => [
                                'clientId' => Configure::read('discord.oauth.clientId'),
                                'clientSecret' => Configure::read('discord.oauth.clientSecret'),
                                'redirectUri' => Configure::read('discord.oauth.redirectUri'),
                            ],
                        ]
                    ],
                ],
            ],
            'loginAction' => ['controller' => 'Users', 'action' => 'login'],
            'authError' => null,
        ]);
    }

    public function beforeFilter(EventInterface $event)
    {
        $guildMembers = $this->Discord->getGuildMembersWithRoles(Configure::read('discord.guild'));
        $guildChannels = $this->Discord->getChannels(Configure::read('discord.guild'));



        $this->set(compact('guildMembers', 'guildChannels'));
        parent::beforeFilter($event);
    }
}
