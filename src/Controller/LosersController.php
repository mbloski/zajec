<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use PHPThumb\GD;

/**
 * Losers Controller
 *
 */
class LosersController extends AppController
{
    public function thumbnail($id, $name = null) {
        $thumbFile = Configure::read('faces_dir').'/thumb/'.$id.'.jpg';
        if (!is_file($thumbFile)) {
            $loser = $this->Losers->get($id);
            $thumb = new GD($loser->getPictureUrl(), []);
            $thumb->resize(180, 240);
            $thumb->save($thumbFile);
        }

        return $this->getResponse()->withType('image/jpg')->withFile($thumbFile);
    }

    public function photo($id, $name = null) {
        $loser = $this->Losers->get($id);
        return $this->getResponse()->withFile($loser->getPictureUrl());
    }
}
