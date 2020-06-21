<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\LogsTable $Logs
 */
class LogsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->setLayout('logs');
        parent::beforeFilter($event);
    }

    public function index()
    {
        $date = $this->request->getQuery('date') ?? date('Y-m-d');

        $channels = ['' => []];
        foreach ($this->viewBuilder()->getVar('guildChannels') as $channel) {
            if ($channel['type'] == 4 ) {
                $channels[$channel['id']] = ['name' => $channel['name'], 'channels' => []];
            } elseif($channel['type'] == 0) {
                $channels[$channel['parent_id']]['channels'][] = $channel;
            }
        }
        $guildChannels = array_filter($channels, function($x) { return !empty($x['channels']); });
        $this->set(compact('guildChannels', 'date'));

        $channel = $this->request->getQuery('channel') ?? null;
        if (!$channel) {
            return;
        }

        $logs = $this->Logs->find('all', [
            'fields' => [
                'created' => 'DATETIME(created, \'localtime\')',
                'message_id',
                'author_id',
                'message',
                'deleted',
            ],
            'contain' => ['Attachments', 'EditHistory'],
            'conditions' => [
                'DATE(created, \'localtime\') =' => $date,
                'channel_id' => $channel,
            ]
        ]);

        $this->set(compact('logs'));
    }
}
