<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\View;

/**
 * Discord helper
 */
class DiscordHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    private function colorName($user, $prefix = '') {
        if (!$user) {
            return null;
        }

        $name = $user['nick'] ?? $user['user']['username'];
        $color = '';
        foreach ($user['roles'] as $role) {
            if ($role['color'] !== 0) {
                $color = 'color:#'.str_pad(dechex($role['color']), 6, '0', STR_PAD_LEFT).';';
            }
        }
        return '<span style="font-weight: bold;'.$color.'">'.h($prefix.$name).'</span>';
    }

    public function getUserById($guildMembers, $id, $prop = null) {
        $key = array_search($id, Hash::extract($guildMembers, '{n}.user.id'));
        if (!$key) {
            return null;
        }

        if (($ret = $guildMembers[$key]) && $prop) {
            return Hash::get($ret, $prop);
        }

        return $ret;
    }

    public function getUsernameWithColor($guildMembers, $id) {
        $ret = $this->getUserById($guildMembers, $id);
        return $this->colorName($ret);
    }

    public function resolveEmoji($str, int $h = 16) {
        return preg_replace('/&lt;\:(\w*)\:(\d*)&gt;/', '<img style="height:'.$h.'px;" class="emoji" alt=":$1:" title=":$1:" src="https://cdn.discordapp.com/emojis/$2.png">', htmlspecialchars($str ?? ''));
    }

    public function resolveNickname($guildMembers, $str) {
        return preg_replace_callback('/&lt;@!?(\d*)&gt;/', function($x) use ($guildMembers) {
            $user = $this->getUserById($guildMembers, $x[1]);
            if (!$user) {
                return '@'.$x[1];
            }

            return $this->colorName($user, '@');
        }, $str);
    }

}
