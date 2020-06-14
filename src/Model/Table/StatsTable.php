<?php
namespace App\Model\Table;

use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class StatsTable extends \Cake\ORM\Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('messages');
    }

    public function getTop($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'fields' => [
                'count' => 'COUNT(1)',
                'author_id',
                'seen' => 'MAX(created)',
            ],
            'group' => [
                'author_id',
            ],
            'order' => [
                'count' => 'DESC',
            ],
            'limit' => $c,
        ]);
    }

    public function getDaily(int $days = 7) {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        $q = $messages->find('all', [
            'fields' => [
                'interval' => 'datetime((strftime(\'%s\', datetime(created, \'localtime\')) / 21600) * 21600, \'unixepoch\')',
                'date' => 'date(created)',
                'time' => 'strftime(\'%H\', time((strftime(\'%s\', created) / 21600) * 21600, \'unixepoch\'))',
                'count' => 'COUNT(1)',
            ],
            'group' => [
                'interval',
            ],
            'conditions' => [
                'created > (SELECT DATETIME(\'now\', \''.-$days.' day\'))',
            ],
        ]);

        $ret = [];
        foreach ($q->toArray() as $key => $item) {
            if (!isset($ret[$item['date']])) {
                $ret[$item['date']] = [0 => 0, 6 => 0, 12 => 0, 18 => 0];
            }
            $ret[$item['date']][intval($item->time)] = intval($item->count);
        }

        return $ret;
    }

    public function getMostActiveTimes() {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'fields' => [
                'interval' => 'time((strftime(\'%s\', created) / 3600) * 3600, \'unixepoch\', \'localtime\')',
                'hour' => 'strftime(\'%H\', created)',
                'count' => 'COUNT(1)',
            ],
            'group' => [
                'interval',
            ],
        ]);
    }

    public function getTopReactions($c = null) {
        $reactions = TableRegistry::getTableLocator()->get('Reactions');
        return $reactions->find('all', [
            'fields' => [
                'count' => 'COUNT(1)',
                'reaction'
            ],
            'group' => [
                'reaction',
            ],
            'order' => [
                'count' => 'DESC',
            ],
            'limit' => $c,
        ]);
    }
}
