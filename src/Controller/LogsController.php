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
        foreach ($this->viewBuilder()->getVar('guildChannels') as $n => $channel) {
            if ($channel['type'] == 4) {
               $channels[$channel['id']]['name'] = $channel['name'];
            } elseif($channel['type'] == 0) {
                $channels[$channel['parent_id']]['channels'][] = $channel;
            }
        }
        $categorizedGuildChannels = array_filter($channels, function($x) { return !empty($x['channels']); });
        $this->set(compact('categorizedGuildChannels', 'date'));

        $conditions = [];
        $channel = $this->request->getQuery('channel') ?? null;
        if ($channel) {
            $conditions['channel_id'] = $channel;
        }

        $search = $this->request->getQuery('search') ?? null;
        if ($search) {
            $conditions['message LIKE'] = '%'.$search.'%';
            // TODO: search specific date
        } else {
            $conditions['DATE(created, \'localtime\') ='] = $date;
        }

        $logs = $this->Logs->find('all', [
            'fields' => [
                'created' => 'DATETIME(created, \'localtime\')',
                'channel_id',
                'message_id',
                'author_id',
                'message',
                'deleted',
            ],
            'contain' => ['Attachments', 'EditHistory'],
            'conditions' => $conditions,
            'order' => [
                'channel_id'
            ],
        ]);

        $this->set(compact('logs'));
    }
}
