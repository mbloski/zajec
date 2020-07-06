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
        $top = $this->Stats->getTop(10);
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

        $mostMentionedUsers = $this->Stats->mostMentionedUsers(2);

        $this->loadModel('Quotes');
        $quotes = $this->Quotes->find('all', [
            'fields' => [
                'created' => 'datetime(created, \'localtime\')',
                'id',
                'author_id',
                'name',
                'value',
            ]
        ]);

        $reactions = $this->Stats->getTopReactions(10);

        $this->set(compact('top', 'dailyActivity', 'mostActiveTimes', 'quotes', 'reactions', 'topChannels', 'topBadwords', 'foulLine', 'mostCommonBadwords', 'topAngry', 'angryLine', 'topQuestions', 'longestLines', 'shortestLines', 'wordOccurences', 'mostMentionedUsers'));
    }

    /**
     * Channel method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function channel($id = null)
    {
        $channels = $this->viewBuilder()->getVar('guildChannels');
        $channel = null;
        $key = array_search($id, array_column($channels, 'id'));
        if ($key !== false) {
            $channel = $channels[$key];
        }

        if ($channel === null || $channel['type'] !== 0) {
            throw new NotFoundException();
        }

        $top = $this->Stats->getTop(10, ['channel_id' => $id]);
        $dailyActivity = $this->Stats->getDaily(14, ['channel_id' => $id]);
        $mostActiveTimes = $this->Stats->getMostActiveTimes(['channel_id' => $id]);

        $this->set(compact('channel', 'top', 'dailyActivity', 'mostActiveTimes'));
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

        $this->loadModel('Losers');
        $pictures = $this->Losers->find('all', [
            'conditions' => [
                'author_id' => $id,
            ]
        ]);

        $dailyActivity = $this->Stats->getDaily(14, ['author_id' => $id]);
        $mostActiveTimes = $this->Stats->getMostActiveTimes(['author_id' => $id]);
        $reactions = $this->Stats->getTopReactions(10, ['author_id' => $id]);
        $mostMentioned = $this->Stats->mostMentioned($id);
        $mostMentionedBy = $this->Stats->mostMentionedBy($id);

        $this->set(compact('user', 'dailyActivity', 'mostActiveTimes', 'reactions', 'mostMentioned', 'mostMentionedBy', 'pictures'));
    }
}
