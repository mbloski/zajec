<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Utility\Hash;

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
        $dailyActivity = $this->Stats->getDaily(14);
        $mostActiveTimes = $this->Stats->getMostActiveTimes();

        $this->loadModel('Quotes');
        $quotes = $this->Quotes->find('all');

        $this->set(compact('top', 'dailyActivity', 'mostActiveTimes', 'quotes', 'guildMembers'));
    }
}
