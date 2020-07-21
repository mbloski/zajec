<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Color helper
 */
class ColorHelper extends Helper
{
    function rgbify($val, $min = 0, $max = 100) {
        $intensity = ($val - $min) / ($max - $min);
        if ($intensity > 0.5) {
            $g = 255;
            $r = round(2 * (1 - $intensity) * 255);
        } else {
            $r = 255;
            $g = round(2 * $intensity * 255);
        }

        return str_pad((string)dechex($r), 2, '0', STR_PAD_LEFT)
            .str_pad((string)dechex($g), 2, '0', STR_PAD_LEFT)
            .'00';
    }
}
