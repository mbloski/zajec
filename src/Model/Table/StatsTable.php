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
}
