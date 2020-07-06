<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Quotes Controller
 *
 */
class QuotesController extends AppController
{
    public function index() {
        $quotes = $this->Quotes->find('all', [
            'fields' => [
                'created' => 'datetime(created, \'localtime\')',
                'id',
                'author_id',
                'name',
                'value',
            ]
        ]);

        $this->set(compact('quotes'));
    }
}
