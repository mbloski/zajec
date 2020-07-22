<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Hash;

/**
 * Quotes Controller
 *
 */
class CookiePointsController extends AppController
{
    public function index() {
        $cookiePoints = $this->CookiePoints->find('all');
        $cookiePoints = Hash::combine($cookiePoints->toArray(), '{n}.target_author_id', '{n}', '{n}.author_id');

        $minRep = $this->CookiePoints->find('all')->select('count')->min('count');
        if ($minRep) {
            $minRep = $minRep->count;
        }

        $maxRep = $this->CookiePoints->find('all')->select('count')->max('count');
        if ($maxRep) {
            $maxRep = $maxRep->count;
        }
        $this->set(compact('cookiePoints', 'minRep', 'maxRep'));
    }
}
