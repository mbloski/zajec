<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Utility\Hash;

/**
 * Stats Controller
 *
 */
class UsersController extends AppController
{
    public function login() {
        if ($user = $this->Auth->user()) {
            $discordUser = $this->Discord->getGuildMember(Configure::read('discord.guild'), $user['user_id']);
            if (!isset($discordUser['user'])) {
                $this->Auth->logout();
                $this->Flash->error('You are not a member of this community.');
                return null;
            }
            return $this->redirect('/');
        }
    }
}
