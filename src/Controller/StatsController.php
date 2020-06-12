<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;

/**
 * Stats Controller
 *
 */
class StatsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $guildMembers = json_decode($this->Discord->getGuildMembers(Configure::read('discord.guild')), true);
        $top = $this->Stats->getTop();
        $this->loadModel('Quotes');
        $quotes = $this->Quotes->find('all');

        $this->set(compact('top', 'quotes', 'guildMembers'));
    }
}
