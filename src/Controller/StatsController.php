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
        $guildMembers = $this->Discord->getGuildMembersWithRoles(Configure::read('discord.guild'));
        $guildChannels = $this->Discord->getChannels(Configure::read('discord.guild'));
        $top = $this->Stats->getTop();
        $dailyActivity = $this->Stats->getDaily(14);
        $mostActiveTimes = $this->Stats->getMostActiveTimes();
        $topChannels = $this->Stats->getTopChannels()->all();
        $topBadwords = $this->Stats->getTopBadwords(2);
        $foulLine = null;
        if (count($topBadwords) > 0) {
            $foulLine = $this->Stats->getFoulLine(array_keys($topBadwords)[0]);
        }

        $this->loadModel('Quotes');
        $quotes = $this->Quotes->find('all', [
            'fields' => [
                'created' => 'datetime(created, \'localtime\')',
                'id',
                'name',
                'value',

            ]
        ]);

        $reactions = $this->Stats->getTopReactions(10);

        $this->set(compact('top', 'dailyActivity', 'mostActiveTimes', 'quotes', 'reactions', 'topChannels', 'topBadwords', 'foulLine', 'guildMembers', 'guildChannels'));
    }
}
