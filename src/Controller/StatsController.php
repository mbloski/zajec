<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;

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
        $top = $this->Stats->getTop();
        $dailyActivity = $this->Stats->getDaily(14);
        $mostActiveTimes = $this->Stats->getMostActiveTimes();
        $topChannels = $this->Stats->getTopChannels()->all();
        $topBadwords = $this->Stats->getTopBadwords(2);
        $foulLine = null;
        if (count($topBadwords) > 0) {
            $foulLine = $this->Stats->getFoulLine(array_keys($topBadwords)[0]);
        }
        $mostCommonBadwords = $this->Stats->getMostCommonBadwords();
        $topAngry = $this->Stats->getTopAngry(2);
        if (count($topAngry) > 0) {
            $angryLine = $this->Stats->getAngryLine(array_keys($topAngry)[0]);
        }
        $topQuestions = $this->Stats->getTopQuestions(2);
        $longestLines = $this->Stats->getLongestLines(2);
        $shortestLines = $this->Stats->getShortestLines(2);

        $wordOccurences = [];
        foreach (Configure::read('word_occurences') ?? [] as $word) {
            $wordOccurences[$word] = $this->Stats->getWordOccurences($word);
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

        $this->set(compact('top', 'dailyActivity', 'mostActiveTimes', 'quotes', 'reactions', 'topChannels', 'topBadwords', 'foulLine', 'mostCommonBadwords', 'topAngry', 'angryLine', 'topQuestions', 'longestLines', 'shortestLines', 'wordOccurences'));
    }

    /**
     * User method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function user($id = null)
    {
        $user = $this->Discord->getGuildMember(Configure::read('discord.guild'), $id);
        if (!isset($user['user'])) {
            throw new NotFoundException();
        }

        $dailyActivity = $this->Stats->getDaily(14, $id);
        $mostActiveTimes = $this->Stats->getMostActiveTimes($id);
        $reactions = $this->Stats->getTopReactions(10, $id);
        $mostMentioned = $this->Stats->mostMentioned($id);
        $mostMentionedBy = $this->Stats->mostMentionedBy($id);

        $this->set(compact('user', 'dailyActivity', 'mostActiveTimes', 'reactions', 'mostMentioned', 'mostMentionedBy'));
    }
}
