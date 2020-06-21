<?php
namespace App\Model\Table;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

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

    public function getDaily(int $days = 7, $author_id = null) {
        $conditions = [
            'created > (SELECT DATETIME(\'now\', \''.-$days.' day\'))',
        ];
        if ($author_id) {
            $conditions['author_id'] = $author_id;
        }

        $messages = TableRegistry::getTableLocator()->get('Messages');
        $q = $messages->find('all', [
            'conditions' => $conditions,
            'fields' => [
                'interval' => 'datetime((strftime(\'%s\', datetime(created, \'localtime\')) / 21600) * 21600, \'unixepoch\')',
                'date' => 'date(created, \'localtime\')',
                'time' => 'strftime(\'%H\', time((strftime(\'%s\', datetime(created, \'localtime\')) / 21600) * 21600, \'unixepoch\'))',
                'count' => 'COUNT(1)',
            ],
            'group' => [
                'interval',
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

    public function getMostActiveTimes($author_id = null) {
        $conditions = [];
        if ($author_id) {
            $conditions['author_id'] = $author_id;
        }

        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'conditions' => $conditions,
            'fields' => [
                'interval' => 'time((strftime(\'%s\', datetime(created, \'localtime\')) / 3600) * 3600, \'unixepoch\')',
                'hour' => 'strftime(\'%H\', datetime(created, \'localtime\'))',
                'count' => 'COUNT(1)',
            ],
            'group' => [
                'interval',
            ],
        ]);
    }

    public function getTopReactions($c = null, $author_id = null) {
        $conditions = [];
        if ($author_id) {
            $conditions['author_id'] = $author_id;
        }
        $reactions = TableRegistry::getTableLocator()->get('Reactions');
        return $reactions->find('all', [
            'conditions' => $conditions,
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

    public function getTopChannels($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'fields' => [
                'channel_id',
                'count' => 'COUNT(1)',
                'most_active' => '(select author_id from messages m where m.channel_id = Messages.channel_id and m.deleted = 0 limit 1)',
                'random_message' => '(select (\'<<@\' || author_id || \'>> \' || message) from messages m where m.channel_id = Messages.channel_id and length(message) between 1 and 200 and m.deleted = 0 order by random() limit 1)',
            ],
            'group' => [
                'Messages.channel_id',
            ],
            'conditions' => [
                'deleted' => false,
            ],
            'order' => [
                'count' => 'DESC',
            ],
            'limit' => $c,
        ]);
    }

    public function getTopBadwords($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');

        $ret = Cache::read('top_badwords');
        if (!$ret) {
            $ret = $messages->find('list', [
                'keyField' => 'author_id',
                'valueField' => ['percent'],
                'fields' => [
                    'author_id',
                    'bad_words' => 'COUNT(1)',
                    'total_lines' => '(SELECT COUNT(1) FROM messages m WHERE m.author_id = Messages.author_id AND deleted = 0)',
                    'percent' => '(COUNT(1) * 1.0 / (SELECT COUNT(1) FROM messages m WHERE m.author_id = Messages.author_id AND deleted = 0)) * 100',
                ],
                'conditions' => [
                    'deleted' => false,
                    'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                    'OR' => array_map(function($x) { return ['message LIKE' => '%'.$x.'%']; }, Configure::read('bad_words') ?? [])
                ],
                'order' => [
                    'percent' => 'DESC',
                ],
                'group' => [
                    'author_id',
                ],
                'limit' => $c,
            ])->toArray();

            Cache::write('top_badwords', $ret);
        }

        return $ret;
    }

    public function getMostCommonBadwords() {
        $messages = TableRegistry::getTableLocator()->get('Messages');

        $fields = [];
        foreach (Configure::read('bad_words') as $word) {
            /* not accurate but seems good enough */
            $fields[$word] = '(SELECT COUNT(1) FROM messages WHERE message LIKE \''.'% '.$word.' %'.'\' OR message LIKE \''.$word.'%'.'\' OR message LIKE \''.$word.'\')';
        }

        $ret = Cache::read('most_common_badwords');
        if (!$ret) {
            $ret = $messages->find('all', [
                'fields' => $fields,
                'conditions' => [
                    'deleted' => false,
                    'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                    'OR' => array_map(function ($x) {
                        return ['message LIKE' => '%' . $x . '%'];
                    }, Configure::read('bad_words') ?? [])
                ],
                'group' => [
                    '1',
                ]
            ])->first()->toArray();
            arsort($ret);

            Cache::write('most_common_badwords', $ret);
        }

        return $ret;
    }

    public function getWordOccurences($word) {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'fields' => [
                $word => 'COUNT(1)'
            ],
            'conditions' => [
                'deleted' => false,
                'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                'OR' => [
                    ['message LIKE' => '%'.$word.'%'],
                ]
            ],
        ])->count();
    }

    public function getTopAngry($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');

        $ret = Cache::read('top_angry');
        if (!$ret) {
            $ret = $messages->find('list', [
                'keyField' => 'author_id',
                'valueField' => ['percent'],
                'fields' => [
                    'author_id',
                    'angry' => 'COUNT(1)',
                    'total_lines' => '(SELECT COUNT(1) FROM messages m WHERE m.author_id = Messages.author_id AND deleted = 0)',
                    'percent' => '(COUNT(1) * 1.0 / (SELECT COUNT(1) FROM messages m WHERE m.author_id = Messages.author_id AND deleted = 0)) * 100',
                ],
                'conditions' => [
                    'deleted' => false,
                    'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                    'OR' => array_map(function($x) { return ['message LIKE' => '%'.$x.'%']; }, Configure::read('angry_emoji') ?? [])
                ],
                'order' => [
                    'percent' => 'DESC',
                ],
                'group' => [
                    'author_id',
                ],
                'limit' => $c,
            ])->toArray();

            Cache::write('top_angry', $ret);
        }

        return $ret;
    }

    public function getTopQuestions($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');

        $ret = Cache::read('top_questions');
        if (!$ret) {
            $ret = $messages->find('list', [
                'keyField' => 'author_id',
                'valueField' => ['percent'],
                'fields' => [
                    'author_id',
                    'questions' => 'COUNT(1)',
                    'total_lines' => '(SELECT COUNT(1) FROM messages m WHERE m.author_id = Messages.author_id AND deleted = 0)',
                    'percent' => '(COUNT(1) * 1.0 / (SELECT COUNT(1) FROM messages m WHERE m.author_id = Messages.author_id AND deleted = 0)) * 100',
                ],
                'conditions' => [
                    'deleted' => false,
                    'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                    'OR' => [
                        ['message LIKE' => '%? %'],
                        ['message LIKE' => '%?'],
                    ],
                ],
                'order' => [
                    'percent' => 'DESC',
                ],
                'group' => [
                    'author_id',
                ],
                'limit' => $c,
            ])->toArray();

            Cache::write('top_questions', $ret);
        }

        return $ret;
    }

    public function getLongestLines($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');

        $ret = Cache::read('long_lines');
        if (!$ret) {
            $ret = $messages->find('list', [
                'keyField' => 'author_id',
                'valueField' => ['average_length'],
                'fields' => [
                    'author_id',
                    'average_length' => 'AVG(LENGTH(message))',
                    'message',
                ],
                'conditions' => [
                    'deleted' => false,
                    'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                ],
                'order' => [
                    'average_length' => 'DESC',
                ],
                'group' => [
                    'author_id',
                ],
                'limit' => $c,
            ])->toArray();

            Cache::write('long_lines', $ret);
        }

        return $ret;
    }

    public function getShortestLines($c = null) {
        $messages = TableRegistry::getTableLocator()->get('Messages');

        $ret = Cache::read('short_lines');
        if (!$ret) {
            $ret = $messages->find('list', [
                'keyField' => 'author_id',
                'valueField' => ['average_length'],
                'fields' => [
                    'author_id',
                    'average_length' => 'AVG(LENGTH(message))',
                    'message',
                ],
                'conditions' => [
                    'deleted' => false,
                    'author_id NOT IN' => Configure::read('excluded_authors') ?? [],
                    'LENGTH(message) > 0',
                ],
                'order' => [
                    'average_length' => 'ASC',
                ],
                'group' => [
                    'author_id',
                ],
                'limit' => $c,
            ])->toArray();

            Cache::write('short_lines', $ret);
        }

        return $ret;
    }

    function getFoulLine($authorId) {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'fields' => [
                'message',
            ],
            'conditions' => [
                'author_id' => $authorId,
                'deleted' => false,
                'OR' => array_map(function($x) { return ['message LIKE' => '%'.$x.'%']; }, Configure::read('bad_words') ?? [])
            ],
            'order' => [
                'RANDOM()',
            ],
            'limit' => 1,
        ])->first();
    }

    function getAngryLine($authorId) {
        $messages = TableRegistry::getTableLocator()->get('Messages');
        return $messages->find('all', [
            'fields' => [
                'message',
            ],
            'conditions' => [
                'author_id' => $authorId,
                'deleted' => false,
                'OR' => array_map(function($x) { return ['message LIKE' => '%'.$x.'%']; }, Configure::read('angry_emoji') ?? [])
            ],
            'order' => [
                'RANDOM()',
            ],
            'limit' => 1,
        ])->first();
    }
}
