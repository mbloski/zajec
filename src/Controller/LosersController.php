<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Cookie\Cookie;
use Cake\Utility\Hash;

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
            $magick = new \Imagick($loser->getPictureUrl());
            $h = 240.0 / $magick->getImageHeight();
            $w = 180.0 / $magick->getImageWidth();
            $magick->setImagebackgroundcolor('#313131');
            $magick->thumbnailImage((int)($magick->getImageHeight() * $h), (int)($magick->getImageWidth() * $w), true, true);
            @file_put_contents($thumbFile, $magick->getImageBlob());
        }

        return $this->getResponse()->withType('image/jpg')->withStringBody($thumbFile);
    }

    public function photo($id, $name = null) {
        $loser = $this->Losers->get($id);
        return $this->getResponse()->withFile($loser->getPictureUrl());
    }
}
