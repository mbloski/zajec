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
            $height = $magick->getImageHeight();
            $width = $magick->getImageWidth();;
            $h = 240.0 / $height;
            $w = 180.0 / $width;
            $magick->sampleImage((int)($width * $w), (int)($height * $h));
            $magick->writeImage($thumbFile);
        }

        return $this->getResponse()->withType('image/jpg')->withFile($thumbFile);
    }

    public function photo($id, $name = null) {
        $loser = $this->Losers->get($id);
        return $this->getResponse()->withFile($loser->getPictureUrl());
    }
}
